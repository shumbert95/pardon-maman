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

        // replace this example code with whatever you need
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

        return $this->render('default/gallery.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'controller' => 'gallery',
            'admin' => $admin,
        ]);
    }

    /**
     * @Route("/participate", name="participate")
     */
    public function participateAction(Request $request)
    {
        $session = $request->getSession();
        $doctrine = $this->getDoctrine();

        $fb = new FacebookService($this->container->getParameter('appId'), $this->container->getParameter('appSecret'));
        $admin = $fb->checkIfUserAdmin($session);

        // replace this example code with whatever you need
        return $this->render('default/participate.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'controller' => 'participate',
            'admin' => $admin,
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

        // replace this example code with whatever you need
        return $this->render('default/rules.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'controller' => 'participate',
            'admin' => $admin,
            'contest' => $contest,
            'rules' => $rules,
        ]);
    }
}
