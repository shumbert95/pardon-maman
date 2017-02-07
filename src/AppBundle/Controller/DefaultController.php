<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Services\FacebookService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $session = $request->getSession();
        $doctrine = $this->getDoctrine();

        $fb = new FacebookService($this->container->getParameter('appId'), $this->container->getParameter('appSecret'));
        $admin = $fb->checkIfUserAdmin($session);

        $contest = $doctrine->getRepository('AppBundle:Contest')->findOneByStatus(1);
        if (!$contest) {

        }

        return $this->render('default/home.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'controller' => 'home',
            'contest' => $contest,
            'admin' => $admin
        ]);
    }

    /**
     * @Route("/gallery", name="gallery")
     */
    public function galleryAction(Request $request)
    {
        $session = $request->getSession();
        $doctrine = $this->getDoctrine();

        $fb = new FacebookService($this->container->getParameter('appId'), $this->container->getParameter('appSecret'));
        $admin = $fb->checkIfUserAdmin($session);

        $contest = $doctrine->getRepository('AppBundle:Contest')->findOneBy(['status' => 1]);
        $contestParticipants = $contest->getContestParticipants();

        return $this->render('default/gallery.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'controller' => 'gallery',
            'admin' => $admin,
            'contest' => $contest,
            'contestParticipants' => $contestParticipants
        ]);
    }

    /**
     * @Route("/rules", name="rules")
     */
    public function rulesAction(Request $request)
    {
        $session = $request->getSession();
        $doctrine = $this->getDoctrine();

        $fb = new FacebookService($this->container->getParameter('appId'), $this->container->getParameter('appSecret'));
        $admin = $fb->checkIfUserAdmin($session);

        $contest = $doctrine->getRepository('AppBundle:Contest')->findOneByStatus(1);
        if (!$contest) {

        }

        $rules = $contest->getRules();

        return $this->render('default/rules.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'controller' => 'rules',
            'admin' => $admin,
            'contest' => $contest,
            'rules' => $rules,
        ]);
    }

    /**
     * @Route("/photo/{facebookId}", name="photo_display")
     */
    public function photoAction(Request $request, $facebookId)
    {
        $session = $request->getSession();
        $doctrine = $this->getDoctrine();

        $fb = new FacebookService($this->container->getParameter('appId'), $this->container->getParameter('appSecret'));
        $admin = $fb->checkIfUserAdmin($session);

        $photo = $doctrine->getRepository('AppBundle:Photo')->findOneBy(['facebookId' => $facebookId]);

       if (!$photo) {
           return $this->render('default/notfound.html.twig');
       } else {
           return $this->render('default/photo.html.twig', [
               'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
               'controller' => 'rules',
               'admin' => $admin,
               'photo' => $photo,
           ]);
       }
    }
}
