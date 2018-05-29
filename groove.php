<?php

//need to be changed when intranet go live
header('Refresh: 1; URL=https://dev-people.carnegiescience.edu/node/4228');
//groove HQ service account credentials
$serviceEmail = "";
$serviceToken = "";

class HTTPRequester {
    //get http response header
    public static $status;
    public static function getStatus($ch) {
        self::$status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    }
    /**
     * @description Make HTTP-GET call
     * @param       $url
     * @param       array $params
     * @return      HTTP-Response body or an empty string if the request fails or is empty
     */
    public static function HTTPGet($url, array $params) {
        $query = http_build_query($params);
        $ch    = curl_init($url.'?'.$query);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
    /**
     * @description Make HTTP-POST call
     * @param       $url
     * @param       array $params
     * @return      HTTP-Response body or an empty string if the request fails or is empty
     */
    public static function HTTPPost($url, array $params) {
        $query = http_build_query($params);
        $ch    = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        $response = curl_exec($ch);
        self::getStatus($ch);
        curl_close($ch);
        return $response;
    }
    /**
     * @description Make HTTP-PUT call
     * @param       $url
     * @param       array $params
     * @return      HTTP-Response body or an empty string if the request fails or is empty
     */
    public static function HTTPPut($url, array $params) {
        $query = http_build_query($params);
        $ch    = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
    /**
     * @category Make HTTP-DELETE call
     * @param    $url
     * @param    array $params
     * @return   HTTP-Response body or an empty string if the request fails or is empty
     */
    public static function HTTPDelete($url, array $params) {
        $query = http_build_query($params);
        $ch    = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
}

$label = "";

//customer email
if($_POST["to"]){$email = $_POST["to"];}else{$email = "not given";}
//customer name
if($_POST["name"]){$name = $_POST["name"];}else{$name = "not given";}
//subject of the ticket
if($_POST["subject"]){$subject = $_POST["subject"];}else{$subject = "";}
//get tags
if($_POST["tag"]){
    foreach ($_POST["tag"] as $key => $tag) {
       $label .= $tag . ", ";
    }
}
else{$tags = "";}
//get ticket message
if($_POST["body"]){$body = $_POST["body"];}else{$body = "not given";}

//make subject a label as well
$label .= $subject;

//set groove ticket attributes
$response = HTTPRequester::HTTPPost("https://api.groovehq.com/v1/tickets?access_token=" . $serviceToken,
    array(
        "body" => $body,
        "from" => $serviceEmail,
        "tags" => $label,
        "subject" => $subject,
        //customer info
        "to" => array(
            "email" => $email,
            "name" => $name
        )
    ));
$status = HTTPRequester::$status;

if ($response && "201" == $status) {
    echo "your ticket has been sent!";
}
else {
    echo "Error number " . $status . ", please try again or contact the admin";
}

//set priority to urgent
if("yes" == $_POST["priority"] && $response){
        $response = json_decode($response);
        $ticket_number = $response->ticket->number;
        $url = "https://api.groovehq.com/v1/tickets/" . $ticket_number . "/priority?access_token=" . $serviceToken;
        $response = HTTPRequester::HTTPPut($url, array("priority" => "urgent"));
}

?>