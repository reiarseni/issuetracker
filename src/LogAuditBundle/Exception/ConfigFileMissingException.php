<?php

declare(strict_types=1);

namespace LogAuditBundle\Exception;

class ConfigFileMissingException extends \Exception
{
    public function __construct($code = 0, \Exception $previous = null)
    {
        parent::__construct('The config file is missing.', $code, $previous);
    }
}
