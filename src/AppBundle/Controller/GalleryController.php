<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
class GalleryController extends Controller
{
    /**
     * @Route("/gallery", name="gallery")
     */
    public function indexAction(Request $request)
    {
        $fb = new \Facebook\Facebook([
            'app_id' => $this->getParameter('appId'),
            'app_secret' => $this->getParameter('appSecret'),
            'default_graph_version' => 'v2.8',
            //'default_access_token' => '{access-token}', // optional
        ]);

        return $this->render('default/gallery.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'controller' => 'gallery',
        ]);
    }
}
