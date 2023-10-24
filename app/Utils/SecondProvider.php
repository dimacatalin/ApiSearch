<?php

namespace App\Utils;

use Illuminate\Support\Facades\Http;

class SecondProvider extends Provider
{
    protected function getUrl()
    {
        return '/mock/provider2/email?linkedInProfileUrl=' . $this->linkedin;
    }

    protected function parseResponse($body)
    {
        return $body;
    }
}
