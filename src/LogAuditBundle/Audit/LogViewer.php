<?php

namespace LogAuditBundle\Audit;

use Doctrine\Common\Collections\ArrayCollection;

class LogViewer
{
    //collection de logs files
    protected $logs;

    public function __construct($log = null)
    {
        setlocale(LC_ALL, 'en_US.UTF8');

        $this->logs = new ArrayCollection();

        if ($log != null) {
            $this->addLog($log);
        }

    }

    public function addLog($log)
    {
        return $this->logs->add($log);
    }

    public function hasLogs()
    {
        return !$this->logs->isEmpty();
    }

    public function getLogs()
    {
        return $this->logs;
    }

    /**
     * @param $logSlug
     * @return LogFile|null
     */
    public function getLog($logSlug)
    {
        foreach ($this->logs as $log) {
            if ($log->getSlug() == $logSlug) return $log;
        }
        return null;
    }

    /**
     * @return LogFile|null
     */
    public function getFirstLog()
    {
        return ($this->logs->count() > 0) ? $this->logs->first() : null;
    }

    public function logExists($log)
    {
        return $this->logs->contains($log);
    }
}
