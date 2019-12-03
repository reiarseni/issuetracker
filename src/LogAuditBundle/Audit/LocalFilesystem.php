<?php

declare(strict_types=1);

namespace LogAuditBundle\Audit;

class LocalFilesystem
{
    public function read($path)
    {
        $location = $path;
        $contents = file_get_contents($location);

        if (false === $contents) {
            return false;
        }

        return $contents;
    }
}
