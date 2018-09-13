<?php

namespace LogAuditBundle\Monolog\Parser;

interface LogParserInterface
{
    /**
     * Parsea un log de tipo symfony, tipo apache error, o apache access
     *
     * @param $log
     * @param $type
     *
     * @return mixed
     */
    public function parse($log, $type);
}
