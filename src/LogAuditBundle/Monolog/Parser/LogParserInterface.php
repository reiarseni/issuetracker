<?php

declare(strict_types=1);

namespace LogAuditBundle\Monolog\Parser;

interface LogParserInterface
{
    /**
     * Parsea un log de tipo symfony, tipo apache error, o apache access.
     *
     * @param $log
     * @param $type
     *
     * @return mixed
     */
    public function parse($log, $type);
}
