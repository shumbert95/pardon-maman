<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Services\FacebookService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;


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
        if (!$contest) {

        }

        $contestParticipants = $doctrine->getRepository('AppBundle:ContestParticipant')->getTenRandomContestParticipants($contest);

        return $this->render('default/home.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'controller' => 'home',
            'contest' => $contest,
            'contestParticipants' => $contestParticipants,
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
            $contestParticipants = $doctrine->getRepository('AppBundle:ContestParticipant')->getRandomContestParticipants($contest);
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
               'controller' => null,
               'admin' => $admin,
               'url_partage' => $routeURL,
               'photo' => $photo,
           ]);
       }
    }

    /**
     * @Route("/contest", name="contest_result")
     */
    public function resultAction(Request $request)
    {
        $session = $request->getSession();
        $doctrine = $this->getDoctrine();

        $fb = $this->container->get('facebook_service');
        $admin = $fb->checkIfUserAdmin($session);

        $contest = $doctrine->getRepository('AppBundle:Contest')->findOneByStatus(1);
        $contestParticipants = $doctrine->getRepository('AppBundle:ContestParticipant')->findContestParticipantsVoteDesc($contest);

        return $this->render('default/contest.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'controller' => 'contest_result',
            'admin' => $admin,
            'contest' => $contest,
            'contestParticipants' => $contestParticipants
        ]);
    }

    /**
     * @Route("/export/", name="contest_export")
     */
    public function exportContestParticipants(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $doctrine = $this->getDoctrine();


             $response = new StreamedResponse();

            $repository = $doctrine->getRepository('AppBundle:ContestParticipant');

            $response->setCallback(function() use ($repository) {
        $handle = fopen('php://output', 'w+');
                $doctrine = $this->getDoctrine();

                $contest = $doctrine->getRepository('AppBundle:Contest')->findOneBy(['status' => 1]);

        fputcsv($handle, ['Nom', 'Prénom', 'Email', 'Date de Naissance', 'Pays', 'Ville', 'Date première participation', 'Date de participation', 'Nbr de votes' ], ';');
                $results = $repository->findBy(['contest' => $contest->getId()]);
                foreach ($results as $cp) {
                    fputcsv(
                        $handle,
                        [$cp->getParticipant()->getUser()->getLastname(),
                        $cp->getParticipant()->getUser()->getFirstname(),
                        $cp->getParticipant()->getUser()->getEmail(),
                        $cp->getParticipant()->getUser()->getBirthday()->format('d/m/Y'),
                        $cp->getParticipant()->getUser()->getCountry(),
                        $cp->getParticipant()->getUser()->getCity(),
                        $cp->getParticipant()->getDateAdd()->format('d/m/Y'),
                        $cp->getDateInscription()->format('d/m/Y'),
                        $cp->getVotes(),
                        ],
                        ';'
                    );
                }

        fclose($handle);
    });

    $response->setStatusCode(200);
    $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
    $response->headers->set('Content-Disposition','attachment; filename="export-users.csv"');

    return $response;
        }
}
