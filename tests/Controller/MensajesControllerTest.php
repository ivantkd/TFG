<?php

namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use App\Entity\Mensajes;
use App\Entity\Usuario;
use Doctrine\Common\Collections\ArrayCollection;

class MensajesControllerTest extends WebTestCase
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
        $clientNoAuth->request('GET', '/mensajes/');
        $this->assertEquals(
            500,    //Prohibibdo
            $clientNoAuth->getResponse()->getStatusCode()
        );
        $this->assertNotEquals(
            200,    //Prohibibdo
            $clientNoAuth->getResponse()->getStatusCode()
        );

        //Usuario tester autorizado
        $client = $this->createAuthorizedClient();
        $crawler = $client->request('GET', '/mensajes/');
        $this->assertEquals(
            200,    //OK
            $client->getResponse()->getStatusCode()
        );  
    }

    public function testNew()
    {
        //Usuario tester autorizado
        $client = $this->createAuthorizedClient();
        $crawler = $client->request('GET', '/mensajes/new');
        $this->assertEquals(
            200,    //OK
            $client->getResponse()->getStatusCode()
        );  

        //Una vez dentro, pruebas sobre formulario
        $form = $crawler->selectButton('Crear mensaje')->form();
        $form['mensajes[asunto]'] = 'Mensaje de prueba';
        $form['mensajes[cuerpo]'] = 'Cuerpo del mensaje de prueba';

        //Mandamos datos
        $crawler = $client->submit($form);

        //Comprobamos si el mensaje se ha insertado correctamente
        $mensaje = self::$kernel->getContainer()->get('doctrine')->getRepository(mensajes::class)->findOneBy(array(
                'asunto' => 'Mensaje de prueba',
                'cuerpo' => 'Cuerpo del mensaje de prueba',
            ));

        //Miramos si la consulta tiene respuesta
        $this->assertNotNull($mensaje);   
    }

    public function testShow()
    {
        //Usuario autorizado
        $client = $this->createAuthorizedClient();
        
        //Sacamos el número id del mensaje de la prueba anterior
        $mensaje = self::$kernel->getContainer()->get('doctrine')->getRepository(mensajes::class)->findOneBy(array(
                'asunto' => 'Mensaje de prueba',
                'cuerpo' => 'Cuerpo del mensaje de prueba',
            ));

        $idMensaje = $mensaje->getId();
        $this->assertNotNull($idMensaje);  //El mensaje está insertado

        //Lo mostramos
        $crawler = $client->request('GET', '/mensajes/'.$idMensaje.'');
        $this->assertEquals(
            200,    //OK
            $client->getResponse()->getStatusCode()
        );  

        //PROBAMOS A MOSTRAR UN MENSAJE QUE NO EXISTE
        $crawler = $client->request('GET', '/mensajes/a');
        $this->assertEquals(
            404,    //No lo encuentra
            $client->getResponse()->getStatusCode()
        );

    }

    public function testDelete()
    {
        $client = $this->createAuthorizedClient();
        
        //Sacamos el número id del mensaje de la prueba anterior
        $mensaje = self::$kernel->getContainer()->get('doctrine')->getRepository(mensajes::class)->findOneBy(array(
                'asunto' => 'Mensaje de prueba',
                'cuerpo' => 'Cuerpo del mensaje de prueba',
            ));

        $idMensaje = $mensaje->getId();
        $this->assertNotNull($idMensaje);  //El mensaje está insertado

        
        //Lo borramos
        $crawler = $client->request('GET', '/mensajes/'.$idMensaje.'');
        $form = $crawler->selectButton('Delete')->form();
        $crawler = $client->submit($form);

        //Volvemos a buscarlo y vemos que ya no está
         $mensaje = self::$kernel->getContainer()->get('doctrine')->getRepository(mensajes::class)->findOneBy(array(
                'id' => $idMensaje,
                'asunto' => 'Mensaje de prueba',
                'cuerpo' => 'Cuerpo del mensaje de prueba',
            ));

        $this->assertNull($mensaje);
        
    }
}