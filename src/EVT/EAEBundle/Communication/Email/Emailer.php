<?php

namespace EVT\EAEBundle\Communication\Email;

/**
 * Class Emailer
 * @author    Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class Emailer
{
    private $twig;
    private $mailer;

    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }

    public function send(array $data, $template)
    {
        $message = \Swift_Message::newInstance()
            ->setSubject($data['subject'] . ' - ' . $data['vertical']['domain'])
            ->setFrom(['no-reply@' . $data['vertical']['domain']])
            ->setTo([$data['user']['email']])
            ->setBody($this->twig->render($template, $data));
        $this->mailer->send($message);
    }
}