<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Facebook;
class GalleryController extends Controller
{
    /**
     * @Route("/gallery", name="gallery")
     */
    public function indexAction(Request $request)
    {
        $facebook = new Facebook(array(
            'appId'  => '1504707966210156',
            'secret' => '68f994bc31e69588727c593005f770c8',
        ));

        var_dump($facebook);
        die();

        // replace this example code with whatever you need
        return $this->render('default/gallery.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'controller' => 'gallery',
        ]);
    }
}
