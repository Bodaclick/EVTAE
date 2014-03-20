<?php

namespace EVT\EAEBundle\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

 /**
 * LoginControllerTest
 *
 * @author    Quique Torras <etorras@bodaclick.com>
 * @copyright 2014 Bodaclick S.A.
 */
class LoginControllerTest
{
    public function testLoginPage()
    {
        $this->client->request('GET', '/leads');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}
