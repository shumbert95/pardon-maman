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
        $em = $this->getDoctrine()->getEntityManager();

        $fb = $this->container->get('facebook_service');
        $admin = $fb->checkIfUserAdmin($session);

        $contestParticipant = $doctrine->getRepository('AppBundle:ContestParticipant')->find($contestParticipantId);
        $user = $doctrine->getRepository('AppBundle:User')->find($session->get('user')->getId());
        if ($contestParticipant->getVoters()->contains($user)) {
            $contestParticipant->decreaseVotes();
            $contestParticipant->removeVoter($user);
        } else {
            $contestParticipant->increaseVotes();
            $contestParticipant->addVoter($user);
            $session->getFlashBag()->add('success', 'Votre vote a été pris en compte');
        }

        $em->flush();

        $contest = $doctrine->getRepository('AppBundle:Contest')->findOneBy(['status' => 1]);
        $contestParticipants = $contest->getContestParticipants();


        return $this->redirectToRoute('gallery');
    }
}
