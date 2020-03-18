<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use App\Entity\Usuario;
use App\Entity\Booking;
use DateTime;

class BookingControllerTest extends WebTestCase
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
        //caso con usuario registrado con accesos
        $client = $this->createAuthorizedClient();
        $departamento = $client->request('GET', '/booking/');

        //ver si se recibe bien la información (mensaje 200 - OK)
        $this->assertEquals(200, $client->getResponse()->getStatusCode());	
        
        //ver si lo pedido 
        $this->assertSame('Booking index', $departamento->filter('title')->text());
        $this->assertSame('Evento de la empresa', $departamento->filter('h1')->text());
        
        
        //Usuario sin registrar
        $clientNoAuth = static::createClient();
        $clientNoAuth->request('GET', '/booking/');
        //redireccion a main
        $this->assertEquals(302, $clientNoAuth->getResponse()->getStatusCode());
    }

    public function testCalendar()
    {
        //usuario con los permisos bien
        $client = $this->createAuthorizedClient();
        $crawler = $client->request('GET', '/booking/calendar');
        //ver si se recibe bien la información (mensaje 200 - OK)
        $this->assertEquals(200, $client->getResponse()->getStatusCode());	
        $this->assertSame('Pagina principal', $crawler->filter('title')->text());

        //Usuario sin registrar
        $clientNoAuth = static::createClient();
        $crawler2 = $clientNoAuth->request('GET', '/booking/calendar/');
        //redireccion a main
        $this->assertEquals(302, $clientNoAuth->getResponse()->getStatusCode());
    }

    public function testNew()
    {
        //caso de nuevo evento con todo bien
        $cliente = $this->createAuthorizedClient();
        $crawler = $cliente->request('GET', '/booking/new');
        $form = $crawler->selectButton('Guardar')->form();
                
        $form['booking[beginAt][date][year]']->select('2019');
        $form['booking[beginAt][date][month]']->select('6');
        $form['booking[beginAt][date][day]']->select('25');
        $form['booking[beginAt][time][hour]']->select('15');
        $form['booking[beginAt][time][minute]']->select('30');

        $form['booking[endAt][date][year]']->select('2019');
        $form['booking[endAt][date][month]']->select('6');
        $form['booking[endAt][date][day]']->select('25');
        $form['booking[endAt][time][hour]']->select('18');
        $form['booking[endAt][time][minute]']->select('30');
        $form['booking[title]'] = 'Trabajos';
        
        $crawler = $cliente->submit($form);

        //Redirección a la página principal de eventos después de haber agregado uno nuevo
        $this->assertTrue($cliente->getResponse()->isRedirect());    

        
        //Creamos los objetos DateTime que serán los que usaremos para ver si se agregó bien el nuevo evento
        $date1 = '2019-06-25 15:30:00';
        $fecha_DateTime = DateTime::createFromFormat('Y-m-d H:i:s', $date1);

        $date2 = '2019-06-25 18:30:00';
        $fecha_DateTime2 = DateTime::createFromFormat('Y-m-d H:i:s', $date2);

        //hacemos una consulta a la base de datos para constatar que se agregó esa fila
        $booking = self::$kernel->getContainer()->get('doctrine')->getRepository( 
            Booking::class)->findOneBy(array( 
            'beginAt' => $fecha_DateTime, 
            'endAt' => $fecha_DateTime2, 
            'title' => 'Trabajos',                  
            )); 
 
        //Cogemos la id de esa fila del repositorio Booking
        $booking = $booking->getId(); 

        //existe por lo menos uno con estas características
        $this->assertNotNull($booking);  

        //intento de insert con campos mal (puestos en null algunos que no lo pueden ser)
        $crawler = $cliente->request('GET', '/booking/new');
        $form = $crawler->selectButton('Guardar')->form();
                
        $form['booking[beginAt][date][year]']->select('2019');
        $form['booking[beginAt][date][month]']->select('6');
        $form['booking[beginAt][date][day]']->select('25');
        $form['booking[beginAt][time][hour]']->select('15');
        $form['booking[beginAt][time][minute]']->select('30');

        $form['booking[endAt][date][year]']->select('2019');
        $form['booking[endAt][date][month]']->select('6');
        $form['booking[endAt][date][day]']->select('25');
        $form['booking[endAt][time][hour]']->select('18');
        $form['booking[endAt][time][minute]']->select('30');
        $form['booking[title]'] = NULL;
        
        $crawler = $cliente->submit($form);
        
        //No se redirecciona por lo que no se insertó en el formulario información válida
        $this->assertFalse($cliente->getResponse()->isRedirect());    
    }

    public function testShow()
    {
        $client = $this->createAuthorizedClient();
        //ver si se recibe bien la información (mensaje 200 - OK)

        //fechas que buscaremos en la base de datos
        $date1 = '2019-06-25 15:30:00';
        $fecha_DateTime = DateTime::createFromFormat('Y-m-d H:i:s', $date1);

        $date2 = '2019-06-25 18:30:00';
        $fecha_DateTime2 = DateTime::createFromFormat('Y-m-d H:i:s', $date2);

        //consulta a la base de datos para recuperar el evento que queremos mostrar
        $booking = self::$kernel->getContainer()->get('doctrine')->getRepository( 
            Booking::class)->findOneBy(array( 
            'beginAt' => $fecha_DateTime, 
            'endAt' => $fecha_DateTime2, 
            'title' => 'Trabajos',                  
            )); 

        $booking = $booking->getId(); 

        $crawler = $client -> request('GET', "/booking/$booking");
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        //ver si lo pedido se muestra
        $this->assertSame('Booking', $crawler->filter('title')->text());
        $this->assertSame('Evento Trabajos', $crawler->filter('h1')->text());
        $this->assertEquals('Trabajos', $crawler->filter('td:contains("Trabajos")')->text());
   
        //caso del pedido de ver un evento del que su id no existe (es decir, no existe y no se puede mostrar)
        $crawler = $client->request('GET', '/booking/1246548788kjgjgrk');
        $this->assertEquals(404, $client->getResponse()->getStatusCode()
        );
    }

    public function testEdit()
    {
        $client = $this->createAuthorizedClient();

        $date1 = '2019-06-25 15:30:00';
        $fecha_DateTime = DateTime::createFromFormat('Y-m-d H:i:s', $date1);

        $date2 = '2019-06-25 18:30:00';
        $fecha_DateTime2 = DateTime::createFromFormat('Y-m-d H:i:s', $date2);

        //hacemos una consulta a la base de datos para recuperar la id de ese evento
        $booking = self::$kernel->getContainer()->get('doctrine')->getRepository( 
            Booking::class)->findOneBy(array( 
            'beginAt' => $fecha_DateTime, 
            'endAt' => $fecha_DateTime2, 
            'title' => 'Trabajos',                  
            )); 

        $booking = $booking->getId(); 

        $crawler = $client -> request('GET', "/booking/$booking/edit");
        $form = $crawler->selectButton('Editar')->form();
        $form['booking[beginAt][date][year]']->select('2019');
        $form['booking[beginAt][date][month]']->select('6');
        $form['booking[beginAt][date][day]']->select('24');
        $form['booking[beginAt][time][hour]']->select('15');
        $form['booking[beginAt][time][minute]']->select('30');

        $form['booking[endAt][date][year]']->select('2019');
        $form['booking[endAt][date][month]']->select('6');
        $form['booking[endAt][date][day]']->select('28');
        $form['booking[endAt][time][hour]']->select('18');
        $form['booking[endAt][time][minute]']->select('30');
        $form['booking[title]'] = 'NewBooking';
        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();

        //consulta a la base de datos para ver si ahora los datos están cambiados
        //Creamos los objetos DateTime que serán los que usaremos para ver si se editó bien el nuevo evento
        $date1 = '2019-06-24 15:30:00';
        $fecha_DateTime = DateTime::createFromFormat('Y-m-d H:i:s', $date1);

        $date2 = '2019-06-28 18:30:00';
        $fecha_DateTime2 = DateTime::createFromFormat('Y-m-d H:i:s', $date2);

        //hacemos una consulta a la base de datos para constatar que se agregó esa fila
        $booking = self::$kernel->getContainer()->get('doctrine')->getRepository( 
            Booking::class)->findOneBy(array( 
            'beginAt' => $fecha_DateTime, 
            'endAt' => $fecha_DateTime2, 
            'title' => 'NewBooking',                  
            )); 
    
        //Cogemos la id de esa fila del repositorio Booking
        $booking = $booking->getId(); 

        //existe por lo menos uno con estas características
        $this->assertNotNull($booking);  

        //evento que no existe (id inexistente)
        $crawler = $client->request('GET', '/booking/1246548788gjdaghau/edit');

        //no encuentra el archivo porque no existe
        $this->assertEquals(404, $client->getResponse()->getStatusCode());
    }

    public function testDelete()
    {
        $client = $this->createAuthorizedClient();

        //usamos uno de los eventos agregados antes (NewBooking)
        $book1 = self::$kernel->getContainer()->get('doctrine')->getRepository('App\Entity\Booking')->findBy(array(
            'title' => 'NewBooking'
            ));
        $id1 = $book1[0]->getId();
        //eliminacion de uno de los que tenga ese título (el total de eventos con ese nombre baja)
        $crawler = $client->request('GET', "/booking/$id1/edit");
        $form = $crawler->selectButton('Eliminar')->form();
        $crawler = $client->submit($form);
        $this->assertTrue($client->getResponse()->isRedirect());
        $crawler = $client->followRedirect();
        $book2 = self::$kernel->getContainer()->get('doctrine')->getRepository('App\Entity\Booking')->findBy(array(
            'title' => 'NewBooking'
            ));

        //Comparación de las arrays de los departamentos que tienen el título NewBooking
        //en el primer caso, hay los que hay al principio, en el segundo hay uno menos porque se eliminó
        //un elemento con ese título
        $this->assertTrue($book1 > $book2);

        //evento que no existe
        $crawler = $client->request('GET', '/booking/1246548788gfgf/edit');
        //no encuentra el archivo porque no existe esa id
        $this->assertEquals(404, $client->getResponse()->getStatusCode()
        );
    }
}