<?php

namespace EVT\EAEBundle\Tests\Communication\Emailer;

use EVT\EAEBundle\Communication\Email\Emailer;

/**
 * Class AWSEmailerTest
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
@copyright 2014 Bodaclick */
class EmailerTest extends \PHPUnit_Framework_TestCase
{
    public function testSendWelcomeEmail()
    {
        $mailer = $this->getMockBuilder('\Swift_Mailer')->disableOriginalConstructor()->getMock();
        $mailer->expects($this->once())->method('send')->will($this->returnValue(1));
        $twig = $this->getMockBuilder('\Twig_Environment')->disableOriginalConstructor()->getMock();
        $twig->expects($this->once())->method('render');
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

        $mailer = new Emailer($mailer, $twig);
        $mailer->send($data, $template);
    }
}