<?php

namespace Core\Support;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class Email 
{
    public $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        //Server settings
        $this->mail->isSMTP();
        $this->mail->isHTML(true);
        $this->mail->setLanguage("br");
        $this->mail->Charset = "utf-8";
        $this->mail->SMTPAuth   = true;
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;

        $this->mail->Host       = "smtp.host.com.br";
        $this->mail->Username   = 'user@email.com.br';
        $this->mail->Password   = 'password';
        $this->mail->Port       = 587;
    }

    public function set($email,$name)
    {
        $this->mail->setFrom($email,$name);
    }

    public function add($email,$name)
    {
        $this->mail->addAddress($email,$name);
    }

    public function content($subject, $body, $altbody)
    {
        $this->mail->isHTML(true);
        $this->mail->Subject = $subject;
        $this->mail->Body    = $body;
        $this->mail->AltBody = $altbody;
    }

    public function send()
    {
        $this->mail->send();
    }
}