<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use App\Entity\Usuario;
use App\Entity\Departamento;
use DateTime;


class DepartamentosControllerTest extends WebTestCase
{
    protected function createAuthorizedClient()
    {
        $client = static::createClient();
        $container = static::$kernel->getContainer();
        $session = $container->get('session');
        $person = self::$kernel->getContainer()->get('doctrine')->getRepository(Usuario::class)->findOneByNombre('tester');

        $token = new UsernamePasswordToken($person, null, 'main', $person->getRoles());
        $session->set('_security_main', serialize($token));
        $session->save();

        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));

        return $client;
    }

    //--------------------------//
    //  TESTS A PARTIR DE AQUÍ	//
    //--------------------------//
    public function testIndex()
    {
        $client = $this->createAuthorizedClient();
        $departamento = $client->request('GET', '/departamentolocal/');

        //ver si se recibe bien la información (mensaje 200 - OK)
        $this->assertEquals(200, $client->getResponse()->getStatusCode());	
        
        //ver si lo pedido 
        $this->assertSame('Departamento index', $departamento->filter('title')->text());
        $this->assertSame('Departamento index', $departamento->filter('h1')->text());
        
        
        //Usuario sin registrar
        $clientNoAuth = static::createClient();
        $clientNoAuth->request('GET', '/departamentolocal/');
        //redireccion a main
        $this->assertEquals(302, $clientNoAuth->getResponse()->getStatusCode());
    }
    
    public function testNew()
    {
        //caso con todo bien, redirección a /departamento/ y bien agregado el nuevo departamento.
        $client = $this->createAuthorizedClient();
        $crawler = $client->request('GET', '/departamentolocal/new');
        $form = $crawler->selectButton('Guardar')->form();
        $form['departamento[Nombre]'] = 'dev';
        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();

        $dep = self::$kernel->getContainer()->get('doctrine')->getRepository( 
            Departamento::class)->findOneBy(array( 
            'Nombre' => 'dev',                 
            )); 
 
        //Cogemos la id de esa fila del repositorio Booking
        $depId = $dep->getId(); 

        //existe por lo menos uno con estas características
        $this->assertNotNull($depId);  

        //mal los campos
        $client = $this->createAuthorizedClient();
        $crawler = $client->request('GET', '/departamentolocal/new');
        $form = $crawler->selectButton('Guardar')->form();
        $form['departamento[Nombre]'] = NULL;
        $crawler = $client->submit($form);
        $this->assertFalse($client->getResponse()->isRedirect());    
    }

    public function testShow()
    {
        $client = $this->createAuthorizedClient();
        //ver si se recibe bien la información (mensaje 200 - OK)
        $user = self::$kernel->getContainer()->get('doctrine') ->getRepository('App\Entity\Departamento')->findOneBy(array('Nombre' => 'testing'));

        $crawler = $client -> request('GET', '/departamentolocal/testing');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //ver si lo pedido se muestra
        $this->assertSame('Departamento', $crawler->filter('title')->text());
        $this->assertSame('Departamento', $crawler->filter('h1')->text());
        $this->assertEquals('testing', $crawler->filter('td:contains("testing")')->text());
   
        //caso del pedido de ver un departamento que no existe (no hay tantas personas)
        $crawler = $client->request('GET', '/departamentolocal/1246548788');
        $this->assertEquals(404, $client->getResponse()->getStatusCode()
        );

    }

    public function testEdit()
    {
        $client = $this->createAuthorizedClient();
        $crawler = $client->request('GET', '/departamentolocal/dev/edit');
        $form = $crawler->selectButton('Actualizar')->form();
        $form['departamento[Nombre]'] = 'devNew';
        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();

        //departamento que no existe
        $crawler = $client->request('GET', '/departamentolocal/1246548788/edit');
        //no encuentra el archivo porque no existe
        $this->assertEquals(404, $client->getResponse()->getStatusCode()
        );
    }
 
    public function testDelete()
    {
        $client = $this->createAuthorizedClient();

        //usamos uno de los departamentos agregados antes (devNew)
        $dep1 = self::$kernel->getContainer()->get('doctrine')->getRepository('App\Entity\Departamento')->findBy(array(
            'Nombre' => 'devNew'
            ));
        
        //eliminacion de uno de los que tenga ese nombre (el total de departamentos con ese nombre baja)
        $crawler = $client->request('GET', '/departamentolocal/devNew/edit');
        $form = $crawler->selectButton('Eliminar')->form();
        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();
        $dep2 = self::$kernel->getContainer()->get('doctrine')->getRepository('App\Entity\Departamento')->findBy(array(
            'Nombre' => 'devNew'
            ));

        //Comparación de las arrays de los departamentos que tienen el nombre de dev
        //en el primer caso, hay los que hay al principio, en el segundo hay uno menos porque se eliminó
        //un elemento con ese nombre
        $this->assertTrue($dep1 > $dep2);

        //departamento que no existe
        $crawler = $client->request('GET', '/departamentolocal/1246548788/edit');
        //no encuentra el archivo porque no existe
        $this->assertEquals(404, $client->getResponse()->getStatusCode()
        );
    }
}