<?php

namespace EVT\EAE\Tests\Communication\Mailer;

use EVT\EAE\Communication\Mailer\WelcomeMailer;

/**
 * Class WelcomeMailerTest
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
@copyright 2014 Bodaclick */
class WelcomeMailerTest 
{
    public function testSendWelcomeEmail()
    {
        $mailer = new WelcomeMailer($awsSes, $templating);
        $mailer->prepare($data);
    }

} 