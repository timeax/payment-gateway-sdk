<?php declare(strict_types=1);

namespace PayKit\Payload\Common;

use JsonSerializable;

final readonly class UiManifest implements JsonSerializable
{
    /** @param array<int,UiModuleDescriptor> $modules
     * @param array<int,UiEntryDescriptor> $entries
     */
    public function __construct(
        public array $modules = [],
        public array $entries = [],
    )
    {
    }

    public function jsonSerialize(): array
    {
        return [
            'modules' => array_map(static fn(UiModuleDescriptor $m) => $m->jsonSerialize(), $this->modules),
            'entries' => array_map(static fn(UiEntryDescriptor $e) => $e->jsonSerialize(), $this->entries),
        ];
    }
}