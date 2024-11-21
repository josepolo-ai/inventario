<?php

namespace App\Services;

use App\Traits\ConsumesExternalServices;
use App\Traits\ApisService;

class DniService
{
    use ConsumesExternalServices, ApisService;

    public function getPersonByDni($dni)
    {
        $response = $this->performRequest('GET', "/v2/reniec/dni?numero={$dni}");
        return $response;
    }
}