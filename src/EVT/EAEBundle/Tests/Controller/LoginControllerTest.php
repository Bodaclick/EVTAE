<?php

namespace EVT\EAEBundle\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

 /**
 * LoginControllerTest
 *
 * @author    Quique Torras <etorras@bodaclick.com>
 * @copyright 2014 Bodaclick S.A.
 */
class LoginControllerTest extends WebTestCase
{
    public function testLoginPage()
    {
        $client = static::createClient();
        
        $crawler = $client->request('GET', '/leads');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
