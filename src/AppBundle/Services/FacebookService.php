<?php

namespace AppBundle\Services;

use Doctrine\Common\Collections\ArrayCollection;
use Facebook\Facebook;
use Symfony\Component\DependencyInjection\Container;

class FacebookService {

    protected $appId;
    protected $appSecret;
    protected $app;
    protected $container;

    public function __construct($app_id, $app_secret, Container $container){
        $this->appId = $app_id;
        $this->container = $container;
        $this->appSecret = $app_secret;
        $fb = new Facebook([
            'app_id' => $this->appId,
            'app_secret' => $this->appSecret,
            'default_graph_version' => 'v2.8',
            //'default_access_token' => '{access-token}', // optional
        ]);
        $this->app = $fb;
    }


    public function checkIfUserAdmin($session){

        $app = $this->app->getApp();
        $roles = $this->app->get('/app/roles', $app->getAccessToken());
        $roles = json_decode($roles->getBody());
        $admin = false;
        foreach ($roles->data as $role) {
            if ($role->user == $session->get('userId') && $role->role == "administrators") {
                $admin = true;
            }
        }

        return $admin;
    }

    public function getApp()
    {
        return $this->app;
    }

    public function getAdmins()
    {
        $app = $this->app->getApp();
        $roles = $this->app->get('/app/roles', $app->getAccessToken());
        $roles = json_decode($roles->getBody());
        $admins = new ArrayCollection();
        foreach ($roles->data as $role) {
            $admin = $this->container->get('doctrine')->getRepository('AppBundle:User')->findOneBy(['facebookId' => $role->user]);
            if ($admin) {
                $admins->add($admin);
            }
        }
        return $admins;
    }
}