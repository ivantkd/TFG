<?php
namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use App\Entity\Usuario;
use App\Entity\Persona;




class UsuarioControllerTest extends WebTestCase
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
        //Testeamos que al hacer una request al indice nos devuelva una pagina
        $client00 = $this->createAuthorizedClient();
        $crawler00 = $client00->request('GET', '/usuario/');
        $this->assertNotTrue($client00->getResponse()->isNotFound());
        $this->assertEquals(
            200,
            $client00->getResponse()->getStatusCode()
        );		
        /*
        Testeamos que al hacer una request al indice nos devuelva la pagina
        de listado de usuario
        */
        $crawler00 = $client00->request("GET","/usuario/");
        $para1 = $crawler00->filter('h1')->first()->text();
        $this->assertEquals("Lista de usuarios",$para1);
    }

    public function testPerfil(){

        //Testeamos que, al pasarle un mail valido, nos redirige a la pagina de informacion de ese usuario
        $client10 = $this->createAuthorizedClient();
        $crawler10 = $client10->request('GET', '/usuario/test@test.test');
        $para10 = $crawler10->filter('img')->first()->text();
        $this->assertEquals("",$para10);
        $this->assertEquals(
            200,
            $client10->getResponse()->getStatusCode()
        );		

        //Testeamos que, al pasarle un mail no valido, nos redirige a la pagina de informacion de ese usuario
        $client11 = $this->createAuthorizedClient();
        $crawler11 = $client11->request('GET', '/usuario/mail@falso');
        $para11 = $crawler11->filter('span')->first()->text();
        $this->assertNotEquals("Total tickets resueltos",$para11);
        $this->assertNotEquals(
            200,
            $client11->getResponse()->getStatusCode()
        );		

    }

    public function testNew(){

        //Testeamos la request con el método GET
        $client20 = $this->createAuthorizedClient();
        $crawler20 = $client20->request('GET', '/usuario../new');
        $this->assertEquals(
            200,
            $client20->getResponse()->getStatusCode()
        );
        
        $this->assertNotTrue($client20->getResponse()->isNotFound());

        //Testeamos la request con el método POST
        $client21 = $this->createAuthorizedClient();
        $crawler21 = $client21->request('POST', '/usuario../new');
        $this->assertEquals(
            200,
            $client21->getResponse()->getStatusCode()
        );
        
        $this->assertNotTrue($client21->getResponse()->isNotFound());


        //Testeamos si guarda el usuario nuevo

        //Primero creamos la persona que utilizara el usuario
        $client22 = $this->createAuthorizedClient();
        $crawler22 = $client22->request('GET', '/persona/new');
        $buttonCrawlerNode = $crawler22->selectButton('Guardar');
        $form = $buttonCrawlerNode->form();

        //Asignamos valores al INSERT
        $form = $buttonCrawlerNode->form([
            'persona[Nombre]'    => 'NewUsuario',
            'persona[Apellidos]' => 'NewUsuario',
            'persona[Cargo]' => 'New',
            'persona[Correo]' => 'testNew@test.test',
            'persona[Telefono]' => '123',
            'persona[Local_id]' => '1',
            'persona[Departamento]' => '1',
        ]);
        
        $client22->submit($form);
        //Recogemos la id del empleado
       
        $persona22 = self::$kernel->getContainer()->get('doctrine')->getRepository(
            Persona::class)->findOneBy(array(
            'Nombre'    => "NewUsuario",
            'Apellidos' => 'NewUsuario',
            'Cargo' => 'New',
            'Correo' => 'testNew@test.test',
            'Telefono' => '123',
            'Local_id' => '1',
            'Departamento' => '1',
            ));
        $nEmpe22 = $persona22->getNumero_Empleado();
        $this->assertNotNull($nEmpe22);
        $this->assertNotNull($persona22);
        //$this->assertEquals("",$nEmpe22);

        $client23 = $this->createAuthorizedClient();
        $crawler23 = $client23->request('GET', '/usuario../new');
        $buttonCrawlerNode = $crawler23->selectButton('Crear');
        $form23 = $buttonCrawlerNode->form();

        //Asignamos valores al INSERT
        
        $form23 = $buttonCrawlerNode->form([
            'usuario[mail]'    => "testNew@test.test",
            'usuario[Password]' => 'test',
            'usuario[Nombre]' => "NewUsuario",
            'usuario[Apellidos]' => "NewUsuario",
            'usuario[numeroEmpleado]' => $nEmpe22,
            'usuario[UserRole]' => "ROLE_ADMIN",
            'usuario[imagen]' => '13c48b7d09d3f9f7f62d9006b4cf06a1.png',
        ]);
        
        $client23->submit($form23);

        $usuario23 = self::$kernel->getContainer()->get('doctrine')->getRepository(
            Usuario::class)->findOneBy(array(
            'mail'    => "testNew@test.test",
            
            ));

            
        
    }

    public function testShow(){

        //Testeamos la request con el método GET con parámetros correctos
        $client30 = $this->createAuthorizedClient();
        $crawler30 = $client30->request('GET', '/usuario/test@test.test');
        $this->assertEquals(
            200,
            $client30->getResponse()->getStatusCode()
        );
        $this->assertNotTrue($client30->getResponse()->isNotFound());

        //Probamos a pasar parámetros incorrectos

        //Le pasamos un string
        $client31 = $this->createAuthorizedClient();
        $crawler31 = $client31->request('GET', '/usuario/ajsbnfiouanbweu');
        $this->assertEquals(
            404,
            $client31->getResponse()->getStatusCode()
        );
        $this->assertTrue($client31->getResponse()->isNotFound());

        //Le pasamos un id inexistente
        $client32 = $this->createAuthorizedClient();
        $crawler = $client32->request('GET', '/usuario/124');
        $this->assertEquals(
            404,
            $client32->getResponse()->getStatusCode()
        );
        $this->assertTrue($client32->getResponse()->isNotFound());

        //Le pasamos unos carácteres extraños
        $client33 = $this->createAuthorizedClient();
        $crawler = $client33->request('GET', '/usuario/€¬·$%&&');
        $this->assertEquals(
            404,
            $client33->getResponse()->getStatusCode()
        );
        $this->assertTrue($client33->getResponse()->isNotFound());

    }

    public function testEdit(){

        //Testeamos si guarda el usuario nuevo

        //Primero creamos la persona que utilizara el usuario
        $client42 = $this->createAuthorizedClient();
        $crawler42 = $client42->request('GET', '/persona/new');
        $buttonCrawlerNode = $crawler42->selectButton('Guardar');
        $form = $buttonCrawlerNode->form();

        //Asignamos valores al INSERT
        $form = $buttonCrawlerNode->form([
            'persona[Nombre]'    => 'prueba',
            'persona[Apellidos]' => 'EditUsuario',
            'persona[Cargo]' => 'test',
            'persona[Correo]' => 'test@test.test',
            'persona[Telefono]' => '123',
            'persona[Local_id]' => '1',
            'persona[Departamento]' => '1',
        ]);
        
        $client42->submit($form);
        //Recogemos la id del empleado
        $persona42 = self::$kernel->getContainer()->get('doctrine')->getRepository(
            Persona::class)->findOneBy(array(
            'Nombre'    => 'prueba',
            'Apellidos' => 'EditUsuario',
            'Cargo' => 'test',
            'Correo' => 'test@test.test',
            'Telefono' => '123',
            'Local_id' => '1',
            'Departamento' => '1',
            ));
        $nEmpe42 = $persona42->getNumero_Empleado();
        $this->assertNotNull($nEmpe42);
        $this->assertNotNull($persona42);

        $crawler42 = $client42->request('GET', '/usuario../new');
        $buttonCrawlerNode = $crawler42->selectButton('Crear');
        $form = $buttonCrawlerNode->form();

        //Asignamos valores al INSERT
        $mail_usuario42="test@test.test";
        $form = $buttonCrawlerNode->form([
            'usuario[mail]'    => "$mail_usuario42",
            'usuario[Password]' => '$2y$12$nOcJF17hfLrLQWmw/bVffePdIghEUahQp4WDx2N/wHPhB/3JzJ8Zq',
            'usuario[Nombre]' => "test",
            'usuario[Apellidos]' => "prueba",
            'usuario[numeroEmpleado]' => $nEmpe42,
            'usuario[UserRole]' => "ROLE_ADMIN",
            'usuario[imagen]' => '13c48b7d09d3f9f7f62d9006b4cf06a1.png',
        ]);
        
        $client42->submit($form);

        //Creamos otro cliente que edite el usuario introducido anteriormente
        //La edicion cumple las condiciones isSubmitted() y isValid() (True-True)
        $client43 = $this->createAuthorizedClient();
        $crawler43 = $client43->request('GET', "/usuario/test@test.test/edit");
        $buttonCrawlerNode = $crawler43->selectButton('Actualizar');
        $form43 = $buttonCrawlerNode->form();

        //Asignamos valores al EDIT
        $form43 = $buttonCrawlerNode->form([
            'usuario[mail]'    => "test@test.test",
            'usuario[Password]' => '$2y$12$nOcJF17hfLrLQWmw/bVffePdIghEUahQp4WDx2N/wHPhB/3JzJ8Zq',
            'usuario[Nombre]' => "test",
            'usuario[Apellidos]' => "prueba editada",
            'usuario[numeroEmpleado]' => $nEmpe42,
            'usuario[UserRole]' => "ROLE_ADMIN",
            'usuario[imagen]' => '13c48b7d09d3f9f7f62d9006b4cf06a1.png',
        ]);
        
        $client43->submit($form43);


        //Comprobamos que nos redirige al formulario para insertar un usuario

        $para43 = $crawler43->filter('h1')->first()->text();
        //$this->assertEquals("Create new Usuario",$para43);

        

        
    }

    public function testDelete(){

        //Primero creamos la persona que utilizara el usuario
        $client50 = $this->createAuthorizedClient();
        $crawler50 = $client50->request('GET', '/persona/new');
        $buttonCrawlerNode = $crawler50->selectButton('Guardar');
        $form = $buttonCrawlerNode->form();

        //Asignamos valores al INSERT
        $form = $buttonCrawlerNode->form([
            'persona[Nombre]'    => 'test@test.test',
            'persona[Apellidos]' => 'DeleteUsuario',
            'persona[Cargo]' => 'test',
            'persona[Correo]' => 'testDelete@test.test',
            'persona[Telefono]' => '123',
            'persona[Local_id]' => '1',
            'persona[Departamento]' => '1',
        ]);
        
        
        $client50->submit($form);
        //Recogemos la id del empleado
        $persona50 = self::$kernel->getContainer()->get('doctrine')->getRepository(
            Persona::class)->findOneBy(array(
            
                'Nombre'    => 'test@test.test',
                'Apellidos' => 'DeleteUsuario',
                'Cargo' => 'test',
                'Correo' => 'testDelete@test.test',
                'Telefono' => '123',
                'Local_id' => '1',
                'Departamento' => '1',
            
            ));
        $nEmpe50 = $persona50->getNumero_Empleado();
        $this->assertNotNull($nEmpe50);
        //$this->assertEquals("test",$persona50);


        $usuario = self::$kernel->getContainer()->get('doctrine')->getRepository(Usuario::class)->findOneBy(array(
            'mail' => 'testDelete@test.test',
            
        ));

        //$this->assertEquals("test",$usuario);
        //Creamos otro cliente que elimine el usuario introducido anteriormente
        
        $client52 = $this->createAuthorizedClient();
        $crawler52 = $client52->request('GET', "/usuario/testDelete@test.test/edit");
        $buttonCrawlerNode = $crawler52->selectButton('Delete');
        $form = $buttonCrawlerNode->form();
        
        $client52->submit($form);

        //Con otro cliente intentamos mostrar la informacion del usuario eliminado
        
        $crawler52 = $client52->request('GET', "/usuario/testDelete@test.test/show");
        $this->assertEquals(
            404,
            $client52->getResponse()->getStatusCode()
        );
        $this->assertTrue($client52->getResponse()->isNotFound());

        
    }
    
}