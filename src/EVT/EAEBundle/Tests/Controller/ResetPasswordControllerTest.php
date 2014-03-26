<?php

namespace EVT\EAEBundle\Test\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

 /**
 * LoginControllerTest
 *
 * @author    Daniel Jimenez <djimenez@bodaclick.com>
 * @copyright 2014 Bodaclick S.A.
 */
class ResetPasswordControllerTest extends WebTestCase
{
    public function testResetPage()
    {
        $client = static::createClient();
        $texto = 'E-Vertical';
        
        $crawler = $client->request('GET', '/resetpassword');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        
        $this->assertTrue($crawler->filter('html:contains("' . $texto . '")')->count() > 0);
    }
}
