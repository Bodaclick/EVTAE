<?php

namespace EVT\EAE\Communication\Mailer;

use EVT\EAE\Communication\Mailer;
use Aws\Ses\SesClient;

/**
 * Class AWSWelcomeMailer
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright 2014 Bodaclick
 */
class AWSWelcomeMailer implements Mailer
{
    private $mailer;
    private $config = [];

    public function __construct(SesClient $awsSes, array $config)
    {
        $this->mailer = $awsSes;
        $this->config = array_merge($this->config, $config);
    }

    public function send($data, $template)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException('Data is not an array');
        }

        $this->prepare($template, $data);

        return $this->mailer->sendEmail($this->config);
    }

    private function prepare($template, $data)
    {
        $this->config['Destination']['ToAddresses'] = [$data['user']['email']];
        $this->config['ReplyToAddresses'] = ['no-reply@' . $data['vertical']['domain']];
        $this->config['Message']['Subject']['Data'] = $data['subject'] . ' - ' . $data['vertical']['domain'];
        $this->config['Message']['Subject']['Charset'] = 'UTF-8';
        $this->config['Message']['Body']['Html']['Data'] = $template;
        $this->config['Message']['Body']['Html']['Charset'] = 'UTF-8';
    }
}