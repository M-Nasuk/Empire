<?php

namespace App\EventListener;

use Swift_Mailer;
use Swift_Message;

class RegisterListener
{
    public $mailer;
    private $adminMail;

    /**
     * RegisterListener constructor.
     * @param Swift_Mailer $mailer
     * @param $adminMail
     */
    public function __construct(Swift_Mailer $mailer, $adminMail)
    {
        $this->mailer = $mailer;
        $this->adminMail = $adminMail;
    }

    public function sendMailToUser()
    {
        $message = (new Swift_Message('Hello'))
            ->setFrom('me@test.fr')
            ->setTo($this->adminMail)
            ->setBody('Here is the message itself');

        $this->mailer->send($message);
    }
}