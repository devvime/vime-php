<?php

namespace App\Controllers;

use Core\Support\Email;

class EmailController extends Email {

    public $mail;

    public function __construct()
	{
        parent::__construct();        
        $this->mail = new Email();
    }

    public function index()
    {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $product = $_POST['product'];
        $msg = $_POST['msg'];
        $subject = 'Lead EstiloDev';
        $altbody = 'Produto de interesse: ' . $product;

        $body = '
        <h1>NOVO LEAD ESTILODEV</h1>
        <p><strong>Nome</strong>: '.$name.'</p>
        <p><strong>E-mail</strong>: '.$email.'</p>
        <p><strong>Telefone</strong>: '.$phone.'</p>
        <p><strong>Produto selecionado</strong>: '.$product.'</p>
        <p><strong>Mensagem</strong>: '.$msg.'</p>
        ';

        try {

            $this->mail->set('victor@estilodev.com.br', 'Victor'); // Qeum está enviando
            $this->mail->add('victor@estilodev.com.br', 'Victor'); // Quem vai receber
            $this->mail->content($subject,$body,$altbody);
            $this->mail->send(); // Dispara o email
            return true;

        } catch (Exception $e) {

            echo "Falha no erro!: {$this->mail->ErrorInfo}";

        }
    }

}
