<?php

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use App\Entity\Usuario;
use App\Entity\Clientes;
use Doctrine\Common\Collections\ArrayCollection;

class ClientesControllerTest extends WebTestCase
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

    //--------------------------//
    //  TESTS A PARTIR DE AQUÍ	//
    //--------------------------//

    public function testIndex()
    {
	   	//Usuario sin registrar
    	$clientNoAuth = static::createClient();
    	$clientNoAuth->request('GET', '/clientes/');
		$this->assertEquals(
			302, 	//Redirigido al main
			$clientNoAuth->getResponse()->getStatusCode()
		);

		//Usuario tester autorizado
        $client = $this->createAuthorizedClient();
        $crawler = $client->request('GET', '/clientes/');
        $this->assertEquals(
            200,	//OK
            $client->getResponse()->getStatusCode()
        );		

        $this->assertSame('Clientes index', $crawler->filter('title')->text());
        $this->assertSame('Clientes index', $crawler->filter('h1')->text());

	}

	public function testNew()
    {
        $cliente = $this->createAuthorizedClient();
        $crawler = $cliente->request('GET', '/clientes/new');

        //creamos una variable de número random para poder crear mails de forma random agregando números aleatorios
        $random = rand(0, 125485485260);

        $form = $crawler->selectButton('Guardar')->form();

        $form['clientes[Nombre]'] = 'Discos&Co';
        $form['clientes[Correo]'] = "disco$random@co.com";
        $form['clientes[Servicio]'] = 'Discos duros';
        $form['clientes[Telefono]'] = '123456789';
        $form['clientes[Direccion]'] = 'C/ Aribau 50';
        $form['clientes[Responsable]'] = 'Pablo';

        $crawler = $cliente->submit($form);

        $this->assertTrue($cliente->getResponse()->isRedirect());    

        //ver si se guardó
        $clienteNew = self::$kernel->getContainer()->get('doctrine')->getRepository( 
            Clientes::class)->findOneBy(array( 
            'Nombre' => 'Discos&Co', 
            'Correo' => "disco$random@co.com", 
            'Servicio' => 'Discos duros',  
            'Telefono' => '123456789',
            'Direccion' => 'C/ Aribau 50',
            'Responsable' => 'Pablo',                
            )); 

        $clienteNew = $clienteNew->getId();

        $this->assertNotNull($clienteNew);

        //intento de crear nuevo con campos erróneos
        $crawler = $cliente->request('GET', '/clientes/new');

        $form = $crawler->selectButton('Guardar')->form();

        $form['clientes[Nombre]'] = NULL;
        $form['clientes[Correo]'] = "disco$random@co.com";
        $form['clientes[Servicio]'] = 'Discos duros';
        $form['clientes[Telefono]'] = '123456789';
        $form['clientes[Direccion]'] = 'C/ Aribau 50';
        $form['clientes[Responsable]'] = 'Pablo';

        $crawler = $cliente->submit($form);
        
        //No se redirecciona por lo que hay un error
        $this->assertFalse($cliente->getResponse()->isRedirect());    

        //Usuario sin registrar
        $clientNoAuth = static::createClient();
        $clientNoAuth->request('GET', '/clientes/new');
        //redireccion a main
        $this->assertEquals(302, $clientNoAuth->getResponse()->getStatusCode());
	}

	public function testShow()
    {
    	$cliente = $this->createAuthorizedClient();
        
        //Guardamos un cliente para mostrarlo después
        $crawler = $cliente->request('GET', '/clientes/new');

        //creamos una variable de número random para poder crear mails de forma random agregando números aleatorios
        $random = rand(0, 125485485260);

        $form = $crawler->selectButton('Guardar')->form();

        $form['clientes[Nombre]'] = 'Memorias';
        $form['clientes[Correo]'] = "memo$random@co.com";
        $form['clientes[Servicio]'] = 'Memorias RAM';
        $form['clientes[Telefono]'] = '123456787';
        $form['clientes[Direccion]'] = 'C/ Aribau 45';
        $form['clientes[Responsable]'] = 'Sergio';

        $crawler = $cliente->submit($form);

	   	//Sacamos el número de usuario del cliente de la prueba anterior
    	$Sergio = self::$kernel->getContainer()->get('doctrine')->getRepository(Clientes::class)->findOneBy(array(
        		'Nombre' => 'Memorias',
        		'Correo' => "memo$random@co.com",
        		'Servicio' => 'Memorias RAM',
        		'Telefono' =>  '123456787',
        		'Direccion' =>  'C/ Aribau 45',
                'Responsable' =>  'Sergio',
        	));

    	$numCliente = $Sergio->getId();
		$this->assertNotNull($numCliente);	//El cliente está insertado

		//Lo mostramos
        $crawler = $cliente->request('GET', '/clientes/'.$numCliente.'');
        $this->assertEquals(
            200,	//OK
            $cliente->getResponse()->getStatusCode()
        );	

        //PROBAMOS A MOSTRAR UN CLIENTE QUE NO EXISTE
        $crawler = $cliente->request('GET', '/clientes/a');
        $this->assertEquals(
            404,    //No lo encuentra
            $cliente->getResponse()->getStatusCode()
        );
	}

	public function testEdit()
    {
        $cliente = $this->createAuthorizedClient();
        
        //Guardamos un cliente para mostrarlo después
        $crawler = $cliente->request('GET', '/clientes/new');

        //creamos una variable de número random para poder crear mails de forma random agregando números aleatorios
        $random = rand(0, 125485485260);

        $form = $crawler->selectButton('Guardar')->form();

        $form['clientes[Nombre]'] = 'Memorias2';
        $form['clientes[Correo]'] = "memo$random@co.com";
        $form['clientes[Servicio]'] = 'Memorias RAM';
        $form['clientes[Telefono]'] = '123456787';
        $form['clientes[Direccion]'] = 'C/ Aribau 45';
        $form['clientes[Responsable]'] = 'Sergio';

        $crawler = $cliente->submit($form);

	   	//Sacamos el número de usuario del cliente de la prueba anterior
    	$Sergio = self::$kernel->getContainer()->get('doctrine')->getRepository(Clientes::class)->findOneBy(array(
        		'Nombre' => 'Memorias2',
        		'Correo' => "memo$random@co.com",
        		'Servicio' => 'Memorias RAM',
        		'Telefono' =>  '123456787',
        		'Direccion' =>  'C/ Aribau 45',
                'Responsable' =>  'Sergio',
        	));

    	$numCliente = $Sergio->getId();
		$this->assertNotNull($numCliente);	//El cliente está insertado

		//Vamos a la página de edición
        $crawler = $cliente->request('GET', '/clientes/'.$numCliente.'/edit');
        $this->assertEquals(
            200,	//OK
            $cliente->getResponse()->getStatusCode()
        );	

        //Editamos datos
        $form = $crawler->selectButton('Actualizar')->form();
        $form['clientes[Nombre]'] = 'Memorias2';
        $form['clientes[Correo]'] = "memo$random@co.com";
        $form['clientes[Servicio]'] = 'Memorias RAM';
        $form['clientes[Telefono]'] = '123456787';
        $form['clientes[Direccion]'] = 'C/ Aribau 45';
        $form['clientes[Responsable]'] = 'Sergio Actualizado';

        //Mandamos datos
        $crawler = $cliente->submit($form);

        //Comprobamos si el usuario se ha insertado correctamente
    	$Sergio = self::$kernel->getContainer()->get('doctrine')->getRepository(Clientes::class)->findOneBy(array(
            'Nombre' => 'Memorias2',
            'Correo' => "memo$random@co.com",
            'Servicio' => 'Memorias RAM',
            'Telefono' =>  '123456787',
            'Direccion' =>  'C/ Aribau 45',
            'Responsable' =>  'Sergio Actualizado',
        ));

        $numCliente = $Sergio->getId();

        //Miramos si la consulta tiene respuesta
        $this->assertNotNull($numCliente);	

        //PROBAMOS A EDITAR UN CLIENTE QUE NO EXISTE
        $crawler = $cliente->request('GET', '/clientes/a/edit');
        $this->assertEquals(
            404,    //No lo encuentra
            $cliente->getResponse()->getStatusCode()
        );
	}

	public function testDelete()
    {
        $cliente = $this->createAuthorizedClient();
        
        //Guardamos un cliente para mostrarlo después
        $crawler = $cliente->request('GET', '/clientes/new');

        //creamos una variable de número random para poder crear mails de forma random agregando números aleatorios
        $random = rand(0, 125485485260);

        $form = $crawler->selectButton('Guardar')->form();

        $form['clientes[Nombre]'] = 'Memorias p/ delete';
        $form['clientes[Correo]'] = "memo$random@co.com";
        $form['clientes[Servicio]'] = 'Memorias RAM';
        $form['clientes[Telefono]'] = '123456787';
        $form['clientes[Direccion]'] = 'C/ Aribau 45';
        $form['clientes[Responsable]'] = 'Sergio';

        $crawler = $cliente->submit($form);

	   	//Sacamos el número de usuario del cliente de la prueba anterior
    	$Sergio = self::$kernel->getContainer()->get('doctrine')->getRepository(Clientes::class)->findBy(array(
        		'Servicio' => 'Memorias RAM',
        	));

        $id1 = $Sergio[0]->getId();

		//Vamos a la página de edición
        $crawler = $cliente->request('GET', "/clientes/$id1");
        $form = $crawler->selectButton('Eliminar')->form();
        $crawler = $cliente->submit($form);
        $this->assertTrue($cliente->getResponse()->isRedirect());

	   	//Sacamos el número de usuario del cliente de la prueba anterior
        $Sergio2 = self::$kernel->getContainer()->get('doctrine')->getRepository(Clientes::class)->findBy(array(
            'Servicio' => 'Memorias RAM',
        ));

        //Comparación de las arrays de los clientes que tienen como servicio Memorias RAM
        //en el primer caso, hay los que hay al principio, en el segundo hay uno menos porque se eliminó
        //un elemento con ese título
        $this->assertTrue($Sergio > $Sergio2);

        //Delete para evento que no existe
        $crawler = $cliente->request('GET', "/clientes/1246548788gfgf");
        //no encuentra el archivo porque no existe esa id
        $this->assertEquals(404, $cliente->getResponse()->getStatusCode()
        );
    }
}