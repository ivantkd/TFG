<?php

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use App\Entity\Usuario;

class MainControllerTest extends WebTestCase
{
	
	protected function createAuthorizedClient()
    {
        $client = static::createClient();
        $container = static::$kernel->getContainer();
        $session = $container->get('session');
        $person = self::$kernel->getContainer()->get('doctrine')->getRepository(Usuario::class)->findOneByMail('test@test.test');

        $token = new UsernamePasswordToken($person, null, 'main', $person->getRoles());
        $session->set('_security_main', serialize($token));
        $session->save();

        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

        return $client;
    }

	public function testIndex()
	{
		//Usuario sin registrar
		$clientNoAuth = static::createClient();
		$clientNoAuth->request('GET', '/main');
		$this->assertEquals(
            302,    //Redireccionado, no llega a lo pedido
            $clientNoAuth->getResponse()->getStatusCode());

		//Usuario tester
		$client = $this->createAuthorizedClient();
   		$client->request('GET', '/main');
        $this->assertEquals(
            302,    //Redirección a calendar
            $client->getResponse()->getStatusCode()
        );		
	}
}
