<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ContestParticipant;
use AppBundle\Entity\Participant;
use AppBundle\Entity\Photo;
use AppBundle\Entity\User;
use AppBundle\Services\FacebookService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class ParticipateController extends Controller
{
    /**
     * @Route("/participate", name="participate_index")
     */
    public function participateAction(Request $request)
    {
        $session = $request->getSession();
        $doctrine = $this->getDoctrine();

        $fb = new FacebookService($this->container->getParameter('appId'), $this->container->getParameter('appSecret'));
        $admin = $fb->checkIfUserAdmin($session);

        $app = $fb->getApp();
        $app->setDefaultAccessToken($session->get('accessToken'));

        $albums = $app->get('/me?fields=albums{name,picture}')->getGraphAlbum();
        foreach($albums as $index => $album) {
            if ($index == 'albums') {
                $albums = $album;
            }
        }
        $form = $this->createFormBuilder()
            ->add('title', 'text', ['label' => 'Nom de l\'album'])
            ->add('photo', FileType::class, ['label' => 'Photo'])
            ->add('submit', ButtonType::class, array(
                'attr' => array('class' => 'btn btn-secondary save'),
                'label' => 'Participer'
            ))
            ->getForm();
        $form->handleRequest($request);

        return $this->render('default/participate.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'controller' => 'participate',
            'admin' => $admin,
            'form' => $form->createView(),
            'albums' => $albums
        ]);
    }

    /**
     * @Route("/participate/{albumId}", name="participate_album")
     */
    public function selectPhotoAction(Request $request, $albumId)
    {
        $session = $request->getSession();
        $doctrine = $this->getDoctrine();

        $fb = new FacebookService($this->container->getParameter('appId'), $this->container->getParameter('appSecret'));
        $admin = $fb->checkIfUserAdmin($session);

        $app = $fb->getApp();
        $app->setDefaultAccessToken($session->get('accessToken'));

        $album = $app->get('/'.$albumId.'?fields=name,photos{picture}')->getGraphAlbum();

        return $this->render('default/participate_photos.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'controller' => 'participate',
            'admin' => $admin,
            'album' => $album
        ]);
    }

    /**
     * @Route("/participate/{albumId}/{photoId}", name="participate_photo_facebook")
     */
    public function postPhotoAction(Request $request, $albumId, $photoId)
    {
        $session = $request->getSession();
        $doctrine = $this->getDoctrine();

        $user = $doctrine
        ->getRepository('AppBundle:User')->find($session->get('user')->getId());
        if (!$user) {
            throw new \Exception('Une erreur est survenue. Veuillez rechargez la page.');
        }

        $fb = new FacebookService($this->container->getParameter('appId'), $this->container->getParameter('appSecret'));
        $app = $fb->getApp();
        $app->setDefaultAccessToken($session->get('accessToken'));
        $admin = $fb->checkIfUserAdmin($session);

        $contest = $doctrine->getRepository('AppBundle:Contest')->findOneByStatus(1);

        $participant = $doctrine->getRepository('AppBundle:Participant')->findOneByUser($user);
        if (!$participant) {
            $participant = new Participant();
            $participant->setUser($user);
            $participant->setStatus(1);
            $participant->setDateAdd(new \DateTime());
            $doctrine->getEntityManager()->persist($participant);
        }

        $contestParticipant = $doctrine->getRepository('AppBundle:ContestParticipant')->findOneBy(['participant' => $participant->getId(), 'contest' => $contest->getId()]);
        if ($contestParticipant) {
            $session->getFlashBag()->add('error', 'Votre participation n\'a pas été prise en compte. Vous avez déjà participé à ce concours');
            return $this->render('default/home.html.twig', [
                'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
                'controller' => 'participate',
                'admin' => $admin,
                'contest' => $contest
            ]);
        } else {
            $contestParticipant = new ContestParticipant();
            $contestParticipant->setContest($contest);
            $contestParticipant->setParticipant($participant);
            $contestParticipant->setDateInscritpion(new \DateTime());
        }

        $facebookPhoto = $app->get('/'.$photoId.'?fields=picture')->getGraphAlbum();
        $photo = $doctrine->getRepository('AppBundle:Photo')->findOneBy(['facebookId' => $facebookPhoto->getId()]);
        $new_photo = false;

        if (!$photo) {
            $new_photo = true;
            $photo = new Photo();
            $photo->setFacebookId($facebookPhoto->getId());
            $photo->setStatus(1);
            $photo->setDateAdd(new \DateTime());
        }
        if (!$new_photo) {
            $photo->setDateUpdate(new \DateTime());
        }
        $photo->setLink($facebookPhoto['picture']);
        $doctrine->getEntityManager()->persist($photo);

        $contestParticipant->setPhoto($photo);
        $doctrine->getEntityManager()->persist($contestParticipant);

        $doctrine->getEntityManager()->flush();

        $session->getFlashBag()->add('success', 'Votre inscription a été prise en compte');


        return $this->render('default/home.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'controller' => 'participate',
            'admin' => $admin,
            'contest' => $contest,
            'photo' => $photo
        ]);
    }

    /**
     * @Route("/participate/import", name="participate_photo_desktop")
     */
    public function importPhotoAction(Request $request)
    {
        $form = $this->createForm('text');
        $form->handleRequest($request);
        dump($form);
        die();
        $session = $request->getSession();
        $doctrine = $this->getDoctrine();
        $user = $doctrine
            ->getRepository('AppBundle:User')->find($session->get('user')->getId());
        if (!$user) {
            throw new \Exception('Une erreur est survenue. Veuillez rechargez la page.');
        }

        $fb = new FacebookService($this->container->getParameter('appId'), $this->container->getParameter('appSecret'));
        $app = $fb->getApp();
        $app->setDefaultAccessToken($session->get('accessToken'));
        $admin = $fb->checkIfUserAdmin($session);

        $contest = $doctrine->getRepository('AppBundle:Contest')->findOneByStatus(1);

        $participant = $doctrine->getRepository('AppBundle:Participant')->findOneByUser($user);
        if (!$participant) {
            $participant = new Participant();
            $participant->setUser($user);
            $participant->setStatus(1);
            $participant->setDateAdd(new \DateTime());
            $doctrine->getEntityManager()->persist($participant);
        }

        $contestParticipant = $doctrine->getRepository('AppBundle:ContestParticipant')->findOneBy(['participant' => $participant->getId(), 'contest' => $contest->getId()]);
        if ($contestParticipant) {
            $session->getFlashBag()->add('error', 'Votre participation n\'a pas été prise en compte. Vous avez déjà participé à ce concours');
            return $this->render('default/home.html.twig', [
                'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
                'controller' => 'participate',
                'admin' => $admin,
                'contest' => $contest
            ]);
        } else {
            $contestParticipant = new ContestParticipant();
            $contestParticipant->setContest($contest);
            $contestParticipant->setParticipant($participant);
            $contestParticipant->setDateInscritpion(new \DateTime());
        }

        $facebookPhoto = $app->get('/'.$photoId.'?fields=picture')->getGraphAlbum();
        $photo = $doctrine->getRepository('AppBundle:Photo')->findOneBy(['facebookId' => $facebookPhoto->getId()]);
        $new_photo = false;

        if (!$photo) {
            $new_photo = true;
            $photo = new Photo();
            $photo->setFacebookId($facebookPhoto->getId());
            $photo->setStatus(1);
            $photo->setDateAdd(new \DateTime());
        }
        if (!$new_photo) {
            $photo->setDateUpdate(new \DateTime());
        }
        $photo->setLink($facebookPhoto['picture']);
        $doctrine->getEntityManager()->persist($photo);

        $contestParticipant->setPhoto($photo);
        $doctrine->getEntityManager()->persist($contestParticipant);

        $doctrine->getEntityManager()->flush();

        $session->getFlashBag()->add('success', 'Votre inscription a été prise en compte');


        return $this->render('default/home.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'controller' => 'participate',
            'admin' => $admin,
            'contest' => $contest,
            'photo' => $photo
        ]);
    }
}