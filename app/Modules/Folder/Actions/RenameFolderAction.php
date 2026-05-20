<?php

namespace App\Modules\Folder\Actions;

use App\Modules\Folder\Models\Folder;

class RenameFolderAction
{
    public function execute(Folder $folder, string $newName): Folder
    {
        $folder->update(['name' => $newName]);
        return $folder;
    }
}
