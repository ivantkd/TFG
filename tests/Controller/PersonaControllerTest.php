<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use App\Entity\Usuario;
use App\Entity\Persona;


class PersonaControllerTest extends WebTestCase
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
        //Usuario autentificado correctamente
        $client = $this->createAuthorizedClient();
        $persona = $client->request('GET', '/persona/');

        //ver si se recibe bien la información (mensaje 200 - OK)
        $this->assertEquals(200, $client->getResponse()->getStatusCode());	
        
        //ver si lo pedido 
        $this->assertSame('Persona index', $persona->filter('title')->text());
        $this->assertSame('Directorio de personas', $persona->filter('h1')->text());

        //Usuario sin registrar
    	$clientNoAuth = static::createClient();
        $clientNoAuth->request('GET', '/proveedores/');
        //redireccion a main
		$this->assertEquals(302, $clientNoAuth->getResponse()->getStatusCode());
    }
    
    public function testNew()
    {
	   	//Usuario sin registrar (redireccionado a main)
        $clientNoAuth = static::createClient();
        $clientNoAuth->request('GET', '/clientes/new');
        //redireccion a main
        $this->assertEquals(302, $clientNoAuth->getResponse()->getStatusCode());

        //caso con todo bien, redirección a /departamento/ y bien agregado el nuevo departamento.
        $client = $this->createAuthorizedClient();
        $crawler = $client->request('GET', '/persona/new');
        $form = $crawler->selectButton('Guardar')->form();
        $form['persona[Nombre]'] = 'Celia';
        $form['persona[Apellidos]'] = 'Cruz';
        $form['persona[Cargo]'] = 'Boss';
        $form['persona[Correo]'] = 'celiaDead@gmail.com';
        $form['persona[Telefono]'] = '123456789';
        $form['persona[Local_id]'] = '1';
        $form['persona[Departamento]'] = '1';

        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();
    }

    public function testShow()
    {
        $client = $this->createAuthorizedClient();
        $user = self::$kernel->getContainer()->get('doctrine') ->getRepository('App\Entity\Persona')->findOneBy(array('Numero_Empleado' => '1'));

        $crawler = $client -> request('GET', '/persona/1');

        //ver si se recibe bien la información (mensaje 200 - OK)
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //ver si lo pedido se muestra
        $this->assertSame('Persona', $crawler->filter('title')->text());
        $this->assertSame('Información de la persona  tester ', $crawler->filter('h1')->text());
        $this->assertEquals('tester', $crawler->filter('td:contains("tester")')->text());

        //caso del pedido de ver una persona que no existe (no hay tantas personas)
        $crawler = $client->request('GET', '/persona/1246548788');
        $this->assertEquals(404, $client->getResponse()->getStatusCode()
        );
    }

    public function testEdit()
    {
        //como tenemos en primera posicion a tester, hacemos un edit suyo
        $client = $this->createAuthorizedClient();
        $crawler = $client->request('GET', '/persona/1/edit');
        $form = $crawler->selectButton('Update')->form();
        $form['persona[Apellidos]'] = 'Testy';
        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();

        //edit teniendo en cuenta los datos de una persona
        $persona = self::$kernel->getContainer()->get('doctrine')->getRepository('App\Entity\Persona')->findOneBy(array(
            'Nombre' => 'Celia',
            'Apellidos' => 'Cruz',
            'Cargo' => 'Boss',
            'Correo' => 'celiaDead@gmail.com',
            'Telefono' => '123456789',
            'Local_id' => '1',
            'Departamento' => '1',
            ));

        $personaID = $persona->getNumero_Empleado();
        $this->assertNotNull($personaID);	//El cliente está insertado

        $crawler = $client->request('GET', '/persona/'.$personaID.'/edit');
        $form = $crawler->selectButton('Update')->form();
        $form['persona[Nombre]'] = 'Celia';
        $form['persona[Apellidos]'] = 'Cruz';
        $form['persona[Cargo]'] = 'Boss';
        $form['persona[Correo]'] = 'celiaDead@gmail.com';
        $form['persona[Telefono]'] = '123456665';
        $form['persona[Local_id]'] = '1';
        $form['persona[Departamento]'] = '1';

        $crawler = $client->submit($form);

        //verificacion de que se hayan cambiado los datos
        $personaVerificacion = self::$kernel->getContainer()->get('doctrine')->getRepository('App\Entity\Persona')->findOneBy(array(
            'Nombre' => 'Celia',
            'Apellidos' => 'Cruz',
            'Cargo' => 'Boss',
            'Correo' => 'celiaDead@gmail.com',
            'Telefono' => '123456665',
            'Local_id' => '1',
            'Departamento' => '1',
            ));
        $this->assertNotNull($personaVerificacion);

        //persona que no existe
        $crawler = $client->request('GET', '/clientes/1246548788/edit');
        //no encuentra el archivo porque no existe
        $this->assertEquals(404, $client->getResponse()->getStatusCode()
        );
    }

    public function testDelete()
    {
        $client = $this->createAuthorizedClient();

        $Celia = self::$kernel->getContainer()->get('doctrine')->getRepository('App\Entity\Persona')->findOneBy(array(
            'Nombre' => 'Celia',
            'Apellidos' => 'Cruz',
            'Cargo' => 'Boss',
            'Correo' => 'celiaDead@gmail.com',
            'Telefono' => '123456665',
            'Local_id' => '1',
            'Departamento' => '1',
            ));
        $personaID = $Celia->getNumero_Empleado();
        
        //borrado
        $crawler = $client->request('GET', '/persona/'.$personaID.'');
        $form = $crawler->selectButton('Eliminar')->form();
        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());

        //vuelvo a pedir la misma página, pero como ya se borró esta persona, me responde con un mensaje 404
        $crawler = $client->request('GET', '/persona/'.$personaID.'');    
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
 
    }
}