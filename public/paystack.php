<?php

      $paystack_initialize_url = 'https://api.paystack.co/transaction/initialize';

        
        $secret_key = env('PAY_STACK_SECRET_KEY');
        $headers = [
            'Authorization:Bearer '.$secret_key,
            'Content-Type:application/json'
            ];

        
        $request_for = 'add-money-to-wallet';

        $current_timestamp = 'test';

        $reference = "125ad";


        $request_for  ="wallet";
        $query = [
            'email'=> "farukh@gmail.com",
            'amount'=>100,
            'reference'=>$current_timestamp.'-----'.$reference.'-----'.$request_for

            ];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $paystack_initialize_url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($query));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($ch);

echo $result;
die();
        if ($result) {
            $result = json_decode($result);  

            return response()->json($result);
        }
        