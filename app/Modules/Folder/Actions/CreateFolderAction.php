<?php

namespace App\Modules\Folder\Actions;

use App\Modules\Folder\DTOs\CreateFolderData;
use App\Modules\Folder\Models\Folder;

class CreateFolderAction
{
    public function execute(CreateFolderData $data): Folder
    {
        return Folder::create([
            'user_id' => $data->user_id,
            'name' => $data->name,
            'parent_id' => $data->parent_id,
        ]);
    }
}
