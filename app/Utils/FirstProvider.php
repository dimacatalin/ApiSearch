<?php

namespace App\Utils;

use Illuminate\Support\Facades\Http;

class FirstProvider extends Provider
{
    protected function getUrl()
    {
        return '/mock/provider1/email?name=' . $this->name . '&company=' . $this->company;
    }

    protected function parseResponse($body)
    {
        $emails = [];

        foreach ($body as $apiUser) {
            $emails[] = $apiUser->email;
        }

        return $emails;
    }
}
