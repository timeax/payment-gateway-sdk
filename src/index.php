<?php declare(strict_types=1);

namespace PayKit;

use PayKit\Manager\DriverResolver;
use PayKit\Manager\GatewayManager;
use PayKit\Manager\GatewayRegistry;

/**
 * Minimal convenience entrypoint.
 * Composer autoload is the primary integration; this just offers a clean helper.
 */
final class Sdk
{
    public static function registry(): GatewayRegistry
    {
        return new GatewayRegistry();
    }

    public static function manager(GatewayRegistry $registry): GatewayManager
    {
        return new GatewayManager(new DriverResolver($registry));
    }
}

