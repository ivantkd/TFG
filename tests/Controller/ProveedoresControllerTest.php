<?php

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use App\Entity\Usuario;
use App\Entity\Proveedores;
use Doctrine\Common\Collections\ArrayCollection;

class ProveedoresControllerTest extends WebTestCase
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
    	$clientNoAuth->request('GET', '/proveedores/');
		$this->assertEquals(
			302, 	//Redirigido al main
			$clientNoAuth->getResponse()->getStatusCode()
		);

		//Usuario tester autorizado
        $client = $this->createAuthorizedClient();
        $crawler = $client->request('GET', '/proveedores/');
        $this->assertEquals(
            200,	//OK
            $client->getResponse()->getStatusCode()
        );		

	}

	public function testNew()
    {
	   	//Usuario sin registrar
    	$clientNoAuth = static::createClient();
    	$clientNoAuth->request('GET', '/proveedores/new');
		$this->assertEquals(
			302, 	//Redirigido al main
			$clientNoAuth->getResponse()->getStatusCode()
		);

		//Usuario tester autorizado
        $client = $this->createAuthorizedClient();
        $crawler = $client->request('GET', '/proveedores/new');
        $this->assertEquals(
            200,	//OK
            $client->getResponse()->getStatusCode()
        );	

        //Una vez dentro, pruebas sobre formulario
        //TEST NEW USER - Valor correcto
        $form = $crawler->selectButton('Guardar')->form();
        $form['proveedores[Nombre]'] = 'Alfombras Juanma';
        $form['proveedores[Correo]'] = 'ajua@barato.com';
        $form['proveedores[Producto]'] = 'Catifes';
        $form['proveedores[Telefono]'] = '9265235';
        $form['proveedores[Responsable]'] = 'Rafik';
        $form['proveedores[Direccion]'] = 'C/ Tela, 20 (Toledo)';

        //Mandamos datos
        $crawler = $client->submit($form);

        //Comprobamos si el usuario se ha insertado correctamente
        $Juanma = self::$kernel->getContainer()->get('doctrine')->getRepository(Proveedores::class)->findOneBy(array(
        		'Nombre' => 'Alfombras Juanma',
        		'Correo' => 'ajua@barato.com',
        		'Producto' => 'Catifes',
        		'Telefono' =>  '9265235',
        		'Responsable' =>  'Rafik',
        		'Direccion' =>  'C/ Tela, 20 (Toledo)',
        	));

        //Miramos si la consulta tiene respuesta (insert correcto)
        $this->assertNotNull($Juanma);	
	}

	public function testShow()
    {
    	$client = $this->createAuthorizedClient();
        
	   	//Sacamos el número de usuario del cliente de la prueba anterior
    	$Juanma = self::$kernel->getContainer()->get('doctrine')->getRepository(Proveedores::class)->findOneBy(array(
                'Nombre' => 'Alfombras Juanma',
                'Correo' => 'ajua@barato.com',
                'Producto' => 'Catifes',
                'Telefono' =>  '9265235',
                'Responsable' =>  'Rafik',
                'Direccion' =>  'C/ Tela, 20 (Toledo)',
        	));

    	$numCliente = $Juanma->getId();
		$this->assertNotNull($numCliente);	//El cliente está insertado

		//Lo mostramos
        $crawler = $client->request('GET', '/proveedores/'.$numCliente.'');
        $this->assertEquals(
            200,	//OK
            $client->getResponse()->getStatusCode()
        );

        //PROBAMOS A MOSTRAR UN PROVEEDOR QUE NO EXISTE
        $crawler = $client->request('GET', '/proveedores/a');
        $this->assertEquals(
            404,    //No lo encuentra
            $client->getResponse()->getStatusCode()
        );

	}

	public function testEdit()
    {
    	$client = $this->createAuthorizedClient();
        
	   	//Sacamos el número de usuario del cliente de las pruebas anteriores
    	$Juanma = self::$kernel->getContainer()->get('doctrine')->getRepository(Proveedores::class)->findOneBy(array(
                'Nombre' => 'Alfombras Juanma',
                'Correo' => 'ajua@barato.com',
                'Producto' => 'Catifes',
                'Telefono' =>  '9265235',
                'Responsable' =>  'Rafik',
                'Direccion' =>  'C/ Tela, 20 (Toledo)',
            ));

    	$numCliente = $Juanma->getId();
		$this->assertNotNull($numCliente);	//El cliente está insertado

		//Vamos a la página de edición
        $crawler = $client->request('GET', '/proveedores/'.$numCliente.'/edit');
        $this->assertEquals(
            200,	//OK
            $client->getResponse()->getStatusCode()
        );	

        //Editamos datos
        $form = $crawler->selectButton('Actualizar')->form();
        $form['proveedores[Nombre]'] = 'Catifes Josep';
        $form['proveedores[Correo]'] = 'cajo@volen.com';
        $form['proveedores[Producto]'] = 'catifes voladores';
        $form['proveedores[Telefono]'] =  '7523138';
        $form['proveedores[Direccion]'] = 'C/ Sahara, 20 (Andorra la Vella)';
        $form['proveedores[Responsable]'] = 'Sebas';

        //Mandamos datos
        $crawler = $client->submit($form);

        //Comprobamos si el usuario se ha insertado correctamente
        $Josep = self::$kernel->getContainer()->get('doctrine')->getRepository(Proveedores::class)->findOneBy(array(
        		'Nombre' => 'Catifes Josep',
        		'Correo' => 'cajo@volen.com',
        		'Producto' => 'catifes voladores',
        		'Telefono' =>  '7523138',
        		'Direccion' =>  'C/ Sahara, 20 (Andorra la Vella)',
        		'Responsable' =>  'Sebas',
        	));

        //Miramos si la consulta tiene respuesta
        $this->assertNotNull($Josep);	

        //PROBAMOS A EDITAR UN PROVEEDOR QUE NO EXISTE
        $crawler = $client->request('GET', '/proveedores/a/edit');
        $this->assertEquals(
            404,    //No lo encuentra
            $client->getResponse()->getStatusCode()
        );
	}

	public function testDelete()
    {
    	$client = $this->createAuthorizedClient();
        
	   	//Sacamos el número de usuario del cliente de la prueba anterior
    	$Josep = self::$kernel->getContainer()->get('doctrine')->getRepository(Proveedores::class)->findOneBy(array(
                'Nombre' => 'Catifes Josep',
                'Correo' => 'cajo@volen.com',
                'Producto' => 'catifes voladores',
                'Telefono' =>  '7523138',
                'Direccion' =>  'C/ Sahara, 20 (Andorra la Vella)',
                'Responsable' =>  'Sebas',
            ));

    	$numCliente = $Josep->getId();
		$this->assertNotNull($numCliente);	//El cliente está insertado

		//Lo borramos
        $crawler = $client->request('GET', '/proveedores/'.$numCliente.'');
        $form = $crawler->selectButton('Eliminar')->form();
        $crawler = $client->submit($form);

        //Volvemos a buscarlo y vemos que ya no está
        $Josep = self::$kernel->getContainer()->get('doctrine')->getRepository(Proveedores::class)->findOneBy(array(
                'Nombre' => 'Catifes Josep',
                'Correo' => 'cajo@volen.com',
                'Producto' => 'catifes voladores',
                'Telefono' =>  '7523138',
                'Direccion' =>  'C/ Sahara, 20 (Andorra la Vella)',
                'Responsable' =>  'Sebas',
            ));

		$this->assertNull($Josep);
	}

    //Probamos a crear usuarios con campos incorrectos
    public function testNewIncorrecto()
    {
        //1 - Nombre vacío

        //Usuario tester autorizado
        $client1 = $this->createAuthorizedClient();
        $crawler1 = $client1->request('GET', '/proveedores/new');
        $this->assertEquals(
            200,    //OK
            $client1->getResponse()->getStatusCode()
        );  

        //Una vez dentro, pruebas sobre formulario
        //TEST NEW USER - Valor incorrecto
        $form1 = $crawler1->selectButton('Guardar')->form();
        $form1['proveedores[Nombre]'] = '';
        $form1['proveedores[Correo]'] = 'ajua@barato.com';
        $form1['proveedores[Producto]'] = 'Catifes';
        $form1['proveedores[Telefono]'] = '9265235';
        $form1['proveedores[Responsable]'] = 'Rafik';
        $form1['proveedores[Direccion]'] = 'C/ Tela, 20 (Toledo)';

        //Mandamos datos
        $crawler1 = $client1->submit($form1);

        //Comprobamos si el usuario se ha insertado correctamente
        $Juanma1 = self::$kernel->getContainer()->get('doctrine')->getRepository(Proveedores::class)->findOneBy(array(
                'Nombre' => '',
                'Correo' => 'ajua@barato.com',
                'Producto' => 'Catifes',
                'Telefono' =>  '9265235',
                'Responsable' =>  'Rafik',
                'Direccion' =>  'C/ Tela, 20 (Toledo)',
            ));

        //Miramos si la consulta tiene respuesta (insert correcto)
        $this->assertNull($Juanma1);    


        //2 - Correo vacío

        //Usuario tester autorizado
        $client2 = $this->createAuthorizedClient();
        $crawler2 = $client2->request('GET', '/proveedores/new');
        $this->assertEquals(
            200,    //OK
            $client2->getResponse()->getStatusCode()
        );  

        //Una vez dentro, pruebas sobre formulario
        //TEST NEW USER - Valor incorrecto
        $form2 = $crawler2->selectButton('Guardar')->form();
        $form2['proveedores[Nombre]'] = 'Alfombras Juanma';
        $form2['proveedores[Correo]'] = '';
        $form2['proveedores[Producto]'] = 'Catifes';
        $form2['proveedores[Telefono]'] = '9265235';
        $form2['proveedores[Responsable]'] = 'Rafik';
        $form2['proveedores[Direccion]'] = 'C/ Tela, 20 (Toledo)';

        //Mandamos datos
        $crawler2 = $client2->submit($form2);

        //Comprobamos si el usuario se ha insertado correctamente
        $Juanma2 = self::$kernel->getContainer()->get('doctrine')->getRepository(Proveedores::class)->findOneBy(array(
                'Nombre' => 'Alfombras Juanma',
                'Correo' => '',
                'Producto' => 'Catifes',
                'Telefono' =>  '9265235',
                'Responsable' =>  'Rafik',
                'Direccion' =>  'C/ Tela, 20 (Toledo)',
            ));

        //Miramos si la consulta tiene respuesta (insert correcto)
        $this->assertNull($Juanma2);  

        //3 - Producto vacío

        //Usuario tester autorizado
        $client3 = $this->createAuthorizedClient();
        $crawler3 = $client3->request('GET', '/proveedores/new');
        $this->assertEquals(
            200,    //OK
            $client3->getResponse()->getStatusCode()
        );  

        //Una vez dentro, pruebas sobre formulario
        //TEST NEW USER - Valor incorrecto
        $form3 = $crawler3->selectButton('Guardar')->form();
        $form3['proveedores[Nombre]'] = 'Alfombras Juanma';
        $form3['proveedores[Correo]'] = 'ajua@barato.com';
        $form3['proveedores[Producto]'] = '';
        $form3['proveedores[Telefono]'] = '9265235';
        $form3['proveedores[Responsable]'] = 'Rafik';
        $form3['proveedores[Direccion]'] = 'C/ Tela, 20 (Toledo)';

        //Mandamos datos
        $crawler3 = $client3->submit($form3);

        //Comprobamos si el usuario se ha insertado correctamente
        $Juanma3 = self::$kernel->getContainer()->get('doctrine')->getRepository(Proveedores::class)->findOneBy(array(
                'Nombre' => 'Alfombras Juanma',
                'Correo' => 'ajua@barato.com',
                'Producto' => '',
                'Telefono' =>  '9265235',
                'Responsable' =>  'Rafik',
                'Direccion' =>  'C/ Tela, 20 (Toledo)',
            ));

        //Miramos si la consulta tiene respuesta (insert correcto)
        $this->assertNull($Juanma3);   

        //4 - Telefono vacío

        //Usuario tester autorizado
        $client4 = $this->createAuthorizedClient();
        $crawler4 = $client4->request('GET', '/proveedores/new');
        $this->assertEquals(
            200,    //OK
            $client4->getResponse()->getStatusCode()
        );  

        //Una vez dentro, pruebas sobre formulario
        //TEST NEW USER - Valor incorrecto
        $form4 = $crawler4->selectButton('Guardar')->form();
        $form4['proveedores[Nombre]'] = 'Alfombras Juanma';
        $form4['proveedores[Correo]'] = 'ajua@barato.com';
        $form4['proveedores[Producto]'] = 'Catifes';
        $form4['proveedores[Telefono]'] = '';
        $form4['proveedores[Responsable]'] = 'Rafik';
        $form4['proveedores[Direccion]'] = 'C/ Tela, 20 (Toledo)';

        //Mandamos datos
        $crawler4 = $client4->submit($form4);

        //Comprobamos si el usuario se ha insertado correctamente
        $Juanma4 = self::$kernel->getContainer()->get('doctrine')->getRepository(Proveedores::class)->findOneBy(array(
                'Nombre' => 'Alfombras Juanma',
                'Correo' => 'ajua@barato.com',
                'Producto' => 'Catifes',
                'Telefono' =>  '',
                'Responsable' =>  'Rafik',
                'Direccion' =>  'C/ Tela, 20 (Toledo)',
            ));

        //Miramos si la consulta tiene respuesta (insert correcto)
        $this->assertNull($Juanma4);   

        //5 - Direccion vacia

        //Usuario tester autorizado
        $client5 = $this->createAuthorizedClient();
        $crawler5 = $client5->request('GET', '/proveedores/new');
        $this->assertEquals(
            200,    //OK
            $client5->getResponse()->getStatusCode()
        );  

        //Una vez dentro, pruebas sobre formulario
        //TEST NEW USER - Valor incorrecto
        $form5 = $crawler5->selectButton('Guardar')->form();
        $form5['proveedores[Nombre]'] = 'Alfombras Juanma';
        $form5['proveedores[Correo]'] = 'ajua@barato.com';
        $form5['proveedores[Producto]'] = 'Catifes';
        $form5['proveedores[Telefono]'] = '9265235';
        $form5['proveedores[Responsable]'] = 'Rafik';
        $form5['proveedores[Direccion]'] = '';

        //Mandamos datos
        $crawler5 = $client5->submit($form5);

        //Comprobamos si el usuario se ha insertado correctamente
        $Juanma5 = self::$kernel->getContainer()->get('doctrine')->getRepository(Proveedores::class)->findOneBy(array(
                'Nombre' => 'Alfombras Juanma',
                'Correo' => 'ajua@barato.com',
                'Producto' => 'Catifes',
                'Telefono' =>  '9265235',
                'Responsable' =>  'Rafik',
                'Direccion' =>  '',
            ));

        //Miramos si la consulta tiene respuesta (insert correcto)
        $this->assertNull($Juanma5);  

        //6 - Responsable vacio

        //Usuario tester autorizado
        $client6 = $this->createAuthorizedClient();
        $crawler6 = $client6->request('GET', '/proveedores/new');
        $this->assertEquals(
            200,    //OK
            $client6->getResponse()->getStatusCode()
        );  

        //Una vez dentro, pruebas sobre formulario
        //TEST NEW USER - Valor incorrecto
        $form6 = $crawler6->selectButton('Guardar')->form();
        $form6['proveedores[Nombre]'] = 'Alfombras Juanma';
        $form6['proveedores[Correo]'] = 'ajua@barato.com';
        $form6['proveedores[Producto]'] = 'Catifes';
        $form6['proveedores[Telefono]'] = '9265235';
        $form6['proveedores[Responsable]'] = '';
        $form6['proveedores[Direccion]'] = 'C/ Tela, 20 (Toledo)';

        //Mandamos datos
        $crawler6 = $client6->submit($form6);

        //Comprobamos si el usuario se ha insertado correctamente
        $Juanma6 = self::$kernel->getContainer()->get('doctrine')->getRepository(Proveedores::class)->findOneBy(array(
                'Nombre' => 'Alfombras Juanma',
                'Correo' => 'ajua@barato.com',
                'Producto' => 'Catifes',
                'Telefono' =>  '9265235',
                'Responsable' =>  '',
                'Direccion' =>  'C/ Tela, 20 (Toledo)',
            ));

        //Miramos si la consulta tiene respuesta (insert correcto)
        $this->assertNull($Juanma6);  
    }
}