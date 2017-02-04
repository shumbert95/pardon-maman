<?php

namespace AppBundle\Services;

use Facebook\Facebook;

class FacebookService {

    protected $appId;
    protected $appSecret;
    protected $app;

    public function __construct($app_id, $app_secret){
        $this->appId = $app_id;
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
            if ($role->user == $session->get('userId')) {
                $admin = true;
            }
        }

        return $admin;
    }

    public function getApp()
    {
        return $this->app;
    }
}