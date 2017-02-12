<?php

namespace AppBundle\Controller;

use AppBundle\Entity\ContestParticipant;
use AppBundle\Entity\Participant;
use AppBundle\Entity\Photo;
use AppBundle\Entity\User;
use AppBundle\Services\FacebookService;
use Ivory\CKEditorBundle\Exception\Exception;
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

        $fb = $this->container->get('facebook_service');
        $admin = $fb->checkIfUserAdmin($session);

        $app = $fb->getApp();
        $app->setDefaultAccessToken($session->get('accessToken'));

        $albums = $app->get('/me?fields=albums{name, cover_photo{images}}')->getGraphAlbum();
        foreach($albums as $index => $album) {
            if ($index == 'albums') {
                $albums = $album;
            }
        }

        $formCreateAlbum = $this->createFormBuilder()
            ->add('title', 'text', ['required' => true, 'label' => 'Nom de l\'album'])
            ->add('description', 'text', ['required' => true, 'label' => 'Description de l\'album'])
            ->add('photo', FileType::class, ['required' => true, 'label' => 'Photo'])
            ->getForm();
        $formCreateAlbum->handleRequest($request);

        if ($formCreateAlbum->isValid()) {
            $create_album = null;
            if (isset( $formCreateAlbum->getViewData()['description']) && $formCreateAlbum->getViewData()['title']) {
                $album_details = array(
                    'message'=> $formCreateAlbum->getViewData()['description'],
                    'name' => $formCreateAlbum->getViewData()['title']
                );
                $create_album = $app->post('/me/albums', $album_details);
            } else {

            }
            $myFile = $app->fileToUpload($formCreateAlbum->getViewData()['photo']->getPathname());
            if (!$myFile) {
                $session->getFlashBag()->add('error', 'Une erreur est survenue. Si le problème persiste, veuillez contacter un administrateur.');
                return $this->render('default/participate.html.twig', [
                    'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
                    'controller' => 'participate',
                    'admin' => $admin,
                    'form' => $formCreateAlbum->createView(),
                    'albums' => $albums
                ]);
            }
            $photo_details = array(
              'source' => $myFile
            );
            if ($create_album){
                $publish_photo = $app->post('/'.$create_album->getGraphAlbum()['id'].'/photos', $photo_details);
            }

            if ($publish_photo) {
                $user = $doctrine
                    ->getRepository('AppBundle:User')->find($session->get('user')->getId());
                if (!$user) {
                    throw new \Exception('Une erreur est survenue. Veuillez rechargez la page.');
                }

                $fb = $this->container->get('facebook_service');
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
                    $contestParticipants  = $doctrine->getRepository('AppBundle:ContestParticipant')->getTenRandomContestParticipants($contest);
                    $session->getFlashBag()->add('error', 'Votre participation n\'a pas été prise en compte. Vous avez déjà participé à ce concours');

                    return $this->render('default/home.html.twig', [
                        'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
                        'controller' => 'participate',
                        'admin' => $admin,
                        'contestParticipants' => $contestParticipants,
                        'contest' => $contest
                    ]);
                } else {
                    $contestParticipant = new ContestParticipant();
                    $contestParticipant->setContest($contest);
                    $contestParticipant->setParticipant($participant);
                    $contestParticipant->setDateInscritpion(new \DateTime());
                }

                $facebookPhoto = $app->get('/'.$publish_photo->getGraphAlbum()['id'].'?fields=images')->getGraphAlbum();
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
                $photo->setLink($facebookPhoto['images'][0]['source']);
                $doctrine->getEntityManager()->persist($photo);

                $contestParticipant->setPhoto($photo);
                $contestParticipant->setVotes(0);
                $doctrine->getEntityManager()->persist($contestParticipant);

                $doctrine->getEntityManager()->flush();

                $album_details = array(
                    'message'=> 'Je viens de participer au concours de Pardon Maman. Allez voir ma photo !',
                    'link' => $this->generateUrl('photo_display', array('facebookId' =>$photo->getFacebookId()), 0),
                    'object_attachment' => $photo->getFacebookId()
                );

                $post_message = $app->post('/me/feed', $album_details);

                $session->getFlashBag()->add('success', 'Votre inscription a été prise en compte');


                return $this->redirectToRoute('photo_display', array('facebookId' =>$photo->getFacebookId()));
            } else {
                $session->getFlashBag()->add('error', 'Une erreur est survenue. Si le problème persiste, veuillez contacter un administrateur.');
                return $this->render('default/participate.html.twig', [
                    'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
                    'controller' => 'participate',
                    'admin' => $admin,
                    'form' => $formCreateAlbum->createView(),
                    'albums' => $albums
                ]);
            }
        }

        return $this->render('default/participate.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'controller' => 'participate',
            'admin' => $admin,
            'form' => $formCreateAlbum->createView(),
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

        $fb = $this->container->get('facebook_service');
        $admin = $fb->checkIfUserAdmin($session);

        $app = $fb->getApp();
        $app->setDefaultAccessToken($session->get('accessToken'));
        $album = $form = null;
        if ($request->get('uploadPhoto')) {
            $form = $this->createFormBuilder()
            ->add('photo', FileType::class, ['required' => true, 'label' => 'Photo'])->getForm();
            $form->handleRequest($request);
            if ($form->isValid()) {
                $albumId = $request->attributes->get('albumId');
                $myFile = $app->fileToUpload($form->getViewData()['photo']->getPathname());
                if (!$myFile) {
                    $session->getFlashBag()->add('error', 'Une erreur est survenue. Si le problème persiste, veuillez contacter un administrateur.');
                    return $this->render('default/participate_photos.html.twig', [
                        'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
                        'controller' => 'participate',
                        'admin' => $admin,
                        'form' => $form->createView(),
                    ]);
                }
                $photo_details = array(
                    'source' => $myFile
                );
                $publish_photo = $app->post('/'.$albumId.'/photos', $photo_details);
                if (!$publish_photo) {
                    $session->getFlashBag()->add('error', 'Une erreur est survenue. Si le problème persiste, veuillez contacter un administrateur.');
                    return $this->render('default/participate_photos.html.twig', [
                        'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
                        'controller' => 'participate',
                        'admin' => $admin,
                        'form' => $form->createView(),
                    ]);
                }
                return $this->redirectToRoute('participate_photo_facebook', array('albumId' => $albumId, 'photoId' =>$publish_photo->getGraphAlbum()['id'] ));
            }
        } else {
            $album = $app->get('/'.$albumId.'?fields=name,photos{images}')->getGraphAlbum();
        }

        return $this->render('default/participate_photos.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
            'controller' => 'participate',
            'admin' => $admin,
            'form' => $form ? $form->createView() : $form,
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

        $fb = $this->container->get('facebook_service');
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
            $contestParticipants  = $doctrine->getRepository('AppBundle:ContestParticipant')->getTenRandomContestParticipants($contest);
            return $this->render('default/home.html.twig', [
                'base_dir' => realpath($this->getParameter('kernel.root_dir').'/..').DIRECTORY_SEPARATOR,
                'controller' => 'participate',
                'admin' => $admin,
                'contestParticipants' => $contestParticipants,
                'contest' => $contest
            ]);
        } else {
            $contestParticipant = new ContestParticipant();
            $contestParticipant->setContest($contest);
            $contestParticipant->setParticipant($participant);
            $contestParticipant->setDateInscritpion(new \DateTime());
        }

        $facebookPhoto = $app->get('/'.$photoId.'?fields=images')->getGraphAlbum();
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
        $photo->setLink($facebookPhoto['images'][0]['source']);
        $doctrine->getEntityManager()->persist($photo);

        $contestParticipant->setPhoto($photo);
        $contestParticipant->setVotes(0);
        $doctrine->getEntityManager()->persist($contestParticipant);

        $doctrine->getEntityManager()->flush();

        $album_details = array(
            'message'=> 'Je viens de participer au concours de Pardon Maman. Allez voir ma photo !',
            'link' => $this->generateUrl('photo_display', array('facebookId' =>$photo->getFacebookId()), 0),
            'object_attachment' => $photo->getFacebookId()
        );

        $post_message = $app->post('/me/feed', $album_details);

        $session->getFlashBag()->add('success', 'Votre inscription a été prise en compte');


        return $this->redirectToRoute('photo_display', array('facebookId' =>$photo->getFacebookId()));

    }

}