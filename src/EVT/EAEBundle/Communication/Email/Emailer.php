<?php

namespace EVT\EAEBundle\Communication\Email;

/**
 * Class Emailer
 * @author    Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class Emailer
{
    private $mailer;
    private $converter;

    public function __construct(\Swift_Mailer $mailer, $converter)
    {
        $this->mailer = $mailer;
        $this->converter = $converter;
    }

    public function send(array $data, $template)
    {
        $this->converter->setHTMLByView($template, $data);
        $this->converter->setCSS('');
        $this->converter->setUseInlineStylesBlock(true);
        $this->converter->setStripOriginalStyleTags(true);

        $message = \Swift_Message::newInstance()
            ->setSubject($data['mailing']['subject'] . ' - ' . $data['vertical']['domain'])
            ->setFrom(['no-reply@' . $data['vertical']['domain']])
            ->setBody($this->converter->generateStyledHTML(), 'text/html');

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
