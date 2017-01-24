<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConnectController extends Controller
{
    /**
     * @Route("/connect", name="app_connect")
     */
    public function indexAction(Request $request)
    {
        if ($request->get('userId') && $request->get('accessToken')) {
            $session = $request->getSession();
            if ($session->get('userId') != $request->get('userId'))
                $session->set('userId', $request->get('userId'));
            if ($session->get('accessToken') != $request->get('accessToken'))
                $session->set('accessToken', $request->get('accessToken'));
        }
        $response = new Response();

        return $response;
    }
}
