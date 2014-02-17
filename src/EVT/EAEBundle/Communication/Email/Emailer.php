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
            ->setSubject($data['mailing']['subject'] . ' - ' . $data['vertical']['domain'])
            ->setFrom(['no-reply@' . $data['vertical']['domain']])
            ->setBody($this->twig->render($template, $data), 'text/html');

        if (is_array($data['mailing']['to'])) {
            $message->setTo($data['mailing']['to']);
        } else {
            $message->setTo([$data['mailing']['to']]);
        }

        if (isset($data['mailing']['cc'])) {
            $message->setCc([$data['mailing']['cc']]);
        }

        $this->mailer->send($message);
    }
}
