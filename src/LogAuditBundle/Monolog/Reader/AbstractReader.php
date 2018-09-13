<?php

namespace LogAuditBundle\Monolog\Reader;

use LogAuditBundle\Monolog\Parser\LineLogParser;

/**
 * Class AbstractReader
 * @package Dubture\Monolog\Reader
 */
class AbstractReader
{

    /**
     * @param $days
     * @param $pattern
     *
     * @return LineLogParser
     */
    protected function getDefaultParser($days, $pattern)
    {
        return new LineLogParser($days, $pattern);
    }
}
