<?php

declare(strict_types=1);

namespace LogAuditBundle\Monolog\Reader;

use LogAuditBundle\Monolog\Parser\LineLogParser;

/**
 * Class AbstractReader.
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
