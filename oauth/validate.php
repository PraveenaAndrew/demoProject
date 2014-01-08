<?php

//setting parameters

$authcode= $_GET["code"];

$clientid='217213914195.apps.googleusercontent.com';

$clientsecret='YZl500kcvyEGpzENQ-8cpwGq';

$redirecturi='http://localhost/Perl-Php/oauth/oauth/validate.php';

$fields=array(

'code'=>  urlencode($authcode),

'client_id'=>  urlencode($clientid),

'client_secret'=>  urlencode($clientsecret),

'redirect_uri'=>  urlencode($redirecturi),

'grant_type'=>  urlencode('authorization_code')

);

//url-ify the data for the POST

$fields_string='';

foreach($fields as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }

$fields_string=rtrim($fields_string,'&');

//open connection

$ch = curl_init();

//set the url, number of POST vars, POST data

curl_setopt($ch,CURLOPT_URL,'https://accounts.google.com/o/oauth2/token');

curl_setopt($ch,CURLOPT_POST,5);

curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);

// Set so curl_exec returns the result instead of outputting it.

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

//to trust any ssl certificates

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

//execute post

$result = curl_exec($ch);

//close connection

curl_close($ch);

//extracting access_token from response string

$response=  json_decode($result);

$accesstoken= $response->access_token;

//passing accesstoken to obtain contact details

$xmlresponse=  file_get_contents('https://www.google.com/m8/feeds/contacts/default/full?oauth_token='.$accesstoken);

//reading xml using SimpleXML

$xml=  new SimpleXMLElement($xmlresponse);

$xml->registerXPathNamespace('gd', 'http://schemas.google.com/g/2005');

$result = $xml->xpath('//gd:email');

foreach ($result as $title) {
?>
<div style="font:20px Calibri ; background:url('images.jpg') repeat; "><?php echo $title->attributes()->address . "<br><br>";?></div>
<?php
}

?>

