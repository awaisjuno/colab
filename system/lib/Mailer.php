<?php

namespace Lib;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    protected $mail;

    public function __construct()
    {
        require_once __DIR__ . '/PHPMailer/Exception.php';
        require_once __DIR__ . '/PHPMailer/PHPMailer.php';
        require_once __DIR__ . '/PHPMailer/SMTP.php';

        $config = require __DIR__ . '/../../config/email.php';

        $this->mail = new PHPMailer(true);

        // Server settings
        $this->mail->isSMTP();
        $this->mail->Host       = $config['smtp_host'];
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = $config['smtp_user'];
        $this->mail->Password   = $config['smtp_pass'];
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port       = $config['smtp_port'];

        // Encoding
        $this->mail->CharSet    = $config['charset'];
        $this->mail->isHTML($config['mailtype'] === 'html');
    }

    public function send($to, $subject, $body, $from = null)
    {
        try {
            if ($from === null) {
                $from = ['email' => 'noreply@colab.com', 'name' => 'Colab'];
            }

            $this->mail->setFrom($from['email'], $from['name']);
            $this->mail->addAddress($to);

            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;

            return $this->mail->send();
        } catch (Exception $e) {
            return "Mailer Error: {$this->mail->ErrorInfo}";
        }
    }
}
