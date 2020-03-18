<?php
namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use App\Entity\Tickets;
use App\Entity\Usuario;


class TicketsControllerTest extends WebTestCase
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

    public function testIndex(){

        //Testeamos que al hacer una request al indice nos devuelva una pagina
        $client00 = $this->createAuthorizedClient();
        $crawler00 = $client00->request('GET', '/tickets/');
        $this->assertNotTrue($client00->getResponse()->isNotFound());
        $this->assertNotEquals(
            404,
            $client00->getResponse()->getStatusCode()
        );		

    }

    public function testNew(){

        //Testeamos la request con el método GET
        $client1 = $this->createAuthorizedClient();
        $crawler = $client1->request('GET', '/tickets/new');
        $this->assertEquals(
            200,
            $client1->getResponse()->getStatusCode()
        );
        
        $this->assertNotTrue($client1->getResponse()->isNotFound());

        //Testeamos la request con el método POST
        $client11 = $this->createAuthorizedClient();
        $crawler = $client11->request('POST', '/tickets/new');
        $this->assertEquals(
            200,
            $client11->getResponse()->getStatusCode()
        );
        
        $this->assertNotTrue($client11->getResponse()->isNotFound());

        //Testeamos si guarda un ticket nuevo

        $client22 = $this->createAuthorizedClient();
        $crawler22 = $client22->request('GET', '/tickets/new');
        $buttonCrawlerNode = $crawler22->selectButton('Guardar');
        $form = $buttonCrawlerNode->form();
 
        //Asignamos valores al INSERT
        $form = $buttonCrawlerNode->form([
           'tickets[Departamento]'    => '1',
           'tickets[TipoIncidente]' => 'Leve',
           'tickets[Dispositivo]' => 'ordenador',
           'tickets[Explicacion]' => 'No funciona',
        ]);
        $client22->submit($form);

        //Recogemos la id del tickets
        
        $tickets22 = self::$kernel->getContainer()->get('doctrine')->getRepository(
            Tickets::class)->findOneBy(array(
                
            'Departamento' => '1',
            'TipoIncidente' => 'Leve',
            'Dispositivo' => 'ordenador',
            'Explicacion' => 'No funciona',
                
            ));

        $id_tickets22 = $tickets22->getId();
        $this->assertNotNull($id_tickets22);

        $para22 = $crawler22->filter('h1')->first()->text();
        $this->assertEquals("Generar nueva incidencia",$para22);

    }


    public function testShow(){

        //Insertamos el ticket que queremos mostrar

        $client2 = $this->createAuthorizedClient();
        $crawler2 = $client2->request('GET', '/tickets/new');
        $buttonCrawlerNode = $crawler2->selectButton('Guardar');
        $form = $buttonCrawlerNode->form();
 
        //Asignamos valores al INSERT
        $form = $buttonCrawlerNode->form([
           'tickets[Departamento]'    => '1',
           'tickets[TipoIncidente]' => 'Mediano',
           'tickets[Dispositivo]' => 'telefono',
           'tickets[Explicacion]' => 'No funciona',
        ]);
        $client2->submit($form);

        $tickets20 = self::$kernel->getContainer()->get('doctrine')->getRepository(
            Tickets::class)->findOneBy(array(
                
            'Departamento' => '1',
            'TipoIncidente' => 'Mediano',
            'Dispositivo' => 'telefono',
            'Explicacion' => 'No funciona',
                
            ));

        $id_tickets20 = $tickets20->getId();
        $this->assertNotNull($id_tickets20);

        //Testeamos la request con el método GET con parámetros correctos
        $client20 = $this->createAuthorizedClient();
        $crawler = $client20->request('GET', "/tickets/$id_tickets20");
        $this->assertEquals(
            200,
            $client20->getResponse()->getStatusCode()
        );
        $this->assertNotTrue($client20->getResponse()->isNotFound());

        //Probamos a pasar parámetros incorrectos

        //Le pasamos un string
        $client21 = $this->createAuthorizedClient();
        $crawler = $client21->request('GET', '/tickets/error');
        $this->assertEquals(
            404,
            $client21->getResponse()->getStatusCode()
        );
        $this->assertTrue($client21->getResponse()->isNotFound());

        //Le pasamos un id inexistente
        $client22 = $this->createAuthorizedClient();
        $crawler = $client22->request('GET', '/tickets/123957219');
        $this->assertEquals(
            404,
            $client22->getResponse()->getStatusCode()
        );
        $this->assertTrue($client22->getResponse()->isNotFound());

        //Le pasamos unos carácteres extraños
        $client23 = $this->createAuthorizedClient();
        $crawler = $client23->request('GET', '/tickets/€¬·$%&&');
        $this->assertEquals(
            404,
            $client23->getResponse()->getStatusCode()
        );
        $this->assertTrue($client23->getResponse()->isNotFound());
    }


    public function testTicketsResolved(){

        //Testeamos la request con el método GET con parámetros correctos
        $client40 = $this->createAuthorizedClient();
        $crawler = $client40->request('GET', '/tickets../ticketsResolved');
        $this->assertEquals(
            200,
            $client40->getResponse()->getStatusCode()
        );
        $this->assertNotTrue($client40->getResponse()->isNotFound());

        
    }

    public function testTicketsNotResolved(){

        //Testeamos la request con el método GET con parámetros correctos
        $client50 = $this->createAuthorizedClient();
        $crawler = $client50->request('GET', '/tickets../ticketsNotResolved');
        $this->assertNotTrue($client50->getResponse()->isNotFound());

        
    }

    

    public function testTicketsByUser(){

        //Testeamos la request con el método GET con parámetros correctos
        $client51 = $this->createAuthorizedClient();
        $crawler = $client51->request('GET', '/tickets../ticketsbyUser');
        $this->assertNotTrue($client51->getResponse()->isNotFound());

        
    }


    public function testResolve(){

        //Creamos el ticket que queremos resolver
        $client58 = $this->createAuthorizedClient();
        $crawler58 = $client58->request('GET', '/tickets/new');
        $buttonCrawlerNode = $crawler58->selectButton('Guardar');
        $form = $buttonCrawlerNode->form();
 
        //Asignamos valores al INSERT
        $form = $buttonCrawlerNode->form([
           'tickets[Departamento]'    => '1',
           'tickets[TipoIncidente]' => 'Alta',
           'tickets[Dispositivo]' => 'router',
           'tickets[Explicacion]' => 'No funciona',
        ]);
        $client58->submit($form);

        //Buscamos el ticket creado anteriormente
        $tickets52 = self::$kernel->getContainer()->get('doctrine')->getRepository(
            Tickets::class)->findOneBy(array(
                
            'Departamento' => '1',
            'TipoIncidente' => 'Alta',
            'Dispositivo' => 'router',
            'Explicacion' => 'No funciona',
                
            ));

        $id_ticket52 = $tickets52->getId();
        
        $this->assertNotNull($id_ticket52);


        //Intentamos resolver el ticket creado anteriormente

        $client53 = $this->createAuthorizedClient();
        $crawler53 = $client53->request('GET', "/tickets/$id_ticket52/resolve");
        $buttonCrawlerNode = $crawler53->selectButton('Resolver el ticket');
        $form = $buttonCrawlerNode->form();
 
        //Asignamos valores al INSERT
        $form = $buttonCrawlerNode->form([
           'tickets_resolved[TipoIncidente]' => 'Alta',
           'tickets_resolved[Solucion]' => 'Reiniciar router',
           
        ]);
        $client53->submit($form);

        $para53 = $crawler53->filter('h1')->first()->text();
        $this->assertEquals("Resolver Ticket Info",$para53);


        //Ahora buscamos este mismo ticket para comprobar que esta resuelto
        
        $ticket53 = self::$kernel->getContainer()->get('doctrine')->getRepository(
            Tickets::class)->findOneBy(array(
                'Departamento' => '1',
                'TipoIncidente' => 'Alta',
                'Dispositivo' => 'router',
                'Explicacion' => 'No funciona',
                
            ));

        $id_ticket53 = $ticket53->getSolved();
        
        $this->assertEquals("si", $id_ticket53);


    }


    public function testDelete(){

        //Creamos el ticket que queremos resolver
        $client6 = $this->createAuthorizedClient();
        $crawler6 = $client6->request('GET', '/tickets/new');
        $buttonCrawlerNode = $crawler6->selectButton('Guardar');
        $form = $buttonCrawlerNode->form();
 
        //Asignamos valores al INSERT
        $form = $buttonCrawlerNode->form([
           'tickets[Departamento]'    => '1',
           'tickets[TipoIncidente]' => 'Leve',
           'tickets[Dispositivo]' => 'impresora',
           'tickets[Explicacion]' => 'No funciona',
        ]);
        $client6->submit($form);

        //Buscamos el ticket resuelto anteriormente
        $ticket60 = self::$kernel->getContainer()->get('doctrine')->getRepository(
            Tickets::class)->findOneBy(array(
                'Departamento' => '1',
                'TipoIncidente' => 'Leve',
                
            ));

        $id_ticket60 = $ticket60->getId();
        
        $this->assertNotNull($id_ticket60);

        //Eliminamos el ticket que hemos buscado
        
        $client61 = $this->createAuthorizedClient();
        $crawler61 = $client61->request('GET', "/tickets/$id_ticket60");
        $buttonCrawlerNode = $crawler61->selectButton('Eliminar');
        $form61 = $buttonCrawlerNode->form();
        
        $client61->submit($form61);

        //Finalmente intentamos mostrar la informacion del ticket eliminado.
        $client62 = $this->createAuthorizedClient();
        $crawler62 = $client62->request('GET', "/tickets/$id_ticket60");
        $this->assertEquals(
            404,
            $client62->getResponse()->getStatusCode()
        );
        $this->assertTrue($client62->getResponse()->isNotFound());
    }

}
