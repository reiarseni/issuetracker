<?php

declare(strict_types=1);

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/uploader")
 */
class UploaderController extends Controller
{
    /**
     * @Route("/images_upload", name="images_upload")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     */
    public function uploadAction(Request $request)
    {
        $fileOrig = $request->files->get('file');
        $fileName = time().'-'.$fileOrig->getClientOriginalName();
        $filePath = '/home/rei/htdocs/www/issuetracker/web/upload';

        if (!file_exists($filePath) && !is_dir($filePath)) {
            mkdir($filePath);
        }

        $fileOrig->move($filePath, $fileName);

        $response = new JsonResponse(['location' => '/upload/'.$fileName]);
        $response->headers->set('Content-Type', 'text/html');

        return $response;
    }

    /**
     * @Route("/images_upload2", name="images_upload2")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     */
    public function upload2Action(Request $request)
    {
        $fileOrig = $request->files->get('image');
        $fileOrig = $fileOrig['file'];

        $fileName = time().'-'.$fileOrig->getClientOriginalName();
        $filePath = '/home/rei/htdocs/www/issuetracker/web/upload';

        if (!file_exists($filePath) && !is_dir($filePath)) {
            mkdir($filePath);
        }

        $fileOrig->move($filePath, $fileName);

        $response = new Response('/upload/'.$fileName);
        $response->headers->set('Content-Type', 'text/html');

        return $response;
    }

    /**
     * Creates a new Translate entity.
     *
     * @Route("/files_upload", name="files_upload")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     */
    public function filesUploadAction(Request $request)
    {
        $fileOrig = $request->files->get('tiny_inner_image');

        $fileName = time().'-'.$fileOrig->getClientOriginalName();
        $filePath = '/home/rei/htdocs/www/issuetracker/web/upload';

        if (!file_exists($filePath) && !is_dir($filePath)) {
            mkdir($filePath);
        }

        $fileOrig->move($filePath, $fileName);

        return new JsonResponse('/upload/'.$fileName);
    }

    /**
     * @Route("/imagetools_proxy", name="imagetools_proxy")
     * @Method({"GET", "POST"})
     *
     * @param Request $request
     */
    public function imagetoolsProxyAction(Request $request)
    {
        // We recommend to extend this script with authentication logic
        // so it can be used only by an authorized user
        $validMimeTypes = ['image/gif', 'image/jpeg', 'image/png'];

        $url = trim($request->get('url'));

        if (!$url) {
            return  new Response('HTTP/1.0 500 Url parameter missing or empty.', 500);
        }

        $content = file_get_contents($_GET['url']);
        $info = getimagesizefromstring($content);

        if (false === $info || false === \in_array($info['mime'], $validMimeTypes)) {
            return new Response("HTTP/1.0 500 Url doesn't seem to be a valid image.", 500);
        }

        $response = new Response($content);
        $response->headers->set('Content-Type', $info['mime']);

        return $response;
    }
}
