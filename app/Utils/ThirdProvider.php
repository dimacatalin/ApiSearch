<?php

namespace App\Utils;

use Illuminate\Support\Facades\Http;

class ThirdProvider extends Provider
{
    protected function getUrl()
    {
        return '/mock/provider3/email?linkedInProfileUrl=' . $this->linkedin . '&company=' . $this->company;
    }

    protected function parseResponse($body)
    {
        $emails = [];

        foreach ($body as $object) {
            $emails[] = $object->Email;
        }

        return $emails;
    }
}
