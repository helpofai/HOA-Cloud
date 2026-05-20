<?php

namespace App\Modules\Folder\Actions;

use App\Modules\Folder\Models\Folder;

class DeleteFolderAction
{
    public function execute(Folder $folder): bool
    {
        // Add logic here to delete physical files from disk if necessary
        // For virtual folders, we just delete the DB records
        return $folder->delete();
    }
}
