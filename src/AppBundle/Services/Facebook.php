<?php

namespace AppBundle\Services;

class Facebook {

    protected $appId;
    protected $appSecret;
    protected $app;

    public function __construct($app_id, $app_secret){
        $this->appId = $app_id;
        $this->appSecret = $app_secret;
        $fb = new \Facebook\Facebook([
            'app_id' => $this->appId,
            'app_secret' => $this->appSecret,
            'default_graph_version' => 'v2.8',
            //'default_access_token' => '{access-token}', // optional
        ]);
        $this->app = $fb;
    }


    public function checkIfUserAdmin(){
        return $this->app;
    }
}