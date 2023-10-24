<?php

namespace App\Utils;

use Illuminate\Support\Facades\Http;

abstract class Provider
{
    public function __construct(public $name, public $company, public $linkedin)
    {
    }

    public function makeRequest()
    {
        try {
            $response = Http::withToken(env('API_TOKEN'))->get(env('BASE_URL') . $this->getUrl());
            if ($response->successful()) {
                return $this->parseResponse(json_decode($response->body()));
            } else {
                print_r("Response code: {$response->status()} \n");
            }
        } catch (\Exception $e) {
            print_r("Error encountered while making API request.\n {$e->getTraceAsString()}");
        }
    }

    protected abstract function getUrl();

    protected abstract function parseResponse($body);
}
