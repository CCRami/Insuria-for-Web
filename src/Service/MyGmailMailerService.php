<?php
namespace App\Service;

use PHPMailer\PHPMailer\PHPMailer;

class MyGmailMailerService
{
    private PHPMailer $phpMailer;

    public function __construct(array $mailerConfig)
    {
        $this->phpMailer = new PHPMailer();
        $this->configureMailer($mailerConfig);
    }

    private function configureMailer(array $config): void
    {
        $this->phpMailer->isSMTP();
        $this->phpMailer->Host = $config['host'];
        $this->phpMailer->Port = $config['port'];
        $this->phpMailer->SMTPAuth = true;
        $this->phpMailer->Username = $config['username'];
        $this->phpMailer->Password = $config['password'];
        $this->phpMailer->SMTPSecure = $config['encryption'];
        $this->phpMailer->setFrom($config['fromAddress'], $config['fromName']);
    }

    public function sendEmail(string $to, string $subject, string $body): bool
    {
        $this->phpMailer->addAddress($to);
        $this->phpMailer->Subject = $subject;
        $this->phpMailer->Body = $body;

        return $this->phpMailer->send();
    }
}