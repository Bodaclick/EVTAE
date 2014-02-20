<?php

namespace EVT\EAEBundle\Tests\Communication\Emailer;

use EVT\EAEBundle\Communication\Email\Emailer;

/**
 * Class AWSEmailerTest
 * @author    Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
 * @copyright 2014 Bodaclicki
 */
class EmailerTest extends \PHPUnit_Framework_TestCase
{
    public function testSendWelcomeEmail()
    {
        $mailer = $this->getMockBuilder('\Swift_Mailer')->disableOriginalConstructor()->getMock();
        $mailer->expects($this->once())->method('send')->will($this->returnValue(1));
        $converter = $this
            ->getMockBuilder('\RobertoTru\ToInlineStyleEmailBundle\Converter\ToInlineStyleEmailConverter')
            ->disableOriginalConstructor()->getMock();
        $converter->expects($this->once())->method('setHTMLByView')->will($this->returnValue(1));

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
            'mailing' => [
                'to' => 'valid@email.com',
                'subject' => 'Welcome to '
            ],
        ];
        $template = 'string';

        $mailer = new Emailer($mailer, $converter);
        $mailer->send($data, $template);
    }
}
