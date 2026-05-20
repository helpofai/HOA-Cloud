<?php

namespace App\Modules\Folder\DTOs;

class CreateFolderData
{
    public function __construct(
        public readonly int $user_id,
        public readonly string $name,
        public readonly ?int $parent_id = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            user_id: $data['user_id'],
            name: $data['name'],
            parent_id: $data['parent_id'] ?? null,
        );
    }
}
