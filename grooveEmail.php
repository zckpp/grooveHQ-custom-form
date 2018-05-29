<?php

//header('Refresh: 3; URL=https://dev-people.carnegiescience.edu/node/4239');
$serviceEmail = "carnegie-science@inbox.groovehq.com";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpMailer/src/Exception.php';
require 'phpMailer/src/PHPMailer.php';
require 'phpMailer/src/SMTP.php';


$label = $subject = $txt = $headers = "";
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

$target_dir = "/srv/Web/Drupal7/carnegiescience.edu/sites/default/groove/upload/";
$files = [];

if(count($_FILES['filesToUpload'])) {
    foreach ($_FILES['filesToUpload']['name'] as $key=>$fileName) {
        $fileArray = [];
        $target_file = $target_dir . basename($fileName);
        $fileArray[] = $target_file;
        $fileArray[] = basename($fileName);
        $uploadOk = 1;
        // Check file size
        if ($_FILES['filesToUpload']["size"][$key] > 500000) {
            echo "Sorry, your file is too large.";
            $uploadOk = 0;
        }
        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.";
        // if everything is ok, try to upload file
        } else {
            if (move_uploaded_file($_FILES['filesToUpload']["tmp_name"][$key], $target_file)) {
                echo "The file ". basename($fileName). " has been uploaded.";
                $files[] = $fileArray;
            } else {
                echo "Sorry, there was an error uploading your file.";
                echo "</p>";
                echo '<pre>';
                echo 'Here is some more debugging info:';
                print_r($target_file);
                print "</pre>";
            }
        }

    }
}


//$FileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

// Check if file already exists
//if (file_exists($target_file)) {
//    echo "Sorry, file already exists.";
//    $uploadOk = 0;
//}


// Allow certain file formats
//if($FileType != "doc" && $FileType != "docx" && $FileType != "xls"
//    && $FileType != "pdf" ) {
//    echo "Sorry, word, excel and pdf files are allowed.";
//    $uploadOk = 0;
//}


//email header
$headers = "From: " . $email . "\r\n";
//concat message and tags
$label = "@label " . $label;
$txt = $body . "\r\n" . $label . "\r\n";

//init phpMailer
$go = 0;
$mail = new PHPMailer(true);
try {
    $mail->From      = $email;
    $mail->FromName  = $name;
    $mail->Subject   = $subject;
    $mail->Body      = $txt;
    $mail->AddAddress( $serviceEmail );
    if(count($files)) {
        foreach ($files as $file) {
            $mail->AddAttachment( $file[0] , $file[1] );
        }
    }
    $mail->send();
    echo 'Your ticket has been submitted';
    $go = 1;
} catch (Exception $e) {
    echo 'Message could not be sent. Mailer Error: ', $email->ErrorInfo;
}

//set priority to urgent
//if("yes" == $_POST["priority"] && 1 == $go){
//    echo "\r\n start setting priority";
//    sleep(5);
//    $url = "https://api.groovehq.com/v1/tickets";
//    $response = HTTPRequester::HTTPGet($url, array("access_token" => $serviceToken, "per_page" => "1"));
//    $response = json_decode($response);
//    $ticket_number = $response->tickets[0]->number;
//    echo $ticket_number;
//    $url = "https://api.groovehq.com/v1/tickets/" . $ticket_number . "/priority?access_token=" . $serviceToken;
//    $response = HTTPRequester::HTTPPut($url, array("priority" => "urgent"));
//}

?>
