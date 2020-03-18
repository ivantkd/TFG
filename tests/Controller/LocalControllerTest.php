<?php
namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use App\Entity\Usuario;
use App\Entity\Local;


class LocalControllerTest extends WebTestCase
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
        $crawler00 = $client00->request('GET', '/localdepartamento/');
        $this->assertNotTrue($client00->getResponse()->isNotFound());
        $this->assertEquals(
            200,
            $client00->getResponse()->getStatusCode()
        );		
        
        /*
        Testeamos que al hacer una request al indice nos devuelva la pagina
        de listado de local
        */
        $client01 = $this->createAuthorizedClient();
        $crawler01 = $client01->request('GET','/localdepartamento/');
        $para1 = $crawler01->filter('h3')->first()->text();
        $this->assertEquals("Locales",$para1);

    }


    public function testNew(){

        //Testeamos la request con el método GET
        $client1 = $this->createAuthorizedClient();
        $crawler = $client1->request('GET', '/localdepartamento/new');
        $this->assertEquals(
            200,
            $client1->getResponse()->getStatusCode()
        );
        
        $this->assertNotTrue($client1->getResponse()->isNotFound());

        //Testeamos la request con el método POST
        $crawler = $client1->request('POST', '/localdepartamento/new');
        $this->assertEquals(
            200,
            $client1->getResponse()->getStatusCode()
        );
        
        $this->assertNotTrue($client1->getResponse()->isNotFound());


        //Testeamos si guarda un local nuevo

        $client22 = $this->createAuthorizedClient();
        $crawler22 = $client22->request('GET', 'localdepartamento/new');
        $buttonCrawlerNode = $crawler22->selectButton('Guardar');
        $form = $buttonCrawlerNode->form();

         //Asignamos valores al INSERT
         $form = $buttonCrawlerNode->form([
            'local[Direccion]'    => 'Rambla',
            'local[Poblacion]' => 'Barcelona',
            'local[Correo]' => 'Barcelona@test.com',
            'local[Telefono]' => '1234',
        ]);
        $client22->submit($form);
        //Recogemos la id del local
        
        $local22 = self::$kernel->getContainer()->get('doctrine')->getRepository(
            Local::class)->findOneBy(array(
                
            'Direccion'    => 'Rambla',
            'Poblacion' => 'Barcelona',
            'Correo' => 'Barcelona@test.com',
            'Telefono' => '1234',
                
            ));
        $id_local22 = $local22->getId();
        $this->assertNotNull($id_local22);
        
        //Probamos las condiciones del IF
        /*
        La condicion $form->isSubmitted()siempre sera true, pues al simular
        el presionar el boton para Guardar la entrada en la BD le estamos 
        proporcionando el valor true a esta variable
        */
        
        //Condicion True-False
        $client24 = $this->createAuthorizedClient();
        $crawler24 = $client24->request('GET', '/localdepartamento/new');
        $buttonCrawlerNode = $crawler24->selectButton('Guardar');
        $form = $buttonCrawlerNode->form();

        $form = $buttonCrawlerNode->form([
            'local[Direccion]'    => 'Arc de Triomf',
            'local[Poblacion]' => 'Barcelona',
            'local[Correo]' => 'Barcelona@test.com',
            'local[Telefono]' => '1234',
        ]);

        $client24->submit($form);

        $local24 = self::$kernel->getContainer()->get('doctrine')->getRepository(
            Local::class)->findOneBy(array(
                
            'Direccion'    => 'Arc de Triomf',
            'Poblacion' => 'Barcelona',
            'Correo' => 'Barcelona@test.com',
            'Telefono' => '1234',
                
            ));
        $id_local24 = $local24->getId();
        $this->assertNotNull($id_local24);


        //Comprobamos que nos redirige a la lista de locales

        $para24 = $crawler24->filter('h1')->first()->text();
        $this->assertEquals("Registrar una nueva oficina en la empresa",$para24);

        
        //Condicion True-False con valores NULL
        $client24 = $this->createAuthorizedClient();
        $crawler24 = $client24->request('GET', 'localdepartamento/new');
        $buttonCrawlerNode = $crawler24->selectButton('Guardar');
        $form = $buttonCrawlerNode->form();
 
        $form = $buttonCrawlerNode->form([
            'local[Direccion]'    => 'Arc de Triomf',
            'local[Poblacion]' => NULL,
            'local[Correo]' => 'Barcelona@test.com',
            'local[Telefono]' => '1234',
        ]);
        $client24->submit($form);
 
        $local24 = self::$kernel->getContainer()->get('doctrine')->getRepository(
            Local::class)->findOneBy(array(
                 
            'Direccion'    => 'Arc de Triomf',
            'Poblacion' => NULL,
            'Correo' => 'Barcelona@test.com',
            'Telefono' => '1234',
                 
            ));
        
        $this->assertNull($local24);
 
 
        //Comprobamos que nos redirige a la lista de locales
 
        $para24 = $crawler24->filter('h1')->first()->text();
        $this->assertEquals("Registrar una nueva oficina en la empresa",$para24);


    }


    public function testShow(){

        //Creamos el local a visualizar

        $client50 = $this->createAuthorizedClient();
        $crawler50 = $client50->request('GET', 'localdepartamento/new');
        $buttonCrawlerNode = $crawler50->selectButton('Guardar');
        $form = $buttonCrawlerNode->form();

         //Asignamos valores al INSERT
         $form = $buttonCrawlerNode->form([
            'local[Direccion]'    => 'Rambla',
            'local[Poblacion]' => 'Barcelona',
            'local[Correo]' => 'Barcelona@test.com',
            'local[Telefono]' => '1234',
        ]);
        $client50->submit($form);

        //Recogemos la id del local
        
        $local51 = self::$kernel->getContainer()->get('doctrine')->getRepository(
            Local::class)->findOneBy(array(
                
            'Direccion'    => 'Rambla',
            'Poblacion' => 'Barcelona',
            'Correo' => 'Barcelona@test.com',
            'Telefono' => '1234',
                
            ));
        $id_local51 = $local51->getId();
        $this->assertNotNull($id_local51);

        //Testeamos la request con el método GET con parámetros correctos
        $client20 = $this->createAuthorizedClient();
        $crawler = $client20->request('GET', "/localdepartamento/$id_local51");
        $this->assertEquals(
            200,
            $client20->getResponse()->getStatusCode()
        );
        $this->assertNotTrue($client20->getResponse()->isNotFound());

        //Probamos a pasar parámetros incorrectos

        //Le pasamos un string
        $client21 = $this->createAuthorizedClient();
        $crawler = $client21->request('GET', '/localdepartamento/error');
        $this->assertEquals(
            404,
            $client21->getResponse()->getStatusCode()
        );
        $this->assertTrue($client21->getResponse()->isNotFound());

        //Le pasamos un id inexistente
        $client22 = $this->createAuthorizedClient();
        $crawler = $client22->request('GET', '/localdepartamento/123957219');
        $this->assertEquals(
            404,
            $client22->getResponse()->getStatusCode()
        );
        $this->assertTrue($client22->getResponse()->isNotFound());

        //Le pasamos unos carácteres extraños
        $client23 = $this->createAuthorizedClient();
        $crawler = $client23->request('GET', '/localdepartamento/€¬·$%&&');
        $this->assertEquals(
            404,
            $client23->getResponse()->getStatusCode()
        );
        $this->assertTrue($client23->getResponse()->isNotFound());




    }
    
    public function testEdit(){

        //Insertamos un nuevo local

        $client24 = $this->createAuthorizedClient();
        $crawler24 = $client24->request('GET', 'localdepartamento/new');
        $buttonCrawlerNode = $crawler24->selectButton('Guardar');
        $form = $buttonCrawlerNode->form();

        //Asignamos valores al INSERT

        $form = $buttonCrawlerNode->form([
            'local[Direccion]'    => 'Gran Via',
            'local[Poblacion]' => 'Madrid',
            'local[Correo]' => 'Madrid@test.com',
            'local[Telefono]' => '1234',
        ]);

        $client24->submit($form);

        //Testeamos si se ha introducido correctamente

        $local24 = self::$kernel->getContainer()->get('doctrine')->getRepository(
            Local::class)->findOneBy(array(
            'Direccion'    => 'Gran Via',
            'Poblacion' => 'Madrid',
            'Correo' => 'Madrid@test.com',
            'Telefono' => '1234',
                
            ));

        $id_local24 = $local24->getId();
        
        $this->assertNotNull($id_local24);

        //Creamos otro cliente que edite el local introducido anteriormente
        //La edicion cumple las condiciones isSubmitted() y isValid() (True-True)
        $client43 = $this->createAuthorizedClient();
        $crawler43 = $client43->request('GET', "/localdepartamento/$id_local24/edit");
        $buttonCrawlerNode = $crawler43->selectButton('Actualizar');
        $form43 = $buttonCrawlerNode->form();

        //Asignamos valores al EDIT
        $form43 = $buttonCrawlerNode->form([
            'local[Direccion]'    => 'Gran Via editado',
            'local[Poblacion]' => 'Madrid',
            'local[Correo]' => 'Madrid@test.com',
            'local[Telefono]' => '1234',
        ]);
        
        $client43->submit($form43);
    }

    public function testDelete(){

        //Creamos el local a eliminar

        $client50 = $this->createAuthorizedClient();
        $crawler50 = $client50->request('GET', 'localdepartamento/new');
        $buttonCrawlerNode = $crawler50->selectButton('Guardar');
        $form = $buttonCrawlerNode->form();

         //Asignamos valores al INSERT
         $form = $buttonCrawlerNode->form([
            'local[Direccion]'    => 'Rambla',
            'local[Poblacion]' => 'Barcelona',
            'local[Correo]' => 'Delete@test.com',
            'local[Telefono]' => '1234',
        ]);
        $client50->submit($form);

        //Recogemos la id del local
        
        $local51 = self::$kernel->getContainer()->get('doctrine')->getRepository(
            Local::class)->findOneBy(array(
                
            'Direccion'    => 'Rambla',
            'Poblacion' => 'Barcelona',
            'Correo' => 'Delete@test.com',
            'Telefono' => '1234',
                
            ));
        $id_local51 = $local51->getId();
        $this->assertNotNull($id_local51);

        //Eliminamos el local que hemos buscado
        
        $client52 = $this->createAuthorizedClient();
        $crawler52 = $client52->request("DELETE", "/localdepartamento/$id_local51");
        
        $this->assertNotEquals(
            200,
            $client52->getResponse()->getStatusCode()
        );

    }

}
