<?php

namespace App\tests\Entity;

use App\Entity\Mensajes;
use App\Entity\Usuario;
use PHPUnit\Framework\TestCase;

class MensajesTest extends TestCase{

     /* NO HACEMOS TEST DE ID PORQUE NO PODEMOS HACER SET DE ESA ENTITY */

    public function testId()
    {
        $mensajes00 = new Mensajes();        
        $result00 = $mensajes00->getId();
        $this->assertNull($result00);
    }

    public function testMailUsuario(){

        $mensajes10 = new Mensajes();
        $usuario10 = new Usuario();
        $usuario10->setMail("softlogy@gmail.com");
        $mensajes10->setmailusuario($usuario10);
        $result10 = $mensajes10->getmailusuario();

        $this->assertNotNull($result10);
        $this->assertequals($usuario10,$result10);
        $this->assertNotEquals(36,$result10);
        $this->assertTrue(is_object($result10));

        $mensajes11 = new Mensajes();
        $usuario11 = new Usuario();
        $usuario11->setMail("");
        $mensajes11->setmailusuario($usuario11);
        $result11 = $mensajes11->getmailUsuario();

        $this->assertNotNull($result11);
        $this->assertequals($usuario11,$result11);
        $this->assertNotEquals(36,$result11);
        $this->assertTrue(is_object($result11));

        $mensajes12 = new Mensajes();
        $usuario12 = new Usuario();
        $usuario12->setMail("@|#€¬¬.com");
        $mensajes12->setmailusuario($usuario12);
        $result12 = $mensajes12->getmailUsuario();

        $this->assertNotNull($result12);
        $this->assertequals($usuario12,$result12);
        $this->assertNotEquals(36,$result12);
        $this->assertTrue(is_object($result12));

    }

    public function testCuerpo(){

        $mensajes20 = new Mensajes();
        $mensajes20->setCuerpo("cuerpo1");
        $result20 = $mensajes20->getCuerpo();

        $this->assertNotNull($result20);
        $this->assertequals("cuerpo1",$result20);
        $this->assertNotEquals("",$result20);
        $this->assertTrue(is_string($result20));

        $mensajes21 = new Mensajes();
        $mensajes21->setCuerpo("");
        $result21 = $mensajes21->getCuerpo();

        $this->assertNotNull($result21);
        $this->assertequals("",$result21);
        $this->assertTrue(is_string($result21));

        $mensajes22 = new Mensajes();
        $mensajes22->setCuerpo("@|!?¿");
        $result22 = $mensajes22->getCuerpo();

        $this->assertNotNull($result22);
        $this->assertequals("@|!?¿",$result22);
        $this->assertNotEquals("",$result22);
        $this->assertTrue(is_string($result22));
        
    }

    public function testAsunto(){

        $mensajes30 = new Mensajes();
        $mensajes30->setAsunto("asunto1");
        $result30 = $mensajes30->getAsunto();

        $this->assertNotNull($result30);
        $this->assertequals("asunto1",$result30);
        $this->assertNotEquals("",$result30);
        $this->assertTrue(is_string($result30));

        $mensajes31 = new Mensajes();
        $mensajes31->setAsunto("");
        $result31 = $mensajes31->getAsunto();

        $this->assertNotNull($result31);
        $this->assertEquals("",$result31);
        $this->assertTrue(is_string($result31));

        $mensajes32 = new Mensajes();
        $mensajes32->setAsunto("@|!?¿");
        $result32 = $mensajes32->getAsunto();

        $this->assertNotNull($result32);
        $this->assertequals("@|!?¿",$result32);
        $this->assertNotEquals("",$result32);
        $this->assertTrue(is_string($result32));

    }

    public function testFecha(){


        $date40=date_create("2019-05-19");
        $mensajes40 = new Mensajes();
        $mensajes40->setFecha($date40);
        $result40 = $mensajes40->getFecha();

        $this->assertNotNull($result40);
        $this->assertEquals($date40,$result40);
        $this->assertNotEquals("",$result40);
        $this->assertTrue(is_object($result40));

        $date41=date_create("2019-15-56");
        $this->assertFalse($date41);
        //$date41 devuelve false si se introduce una fecha incorrecta
        //El metodo setFecha detecta esto y no setea el valor de Fecha.
        

        //date_create crea un objeto DateTimeInterface si se utiliza "/" en lugar de "-"
        $date42=date_create("2019/05/26");
        $booking42 = new Mensajes();
        $booking42->setFecha($date42);
        $result42 = $booking42->getFecha();

        $this->assertNotNull($result42);
        $this->assertEquals($date42,$result42);
        $this->assertNotEquals("",$result42);
        $this->assertTrue(is_object($result42));

        $date43=date_create("2019/cinco/26");
        $this->assertFalse($date43);
        //$date43 devuelve false si se introduce una fecha con letras
        //El metodo setFecha detecta esto y no setea el valor de Fecha.
    }
}