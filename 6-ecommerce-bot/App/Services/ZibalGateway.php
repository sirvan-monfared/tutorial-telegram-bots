<?php

namespace App\Services;

use App\Exceptions\GatewayException;

class ZibalGateway
{
    private string $merchant_id;
    private string $callback_url;
    private string $request_url;
    private string $gateway_url;
    private string $verify_url;
    private int $track_id;
    private int $support_id;
    private object $verify_response;

    public function __construct()
    {
        $this->merchant_id = env('ZIBAL_MERCHANT_ID');
        $this->callback_url = url('callback.php');

        $this->request_url = 'https://gateway.zibal.ir/v1/request';
        $this->gateway_url = 'https://gateway.zibal.ir/start/';
        $this->verify_url = 'https://gateway.zibal.ir/v1/verify';
    }

    /**
     * @throws GatewayException
     */
    public function request(int $amount, mixed $order_id): static
    {
        $params = [
            'merchant' => $this->merchant_id,
            'amount' => $amount,
            'callbackUrl' => $this->callback_url,
            'orderId' => $order_id
        ];

        $response = $this->post($this->request_url, $params);

        if ($response->result !== 100) {
            throw new GatewayException(
                $this->message($response->result)
            );
        }

        $this->track_id = $response->trackId;

        return $this;
    }

    /**
     * @throws GatewayException
     */
    public function verify(int $track_id)
    {
        $params = [
            'merchant' => $this->merchant_id,
            'trackId' => $track_id
        ];

        $response = $this->post($this->verify_url, $params);

        if (intval($response->result) !== 100) {
            throw new GatewayException(
                $this->message($response->result)
            );
        }

        $this->support_id = $response->refNumber;
        $this->verify_response = $response;

        return $this;
    }

    public function paymentUrl(): string
    {
        return $this->gateway_url . $this->track_id;
    }

    public function trackId(): int
    {
        return $this->track_id;
    }

    public function supportId(): int
    {
        return $this->support_id;
    }

    private function message(int $code):string
    {
        return match($code) {
            102 => "درگاه یافت نشد",
            103 => "درگاه غیرفعال",
            104 => "درگاه نامعتبر",
            201 => "تراکنش قبلا تایید شده است",
            202 => "پرداخت ناموفق",
            203 => "سفارش نامعتبر است"
        };
    }

    protected function post(string $url, array $params)
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS => json_encode($params),
          CURLOPT_HTTPHEADER => [
            'Content-Type: application/json'
          ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response);
    }


}