<?php

namespace App\Traits;

trait ApisService{
    
    public $baseUri;

    public $secret;

    public function __construct()
    {
        $this->baseUri = config('services.apis.base_uri');
        $this->secret = config('services.apis.secret');
    }

}