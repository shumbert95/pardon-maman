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

        $fb = $this->container->get('facebook_service');
        $admin = $fb->checkIfUserAdmin($session);

        $contest = $doctrine->getRepository('AppBundle:Contest')->findOneByStatus(1);
        $winner = $doctrine->getRepository('AppBundle:ContestParticipant')->getContestWinner($contest);
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

        $fb = $this->container->get('facebook_service');
        $admin = $fb->checkIfUserAdmin($session);

        $contest = $doctrine->getRepository('AppBundle:Contest')->findOneBy(['status' => 1]);
        $type = $request->get('type') ? $request->get('type') : '';

        if ($type == 'dateAsc') {
            $contestParticipants = $doctrine->getRepository('AppBundle:ContestParticipant')->findContestParticipantsDateAsc($contest);
        } elseif ($type == 'dateDesc') {
            $contestParticipants = $doctrine->getRepository('AppBundle:ContestParticipant')->findContestParticipantsDateDesc($contest);
        } elseif ($type == 'voteAsc') {
            $contestParticipants = $doctrine->getRepository('AppBundle:ContestParticipant')->findContestParticipantsVoteAsc($contest);
        } elseif ($type == 'voteDesc') {
            $contestParticipants = $doctrine->getRepository('AppBundle:ContestParticipant')->findContestParticipantsVoteDesc($contest);
        } else {
            $contestParticipants = $doctrine->getRepository('AppBundle:ContestParticipant')->getRandomContestParticipants();
        }

        return $this->render('default/gallery.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir') . '/..') . DIRECTORY_SEPARATOR,
            'controller' => 'gallery',
            'admin' => $admin,
            'contest' => $contest,
            'contestParticipants' => $contestParticipants,
            'type' => $type
        ]);
    }

    /**
     * @Route("/rules", name="rules")
     */
    public function rulesAction(Request $request)
    {
        $session = $request->getSession();
        $doctrine = $this->getDoctrine();

        $fb = $this->container->get('facebook_service');
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

        $fb = $this->container->get('facebook_service');
        $admin = $fb->checkIfUserAdmin($session);

        $photo = $doctrine->getRepository('AppBundle:Photo')->findOneBy(['facebookId' => $facebookId]);
        $routeURL = $request->getRequestUri();
       if (!$photo) {
           return $this->render('default/notfound.html.twig');
       } else {
           return $this->render('default/photo.html.twig', [
               'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
               'controller' => 'rules',
               'admin' => $admin,
               'url_partage' => $routeURL,
               'photo' => $photo,
           ]);
       }
    }
}
