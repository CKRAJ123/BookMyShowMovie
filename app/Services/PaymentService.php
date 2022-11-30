<?php

namespace App\services;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class PaymentService implements PaymentServiceInterface
{


      public function doMoviePayment()
      {


        $client = new \GuzzleHttp\Client();
        // dd($client);
       
       
       // dd($id);
        try{
            // $id = Crypt::decrypt($config);  
            $id = config('secretKey.access_key');
           // dd($id);
        }
        catch(Illuminate\Contracts\Encryption\DecryptException $r){
            
            echo $r;
        }
       
        // dd($id);
        $secret = config('secretKey.secret_key');
      //  $secret = Crypt::decrypt($secretConfig);
        
       // dd($secret);
        $bytes = random_bytes(20);
       // dump("hoiiii");
        $response = $client->request('POST', 'https://sandbox-icp-api.bankopen.co/api/payment_token', [
            'body' => json_encode([
                "amount" => 10 ,
                "contact_number" => "7567662274",
                "email_id" => "ck90454@gmail.com" ,
                "currency" => "INR",
                "mtx" => bin2hex($bytes)
               
            ]),
        'headers' => [
            'Authorization' => 'Bearer '.$id.':'.$secret,
            'accept' => 'application/json',
            'content-type' => 'application/json',
        ],
        ]);
       // dump("chand");
       // dd($response);
     
        $responseData = json_decode($response->getBody()->getContents());
        //dd($responseData);
        //dd($responseData->id);
        return $responseData->id;

      }
}