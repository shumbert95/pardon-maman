<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Services\FacebookService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class VoteController extends Controller
{
    /**
     * @Route("/vote/{contestParticipantId}", name="vote_photo")
     */
    public function votePhotoAction(Request $request, $contestParticipantId)
    {
        $session = $request->getSession();
        $doctrine = $this->getDoctrine();

        $fb = new FacebookService($this->container->getParameter('appId'), $this->container->getParameter('appSecret'));
        $admin = $fb->checkIfUserAdmin($session);

        $contestParticipant = $doctrine->getRepository('AppBundle:ContestParticipant')->find($contestParticipantId);
        $contestParticipant->increaseVotes();

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

        // replace this example code with whatever you need
        return $this->render('default/rules.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'controller' => 'rules',
            'admin' => $admin,
            'contest' => $contest,
            'rules' => $rules,
        ]);
    }
}
