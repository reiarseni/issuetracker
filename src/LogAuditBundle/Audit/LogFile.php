<?php

declare(strict_types=1);

namespace LogAuditBundle\Audit;

use Doctrine\Common\Collections\ArrayCollection;
use LogAuditBundle\Monolog\Parser\LineLogParser;
use LogAuditBundle\Util\StringUtil;

class LogFile
{
    protected $name;
    protected $slug; //slug del nombre
    protected $path;
    protected $pathType; // 'local' 'ftp', etc
    protected $type;

    protected $lines;
    protected $filesystem;

    protected $loggers;

    public function __construct($args)
    {
        setlocale(LC_ALL, 'en_US.UTF8');
        $this->name = $args['name'];
        $this->slug = StringUtil::toAscii($this->name);
        $this->path = $args['path'];
        $this->type = $args['type'];
    }

    /**
     * Carga las lineas de un archivo de logs mediante de forma paginada.
     *
     * @param $page
     * @param $limit
     *
     * @return $this
     */
    public function load($page, $limit)
    {
        $this->filesystem = new LocalFilesystem();

        //La idea es poder leer desde cualquier sistema de archivos, aqui esta puesto local pero hay que utilizar el bundle original
        $file = $this->filesystem->read($this->path);

        //Convierto el fichero de logs en un array cada elemnto es una linea
        $lines = explode("\n", $file);

        //Reverso el array para ver las lineas mas recientes primero
        $cantidadLinesa = \count($lines);
        $i = 1;
        $linesTemp = [];
        while ($i <= $cantidadLinesa) {
            $linesTemp[] = array_pop($lines);
            ++$i;
        }
        $lines = $linesTemp;

        $parser = new LineLogParser(); //parser de monolog

        $primerResultado = ($page - 1) * $limit + 1;
        $ultimoResultado = $primerResultado + $limit;

        $this->loggers = new ArrayCollection();
        $contador = 0;
        foreach ($lines as $logLine) {
            ++$contador;
            //Solo parseo las lineas que estan dentro de la paginacion envida desde el controller
            if ($contador >= $primerResultado && $contador <= $ultimoResultado) {
                $entry = $parser->parse($logLine, $this->type);
                if (\count($entry) > 0) {
                    $this->lines[] = $entry;

                    //Adiciono esto
                    if (!$this->loggers->contains($entry['logger'])) {
                        $this->loggers->add($entry['logger']);
                    }
                } else {
                    --$contador;
                }
            } else {
                $this->lines[] = null;
            }
        }

        return $this;
    }

    public function getLine($line)
    {
        return $this->lines[(int) $line];
    }

    public function getLines()
    {
        return $this->lines;
    }

    public function setLines($lines)
    {
        $this->lines = $lines;
    }

    public function countLines()
    {
        return \count($this->lines);
    }

    public function getName()
    {
        return $this->name;
    }

    public function getSlug()
    {
        return $this->slug;
    }

    public function getLoggers()
    {
        return $this->loggers;
    }
}
