<?php declare(strict_types=1);

namespace PayKit\Tests\Support;

use PayKit\Support\ArrayEventLockStore;
use PayKit\Support\WebhookDeduplicator;
use PHPUnit\Framework\TestCase;

final class WebhookDeduplicatorTest extends TestCase
{
    public function test_execute_once_prevents_duplicate_executions(): void
    {
        $deduplicator = new WebhookDeduplicator(new ArrayEventLockStore());
        $count = 0;

        $callback = function () use (&$count) {
            $count++;
            return 'processed';
        };

        // First call executed
        $res1 = $deduplicator->executeOnce('stripe', 'evt_123', $callback);
        $this->assertEquals('processed', $res1);
        $this->assertEquals(1, $count);

        // Second call (duplicate) skipped
        $res2 = $deduplicator->executeOnce('stripe', 'evt_123', $callback);
        $this->assertNull($res2);
        $this->assertEquals(1, $count);
    }

    public function test_unlocks_on_callback_exception(): void
    {
        $deduplicator = new WebhookDeduplicator(new ArrayEventLockStore());

        $this->expectException(\RuntimeException::class);

        try {
            $deduplicator->executeOnce('paystack', 'evt_fail', function () {
                throw new \RuntimeException('Database down');
            });
        } finally {
            // Lock should have been cleared after exception
            $this->assertFalse($deduplicator->isDuplicate('paystack', 'evt_fail'));
        }
    }
}
