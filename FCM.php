<?php

class FCM
{
    protected $fcmKey;

    function __construct()
    {
        $this->setFcmKey();
    }

    function setFcmKey(){
        $this->fcmKey =  ''; # set Your FireBase Notification Key here 
    }

    function getFcmKey(){
        return $this->fcmKey;
    }

    # @token = array
    # @title = string
    # @body = string
    # @data = array 
    function send_notification ($tokens  , $title  , $body  , $data = []  ){

        $url = 'https://fcm.googleapis.com/fcm/send';
        $fields = array(
            'registration_ids' =>$tokens,
            'notification' => ["title" => $title , "body" => $body ] ,
        );
        if(count($data) > 0 ){
            $fields['data'] =  $data;
        }
        $headers = array(
            'Authorization' => ['key=' . $this->getFcmKey() ],
            'Content-Type'=> 'application/json'
        );
        try{
            $client = new GuzzleHttp\Client();
            $req = $client->post($url , ['headers' => $headers  , 'json' => $fields ]  );
            $result = $req->getBody()->getContents();
            $decodedResult = json_decode($result);
            $data = array(
                'multicast_id' => $decodedResult->multicast_id,
                'success' => $decodedResult->success,
                'failure' => $decodedResult->failure,
                'canonical_ids' => $decodedResult->canonical_ids,
                'response_code' => $req->getStatusCode(),
                'results' => $decodedResult->results,
            );
            return $data;
        }catch (Exception $e ){
            return $e;
        }
    }



}
