<?php
namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use App\Entity\Usuario;

/*
Hacer request y mirar que me reenvie al login

Logearte mal y que no te deje pasar (que muestre el error?)


*/

class SecurityControllerTest extends WebTestCase{

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

    public function testLogin(){

        //Requests con el metodo POST (no lo acepta)
        $client00 = static::createClient();
        $client00->request('POST', '/booking/calendar');
        
        $this->assertEquals(
            405,    //METHOD NOT ALLOWED
            $client00->getResponse()->getStatusCode()
        );		
        
        $client01 = $this->createAuthorizedClient();
        $client01->request('POST','/booking/calendar');
        $this->assertEquals(
            405,    //METHOD NOT ALLOWED
            $client01->getResponse()->getStatusCode()
        );	
        //Requests con GET
        $client02 = $this->createAuthorizedClient();
        $client02->request('GET','/booking/calendar');
        $this->assertEquals(
            200,    //OK
            $client02->getResponse()->getStatusCode()
        );  
        //Usuario no autorizado
        $client03 = static::createClient();
        $client03->request('GET', '/booking/calendar');
        
        //redireccionado porque no tiene los permisos necesarios
        $this->assertTrue($client03->getResponse()->isRedirect());  
    }

}