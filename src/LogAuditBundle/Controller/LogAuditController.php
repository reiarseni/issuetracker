<?php

namespace LogAuditBundle\Controller;

use LogAuditBundle\Audit\LogFile;
use LogAuditBundle\Audit\LogViewer;
use Monolog\Logger;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class LogAuditController extends Controller
{

    /**
     * @Method("GET")
     * @Route("/logs/{logSlug}", name="log", defaults={"logSlug":"NA"} ,  options={"expose": true})
     */
    public function indexAction(Request $request,$logSlug)
    {
        $app = array();
        $app['debug'] = true;
        $app['config']['dateFormat'] = 'd.m.Y, H:i:s';
        $app['config']['display_logger'] = true;

        $viewer = new LogViewer();

        $args['name'] = 'Monolog-Default-Dev';
        //$args['path'] = '/home/reinaldo/htdocs/www/planillas.corpglez/app/logs/dev.log';
        $args['path'] = '/home/reinaldo/htdocs/www/planillas.corpglez/app/logs/dev.log';
        $args['type'] = 'monolog_default';
        $loger = new LogFile($args);
        $viewer->addLog($loger);

        $args['name'] = 'Monolog-Default-Prod';
        $args['path'] = '/home/reinaldo/htdocs/www/planillas.corpglez/app/logs/prod.log';
        $args['type'] = 'monolog_default';
        $loger = new LogFile($args);
        $viewer->addLog($loger);

        $args = array();
        $args['name'] = 'Apache-Error';
        $args['path'] = '/home/reinaldo/htdocs/logs/apache2/planillas.corpglez/error.log';
        $args['type'] = 'apache_error';
        $loger = new LogFile($args);
        $viewer->addLog($loger);

        $args['name'] = 'Apache-Access';
        $args['path'] = '/home/reinaldo/htdocs/logs/apache2/planillas.corpglez/access.log';
        $args['type'] = 'apache_access';
        $loger = new LogFile($args);
        $viewer->addLog($loger);



        $args = array();
        $args['name'] = 'Apache-Error-Global';
        $args['path'] = '/var/log/apache2/error.log';
        $args['type'] = 'apache_error';
        $loger = new LogFile($args);
        $viewer->addLog($loger);

        $args['name'] = 'Apache-Access-Global';
        $args['path'] = '/var/log/apache2/access.log';
        $args['type'] = 'apache_access';
        $loger = new LogFile($args);
        $viewer->addLog($loger);

        $args['name'] = 'Syslog';
        $args['path'] = '/home/reinaldo/htdocs/www/issuetracker/var/logs/system/syslog';
        $args['type'] = 'syslog';
        $loger = new LogFile($args);
        $viewer->addLog($loger);



        if($viewer === null || !$viewer->logExists($logSlug)) {
        }

        if ($logSlug=='NA') {
           // $logSlug = $viewer->getFirstLog()->getSlug();
            $logSlug = 'syslog';
        }

        $paginator = $this->get('knp_paginator');
        $page = $request->get('page', 1);
        $limit = 200;

        //dump($logSlug); die;
        $loger = $viewer->getLog($logSlug)->load( $page, $limit);

        $linesPaginated = $paginator->paginate(
            $loger->getLines(),
            $page,
            $limit
        );

        $loger->setLines($linesPaginated);

        $minLogLevel = $request->query->get('m');
        $currentLogger = $request->query->get('l');

        return $this->render( 'LogAuditBundle::log.html.twig', array(
            'current_log_slug' => $logSlug,
            'log' => $loger,
            'logLevels' => Logger::getLevels(),
            'min_log_level' => (in_array($minLogLevel, Logger::getLevels()) ? $minLogLevel : 100),
            'loggers' => $loger->getLoggers(),
            'current_logger' => $currentLogger,
            'app' => $app,
            'logs' => $viewer->getLogs()
        ));
    }

}
