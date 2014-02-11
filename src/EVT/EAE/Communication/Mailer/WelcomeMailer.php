<?php

namespace EVT\EAE\Communication\Mailer;

use EVT\EAE\Communication\Mailer;
use Aws\Ses\SesClient;

/**
 * Class WelcomeMailer
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
@copyright 2014 Bodaclick */
class WelcomeMailer implements Mailer
{
    private $mailer;
    private $templating;

    public function __construct(SesClient $awsSes, $templating)
    {
        $this->mailer = $awsSes;
        $this->templating = $templating;
    }

    public function prepare($data)
    {
        return $this;
    }

    public function send()
    {

    }
}