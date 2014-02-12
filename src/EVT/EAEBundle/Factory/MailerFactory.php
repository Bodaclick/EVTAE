<?php

namespace EVT\EAEBundle\Factory;

use Aws\Ses\SesClient;
use EVT\EAE\Communication\Mailer\AWSWelcomeMailer;
use Symfony\Component\Translation\Translator;

/**
 * Class AWSMailerFactory
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
@copyright 2014 Bodaclick */
class AWSMailerFactory 
{
    private $twig;
    private $translator;
    private $mailer;

    public function __construct(\Twig_Environment $twig, Translator $translator, AWSWelcomeMailer $mailer)
    {
        $this->twig = $twig;
        $this->translator = $translator;
        $this->mailer = $mailer;
    }

    public function send(array $data, $template)
    {
        $data['subject'] = $this->translator->trans('subject.user_welcome', [], 'email');
        $renderedTemplate = $this->twig->render($template, $data);
        return $this->mailer->send($data, $renderedTemplate);
    }
}