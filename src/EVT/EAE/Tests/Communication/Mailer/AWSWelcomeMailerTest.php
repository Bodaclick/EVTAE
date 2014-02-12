<?php

namespace EVT\EAE\Tests\Communication\Mailer;

use EVT\EAE\Communication\Mailer\AWSWelcomeMailer;

/**
 * Class AWSWelcomeMailerTest
 * @author Eduardo Gulias Davis <eduardo.gulias@bodaclick.com>
@copyright 2014 Bodaclick */
class AWSWelcomeMailerTest extends \PHPUnit_Framework_TestCase
{
    public function testSendWelcomeEmail()
    {
        $awsSes = $this->getMockBuilder('Aws\Ses\SesClient')->disableOriginalConstructor()
            ->setMethods(['sendEmail'])->getMock();
        $awsSes->expects($this->once())->method('sendEmail')->will($this->returnValue(1));
        $config = [];
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

        $mailer = new AWSWelcomeMailer($awsSes, $config);
        $mailer->send($data, $template);
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testDataIsNotArray()
    {
        $awsSes = $this->getMockBuilder('Aws\Ses\SesClient')->disableOriginalConstructor()
            ->setMethods(['sendEmail'])->getMock();
        $awsSes->expects($this->never())->method('sendEmail')->will($this->returnValue(1));
        $config = [];
        $data = 'string';
        $template = 'string';

        $mailer = new AWSWelcomeMailer($awsSes, $config);
        $mailer->send($data, $template);

    }

}