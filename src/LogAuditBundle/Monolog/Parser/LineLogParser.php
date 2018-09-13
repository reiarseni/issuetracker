<?php

namespace LogAuditBundle\Monolog\Parser;

/**
 * Class LineLogParser
 */
class LineLogParser implements LogParserInterface
{
    /*** @var array */
    protected $pattern = array(
        'monolog_default' => '/\[(?P<date>.*)\] (?P<logger>\w+).(?P<level>\w+): (?P<message>[^\[\{]+) (?P<context>[\[\{].*[\]\}]) (?P<extra>[\[\{].*[\]\}])/',
        'monolog_error'   => '/\[(?P<date>.*)\] (?P<logger>\w+).(?P<level>\w+): (?P<message>(.*)+) (?P<context>[^ ]+) (?P<extra>[^ ]+)/',
    );

    /**
     * Para cada parseador hay que definir esto
     *
     * @var array
     */
    protected $levels = array (
      ':error' => 100,
      'php7:notice' => 200
    );

    /**
     * @param string $logLine
     *
     * @param string $type
     * @return array
     */
    public function parse($logLine, $type = 'monolog_default')
    {
        //El archivo no debe estar vacio
        if (!is_string($logLine) || strlen($logLine) === 0) {
            return array();
        }

        if ($type == 'monolog_default') {
            return $this->getMonologDefault($logLine);
        } elseif ($type == 'apache_error') {
            return $this->getApacheError($logLine);
        } elseif ($type == 'apache_access') {
            return $this->getApacheAccess($logLine);
        } elseif ($type == 'syslog') {
            return $this->getSyslog($logLine);
        }

    }

    public function getMonologDefault($log)
    {
        $regex = '/\[(?P<date>.*)\] (?P<logger>\w+).(?P<level>\w+): (?P<message>[^\[\{]+) (?P<context>[\[\{].*[\]\}]) (?P<extra>[\[\{].*[\]\}])/';

        //Hacer todos los posibles preg_matchs, o solo el primero
        preg_match($regex, $log, $data);

        if (!isset($data['date'])) {
            //return array();

            //hago otro intento (fallback) con la otra regexprex
            $regex = '/\[(?P<date>.*)\] (?P<logger>\w+).(?P<level>\w+): (?P<message>(.*)+)\s*(.*)/';
            //Hacer todos los posibles preg_matchs, o solo el primero
            preg_match($regex, $log, $data);
            $data['context']=null;$data['extra']=null;///estabezco estos vaklores para que no haya problema en la vista
            //dump($data); //die;

            if (!isset($data['date'])) {
                dump($log); die; //Si algo llega aqui, logear que hubo algunas lineas que no se parsearon
                return array();
                //hago otro intento
            }
        }

        $date = \DateTime::createFromFormat('Y-m-d H:i:s', $data['date']);

        return $array = array(
            'date'    => $date,
            'logger'  => $data['logger'],
            'level'   => $data['level'],
            'message' => $data['message'],
            'context' => json_decode($data['context'], true,2),
            'extra'   => json_decode($data['extra'], true,2)
        );
    }

    public function getApacheError($log)
    {
        //$regex = '/\[(?P<date>.*)\]\s+\[(?P<level>.*)\]\s+\[(?P<pid>.*)\]\s+\[(?P<client>.*)\]\s*(.*)/';//ESTA ES LA OKOKOK pero no separa el error por : y .
        //$regex = '/\[(?P<date>.*)\]\s+\[(?P<level>.*)\]\s+\[(?P<pid>.*)\]\s+\[(?P<client>.*)\]\s+([a-zA-Z0-9\s]+:?\.?)\s*(.*)/';   //OKOK, resuelto lo anterior pero sin referer
        $regex = '/\[(?P<date>.*)\]\s+\[(?P<level>.*)\]\s+\[(?P<pid>.*)\]\s+\[(?P<client>.*)\]\s+([a-zA-Z0-9\s]+:?\.?)\s*(((?P<tracev1>.*), referer: (?P<referer>.*))|(?P<tracev2>.*))/';

        //Hacer todos los posibles preg_matchs, o solo el primero
        preg_match($regex, $log, $data);   dump($data); //die;

        if (!isset($data['date'])) {
            //hago otro intento (fallback) con la otra regexprex
            $regex = '/\[(?P<date>.*)\]\s+\[(?P<level>.*)\]\s+\[(?P<pid>.*)\]\s+([a-zA-Z0-9\s]+:?\.?)\s*(((?P<tracev1>.*), referer: (?P<referer>.*))|(?P<tracev2>.*))/';
            //Hacer todos los posibles preg_matchs, o solo el primero
            preg_match($regex, $log, $data);
            $data['client']='';///estabezco estos vaklores para que no haya problema en la vista
            dump($data);

            if (!isset($data['date'])) {
                //dump($log); die; //Si algo llega aqui, logear que hubo algunas lineas que no se parsearon
                return array();
                //hago otro intento
            }
        }

        $dateFormat = 'D M d H:i:s.u Y'; //todo hay que definir a nivel de configuaraciones
        $date = \DateTime::createFromFormat($dateFormat, $data['date']);

        $trace = $data['tracev1']!=''?$data['tracev1']:(isset($data['tracev2'])?$data['tracev2']:'');

        $referer = $data['referer']!=''? ' <b>REFERRER</b> <a href="'.$data['referer'].'">'.$data['referer'].'<a>' :'';

        $mensaje = '<b>'. $data[5] . '</b> ' . $trace . $referer . ' <b>PID</b>: ' . $data['pid'] . ' <b>CLIENT</b>: ' . $data['client'];

        return array(
            'date' => $date,
            'logger' => $data['level'],
            'level' => 'INFO', //todo implementar, hay que buscar patrones y definirlos a traves de configuraciones
            'message' => $mensaje,
            'context' => null,
            'extra' => null
        );
    }

    public function getApacheAccess($logLine)
    {
        $regex =   '/^(?P<IP>\S+.*) (?P<ident>\S)
                    \ (?P<auth_user>.*?) # Spaces are allowed here, can be empty.
                    \ (?P<date>\[[^]]+\])
                    \ "(?P<http_start_line>.+ .+)" # At least one space: HTTP 0.9
                    \ (?P<status_code>[0-9]+) # Status code is _always_ an integer
                    \ (?P<response_size>(?:[0-9]+|-)) # Response size can be -
                    \ "(?P<referrer>.*)" # Referrer can contains everything: its just a header
                    \ "(?P<user_agent>.*)"$/x';

        //Hacer todos los posibles preg_matchs, o solo el primero
        preg_match($regex, $logLine, $data);

        $dateFormat = 'd/M/Y:H:i:s'; //todo hay que definir a nivel de configuraciones
        $data['date'] = substr( $data['date'],1,20);
        $date = \DateTime::createFromFormat($dateFormat, $data['date']);

        if (!isset($data['date'])) {
            dump($data); die; //Si algo llega aqui, logear que hubo algunas lineas que no se parsearon
            return array();
        }


        //http://www.geoiptool.com/en/?IP=%p

        $ip =$this->escape($data['IP']);
        $ip = $ip!=''? ' <a target="_blank" href="'. sprintf('http://www.geoiptool.com/en/?IP=%s',$ip).'">'.$ip.'<a>' :'';

        $referrer =$this->escape($data['referrer']);
        $referrer = $referrer!=''? ' <a target="_blank" href="'.$referrer.'">'.$referrer.'<a>' :'';

        $message = '<b>IP</b>: ' . $ip  .
            ' <b>IDENT</b>: ' . $this->escape($data['ident']) .
            ' <b>AUTH_USER</b>: ' . $this->escape($data['auth_user']) .
            ' <b>HTTP_START_LINE</b>: ' . $this->escape($data['http_start_line']) .
            ' <b>STATUS_CODE</b>: ' . $this->escape($data['status_code']) .
            ' <b>RESPONSE_SIZE</b>: ' . $this->escape($data['response_size']) .
            ' <b>REFERRER</b>: ' . $referrer .
            ' <b>USER_AGENT</b>: ' . $this->escape($data['user_agent']) ;

        return array(
            'date' => $date,
            'logger' => $data['status_code'],
            'level' => 'INFO', //todo implementar, hay que buscar patrones y definirlos a traves de configuraciones
            'message' => $message,
            'context' => null,
            'extra' => null
        );
    }

    public function getSyslog($log)
    {
        $regex = '/^(?P<month>\S+)\s(?P<day>\S+)\s(?P<time>\S+)\s(?P<user>\S+)\s(?P<level>\S+):(?P<message>.*)/';

        //Hacer todos los posibles preg_matchs, o solo el primero
        preg_match($regex, $log, $data);

        if (!isset($data['month'])) {
                dump($log); die; //Si algo llega aqui, logear que hubo algunas lineas que no se parsearon
                return array();
        }

        $levelLimpio = preg_split("/[\[]+/", $data['level']);

        $date = $data['month'] .' '. $data['day'].' '. $data['time'].' 2018';

        //Sep 11 08:25:44 reinaldo-pc systemd[1]: Starting Daily apt activities...
        $date = \DateTime::createFromFormat('M d H:i:s Y', $date);  //D M d H:i:s.u Y

        return $array = array(
            'date'    => $date,
            'logger'  => $levelLimpio[0],
            'level'   => 'INFO',
            'message' => $data['user'] . ' : ' . $data['level']. ' : ' . $data['message'],
            'context' => json_decode($data[0], true,2),
            'extra'   => json_decode($data[0], true,2),
        );
    }

    /**
     * @param string $name
     * @param string $pattern
     *
     * @throws \RuntimeException
     */
    public function registerPattern($name, $pattern)
    {
        if (!isset($this->pattern[$name])) {
            $this->pattern[$name] = $pattern;
        } else {
            throw new \RuntimeException("Pattern $name already exists");
        }
    }

    public function escape($str)
    {
        return htmlEntities($str , ENT_QUOTES);
    }


}
