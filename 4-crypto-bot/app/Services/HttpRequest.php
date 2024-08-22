<?php

namespace App\Services;

class HttpRequest
{
    protected array $config;

    protected $curl;

    public function __construct()
    {
        $this->config = [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET'
        ];

        $this->curl = curl_init();
    }

    public function cryptolist()
    {
        $this->config[CURLOPT_URL] = 'https://one-api.ir/DigitalCurrency/?token=' . env('ONE_API_TOKEN');

        return $this->get();
    }

    public function currencyList()
    {
        $this->config[CURLOPT_URL] = 'https://one-api.ir/price/?token=' . env('ONE_API_TOKEN') . '&action=tgju';

        return $this->get();
    }

    protected function get()
    {

        curl_setopt_array($this->curl, $this->config);

        $response = curl_exec($this->curl);

        curl_close($this->curl);

        return (json_decode($response))->result;
    }
}
