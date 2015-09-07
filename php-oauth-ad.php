<?php

$consumer_key = "mykey";
$consumer_secret = "mysecret";
$api_url = "https://mysite.byappdirect.com/api/hostedCheckout/v1/transactions";
$http_method = "POST";
$returnUrl = "http://saralam.com";

$nonce_range = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";

$nonce = '';
for ($i = 0; $i < 15; ++$i) {
	$rind = floor((float)rand()/(float)getrandmax() * strlen($nonce_range));
        $nonce .= substr($nonce_range, $rind, 1);
}
$timestamp = time();

$version = "1.0";

$oauth =  new OAuth($consumer_key, $consumer_secret);
$oauth->setNonce($nonce);

$oauth->setTimestamp($timestamp);
$oauth->setversion($version);

$sign =  $oauth->generateSignature ( 'POST' , $api_url);


$oauth_header = 'Authorization: OAuth oauth_version=1.0, oauth_nonce='.$nonce.',oauth_timestamp='.$timestamp.',oauth_consumer_key=mykey, oauth_signature_method=HMAC-SHA1,oauth_signature='.$sign;

$ch = curl_init($api_url);

$to_postdata = array ("productId" => "37392",
                      "token" => '123446788-dgfgfgfg-uytt',
                       "type" => "PURCHASE",
                       "user" => array ("email" => 'testad123@test.com',
                                "firstName" => "Test",
                                "lastName" => "Test",
                        ),
                        "company" => array ("name" => "Saralam"),
                        "returnUrl" => $returnUrl);

$data_string = json_encode($to_postdata);

curl_setopt($ch, CURLOPT_POST ,true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS , $data_string);

curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 
	'Accept: application/json',
         $oauth_header
            )
      );

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION ,true);
curl_setopt($ch, CURLOPT_HEADER ,true);  // DO NOT RETURN HTTP HEADERS
curl_setopt($ch, CURLINFO_HEADER_OUT, true); // enable tracking
curl_setopt($ch, CURLOPT_FILETIME, true); // enable tracking
curl_setopt($ch, CURLOPT_VERBOSE, true);



$result = curl_exec($ch);
$info = curl_getinfo($ch);
$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
$header = substr($result, 0, $header_size);
$body = substr($result, $header_size);

print_r($body);
//convert to php object
$out_data_string = json_decode($body);


?>



