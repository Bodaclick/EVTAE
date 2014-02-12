<?php

namespace EVT\EAE\Tests\Communication\Mailer;

use EVT\EAE\Communication\Mailer\WelcomeMailer;

/**
 * Class AWSWelcomeMailerTest
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
@copyright 2014 Bodaclick */
class WelcomeMailerTest extends \PHPUnit_Framework_TestCase
{
    public function testSendWelcomeEmail()
    {
        $mailer = $this->getMockBuilder('\Swift_Mailer')->disableOriginalConstructor()->getMock();
        $mailer->expects($this->once())->method('send')->will($this->returnValue(1));
        $data = [
            'user' => [
                'username' => 'testUsername',
                'name' => 'testUserName',
                'surname' => 'testUserSurname',
                'email' => 'valid@email.com',
                'phone' => '+34 0123456789'
            ],
            'vertical' => [
                'domain' => 'test.com'
            ],
            'subject' => 'Welcome to '
        ];
        $template = 'string';

        $mailer = new WelcomeMailer($mailer);
        $mailer->send($data, $template);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDataIsNotArray()
    {
        $mailer = $this->getMockBuilder('\Swift_Mailer')->disableOriginalConstructor()->getMock();
        $mailer->expects($this->never())->method('send')->will($this->returnValue(1));
        $data = 'string';
        $template = 'string';

        $mailer = new WelcomeMailer($mailer);
        $mailer->send($data, $template);

    }

}