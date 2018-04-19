<?php

header('Refresh: 1; URL=https://dev-people.carnegiescience.edu/grant-ticketing-system');

$label = $subject = $txt = $headers = "";
//groove HQ email
$to = "carnegie-science@inbox.groovehq.com";
//customer email
if($_POST["to"]){$email = $_POST["to"];}else{$email = "not given";}
//subject of the ticket
if($_POST["subject"]){$subject = $_POST["subject"];}else{$subject = "not given";}
//get tags
if($_POST["tag"]){
    $last_tag = end(array_keys($_POST["tag"]));
    foreach ($_POST["tag"] as $key => $tag) {
        if ($key != $last_tag) {
            $label .= $tag . ", ";
        }
        else {
            $label .= $tag;
        }
    }
}else{$tags = "";}
//get ticket message
if($_POST["body"]){$body = $_POST["body"];}else{$body = "not given";}

//email header
$headers = "From: " . $email . "\r\n";
//concat message and tags
$label = "@label " . $label;
$txt = $body . "\r\n" . $label;


mail($to,$subject,$txt,$headers);
echo "your ticket is been sent!";
