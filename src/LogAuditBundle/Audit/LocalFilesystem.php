<?php

namespace LogAuditBundle\Audit;

class LocalFilesystem
{
    public function read($path)
    {
        $location = $path;
        $contents = file_get_contents($location);

        if ($contents === false) {
            return false;
        }

        return $contents;
    }
}