<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Services\FacebookService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class FacebookController extends Controller
{
    /**
     * @Route("/connect", name="app_connect")
     */
    public function indexAction(Request $request)
    {
        $connection = false;
        $session = $request->getSession();
        if (!$session->get('userId') || !$session->get('accessToken')) {
            $connection = true;
        }
        if ($request->get('userId') && $request->get('accessToken')) {
            if ($session->get('userId') != $request->get('userId')) {
                $session->set('userId', $request->get('userId'));
            }
            if ($session->get('accessToken') != $request->get('accessToken')){
                $session->set('accessToken', $request->get('accessToken'));
            }
        }
        $fb = new FacebookService('1504707966210156', '68f994bc31e69588727c593005f770c8');
        $app = $fb->getApp();
        if ($session->get('accessToken')) {
            $app->setDefaultAccessToken($session->get('accessToken'));
            $response = $app->get('/me?fields=id,first_name,last_name,email,birthday,location{location}');
            $data = $response->getGraphUser();
            $em = $this->getDoctrine()->getEntityManager();
            $user = $this->getDoctrine()->getRepository('AppBundle:User')->findOneBy(['facebookId'=> $data->getId()]);
            if (!$user) {
                $user = new User();
                $user->setStatus(1);
            }
            $user->setFacebookId($data->getId());
            $user->setFirstname($data->getFirstName());
            $user->setLastname($data->getLastName());
            $user->setCity($data->getLocation()->getLocation()->getCity());
            $user->setCountry($data->getLocation()->getLocation()->getCountry());
            $user->setBirthday($data->getBirthday());
            $user->setEmail($data->getEmail());
            $em->persist($user);
            $em->flush();
            $session->set('user', $user);
        }
        if ($connection == true) {
            $response = new Response('connection');
        } else {
            $response = new Response();
        }

        return $response;
    }
}
