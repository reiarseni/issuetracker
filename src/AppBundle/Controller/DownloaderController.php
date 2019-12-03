<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @Route("")
 */
class DownloaderController extends Controller
{
    /**
     * Chequear permisos.
     *
     * @Route("/upload/{filename}", name="upload")
     * @Method({"GET"})
     *
     * @param Request $request
     * @param mixed   $filename
     *
     * @return NotFoundHttpException|static
     */
    public function uploadAction(Request $request, $filename)
    {
        $dir = $this->getParameter('kernel.root_dir');

        $dir = $dir.'/../web/upload/';

        $filePath = /*'/home/reinaldo/htdocs/www/issuetracker/web/upload/'*/ $dir.$filename;

        // dump($filePath); die;

        if (file_exists($filePath) && !is_dir($filePath)) {
            return BinaryFileResponse::create($filePath);
        }

        return new NotFoundHttpException();

        /* $fileOrig->move($filePath, $fileName);

         $response = new JsonResponse(array('location' => '/upload/'. $fileName) );
         $response->headers->set('Content-Type', 'text/html');

         return $response;*/
    }
}
