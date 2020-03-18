<?php

namespace App\tests\Entity;

use App\Entity\Booking;
use App\Entity\Usuario;
use PHPUnit\Framework\TestCase;

class BookingTest extends TestCase{


    public function testId()
    {
        $booking00 = new Booking();        
        $result00 = $booking00->getId();
        $this->assertNull($result00);
    }

    public function testBeginAt(){  
        $date10=date_create("2019-05-19");
        $booking10 = new Booking();
        $booking10->setBeginAt($date10);
        $result10 = $booking10->getBeginAt();

        $this->assertNotNull($result10);
        $this->assertEquals($date10,$result10);
        $this->assertNotEquals("",$result10);
        $this->assertTrue(is_object($result10));

        $date11=date_create("2019-15-56");
        $this->assertFalse($date11);
        //$date11 devuelve false si se introduce una fecha incorrecta
        //El metodo setBeginAt detecta esto y no setea el valor de BeginAt.
        

        //date_create crea un objeto DateTimeInterface si se utiliza "/" en lugar de "-"
        $date12=date_create("2019/05/26");
        $booking12 = new Booking();
        $booking12->setBeginAt($date12);
        $result12 = $booking12->getBeginAt();

        $this->assertNotNull($result12);
        $this->assertEquals($date12,$result12);
        $this->assertNotEquals("",$result12);
        $this->assertTrue(is_object($result12));

        $date13=date_create("2019/cinco/26");
        $this->assertFalse($date13);
        //$date13 devuelve false si se introduce una fecha con letras
        //El metodo setBeginAt detecta esto y no setea el valor de BeginAt.

    }

    public function testMailUsuario(){

        $booking20 = new Booking();
        $usuario20 = new Usuario();
        $usuario20->setMail("softlogy@gmail.com");
        $booking20->setmail_usuario($usuario20);
        $result20 = $booking20->getmail_usuario();

        $this->assertNotNull($result20);
        $this->assertequals($usuario20,$result20);
        $this->assertNotEquals(36,$result20);
        $this->assertTrue(is_object($result20));

        $booking21 = new Booking();
        $usuario21 = new Usuario();
        $usuario21->setMail("");
        $booking21->setmail_usuario($usuario21);
        $result21 = $booking21->getmail_usuario();

        $this->assertNotNull($result21);
        $this->assertequals($usuario21,$result21);
        $this->assertNotEquals(36,$result21);
        $this->assertTrue(is_object($result21));

        $booking22 = new Booking();
        $usuario22 = new Usuario();
        $usuario22->setMail("@|#€¬¬.com");
        $booking22->setmail_usuario($usuario22);
        $result22 = $booking22->getmail_usuario();

        $this->assertNotNull($result22);
        $this->assertequals($usuario22,$result22);
        $this->assertNotEquals(36,$result22);
        $this->assertTrue(is_object($result22));
        
        

    }

    public function testEndAt(){
        $date30=date_create("2019-05-19");
        $booking30 = new Booking();
        $booking30->setEndAt($date30);
        $result30 = $booking30->getEndAt();

        $this->assertNotNull($result30);
        $this->assertEquals($date30,$result30);
        $this->assertNotEquals("",$result30);
        $this->assertTrue(is_object($result30));

        $date31=date_create("2019-15-56");
        $this->assertFalse($date31);
        //$date31 devuelve false si se introduce una fecha incorrecta
        //El metodo setEndAt detecta esto y no setea el valor de BeginAt.
        

        //date_create crea un objeto DateTimeInterface si se utiliza "/" en lugar de "-"
        $date32=date_create("2019/05/26");
        $booking32 = new Booking();
        $booking32->setEndAt($date32);
        $result32 = $booking32->getEndAt();

        $this->assertNotNull($result32);
        $this->assertEquals($date32,$result32);
        $this->assertNotEquals("",$result32);
        $this->assertTrue(is_object($result32));

        $date33=date_create("2019/cinco/26");
        $this->assertFalse($date33);
        //$date33 devuelve false si se introduce una fecha con letras
        //El metodo setEndAt detecta esto y no setea el valor de BeginAt.

    }

    public function tesTittle(){

        $booking40 = new Booking();
        $booking40->setTitle("reserva1");
        $result40 = $booking40->getTitle();

        $this->assertNotNull($result40);
        $this->assertequals("reserva1",$result40);
        $this->assertNotEquals("",$result40);
        $this->assertTrue(is_string($result40));


        $booking41 = new Booking();
        $booking41->setTitle("·($/·)$=%(·/·$");
        $result41 = $booking41->getTitle();

        $this->assertNotNull($result41);
        $this->assertequals("·($/·)$=%(·/·$",$result41);
        $this->assertNotEquals("",$result41);
        $this->assertTrue(is_string($result41));

        $booking42 = new Booking();
        $booking42->setTitle("");
        $result42 = $booking42->getTitle();

        $this->assertNotNull($result42);
        $this->assertequals("",$result42);
        $this->assertTrue(is_string($result42));



    }
}