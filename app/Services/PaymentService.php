<?php

namespace App\services;

// use Illuminate\Contracts\Encryption\DecryptException;
// use Illuminate\Support\Facades\Crypt;

class PaymentService implements PaymentServiceInterface
{


      public function doMoviePayment($amount)
      {


            $bytes = random_bytes(20);

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', 'https://sandbox-icp-api.bankopen.co/api/payment_token', [
            'body' => json_encode([
                "amount" => $amount,
                "contact_number" => "8043234223",
                "email_id" => "krchandanagarwal@gmail.com",
                "currency" => "INR",
                "mtx" => bin2hex($bytes)
            ]),
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer c6b19ee0-284e-11ed-a4b4-91d56a37fb20:1c7903dfe788673d46d6a2fc898756ef229efb6b',
                'Content-Type' => 'application/json',
            ],
        ]);

        $responseData = json_decode($response->getBody()->getContents());
             return $responseData->id;
      }
}