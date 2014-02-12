<?php

namespace EVT\EAE\Communication\Mailer;

use EVT\EAE\Communication\Mailer;
use Aws\Ses\SesClient;

/**
 * Class AWSWelcomeMailer
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class WelcomeMailer implements Mailer
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function send($data, $template)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException('Data is not an array');
        }

        return $this->mailer->send($this->prepare($template, $data));
    }

    private function prepare($template, $data)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($data['subject'] . ' - ' . $data['vertical']['domain'])
            ->setFrom(['no-reply@' . $data['vertical']['domain']])
            ->setTo([$data['user']['email']])
            ->setBody($template);
        return $message;
    }
}