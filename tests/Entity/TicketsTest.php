<?php

namespace App\tests\Entity;

use App\Entity\Tickets;
use App\Entity\Usuario;
use App\Entity\Departamento;
use PHPUnit\Framework\TestCase;

class TicketsTest extends TestCase{

     /* NO HACEMOS TEST DE ID PORQUE NO PODEMOS HACER SET DE ESA ENTITY */
    public function testId()
    {
        $tickets00 = new Tickets();        
        $result00 = $tickets00->getId();
        $this->assertNull($result00);
    }


    public function testUserMail(){

        $tickets10 = new Tickets();
        $usuario10 = new Usuario();
        $usuario10->setMail("softlogy@gmail.com");
        $tickets10->setUsermail($usuario10);
        $result10 = $tickets10->getUsermail();

        $this->assertNotNull($result10);
        $this->assertEquals($usuario10,$result10);
        $this->assertNotEquals(36,$result10);
        $this->assertTrue(is_object($result10));

        $tickets11 = new Tickets();
        $usuario11 = new Usuario();
        $usuario11->setMail("");
        $tickets11->setUsermail($usuario11);
        $result11 = $tickets11->getUsermail();

        $this->assertNotNull($result11);
        $this->assertequals($usuario11,$result11);
        $this->assertNotEquals(36,$result11);
        $this->assertTrue(is_object($result11));

        $tickets12 = new Tickets();
        $usuario12 = new Usuario();
        $usuario12->setMail("@|#€¬¬.com");
        $tickets12->setUsermail($usuario12);
        $result12 = $tickets12->getUsermail();

        $this->assertNotNull($result12);
        $this->assertequals($usuario12,$result12);
        $this->assertNotEquals(36,$result12);
        $this->assertTrue(is_object($result12));


    }

    public function testDepartamento(){

        $tickets20 = new Tickets();
        $departamento20 = new Departamento();
        $departamento20->setNombre("RRHH");
        $tickets20->setDepartamento($departamento20);
        $result20 = $tickets20->getDepartamento();

        $this->assertNotNull($result20);
        $this->assertEquals($departamento20,$result20);
        $this->assertNotEquals(36,$result20);
        $this->assertTrue(is_object($result20));

        $tickets21 = new Tickets();
        $departamento21 = new Departamento();
        $departamento21->setNombre("");
        $tickets21->setDepartamento($departamento21);
        $result21 = $tickets21->getDepartamento();

        $this->assertNotNull($result21);
        $this->assertEquals($departamento21,$result21);
        $this->assertNotEquals(36,$result21);
        $this->assertTrue(is_object($result21));
    }

    public function testTipoIncidente(){

        $ticket30 = new Tickets();
        $ticket30->setTipoIncidente("grave");
        $result30 = $ticket30->getTipoIncidente();

        $this->assertNotNull($result30);
        $this->assertequals("grave",$result30);
        $this->assertNotEquals("",$result30);
        $this->assertTrue(is_string($result30));

        $ticket31 = new Tickets();
        $ticket31->setTipoIncidente(20);
        $result31 = $ticket31->getTipoIncidente();

        $this->assertNotNull($result31);
        $this->assertequals(20,$result31);
        $this->assertNotEquals("",$result31);
        $this->assertTrue(is_string($result31));
    }

    public function testDispositivo(){
        
        $ticket40 = new Tickets();
        $ticket40->setTipoIncidente("impresora");
        $result40 = $ticket40->getTipoIncidente();

        $this->assertNotNull($result40);
        $this->assertequals("impresora",$result40);
        $this->assertNotEquals("",$result40);
        $this->assertTrue(is_string($result40));

        $ticket41 = new Tickets();
        $ticket41->setTipoIncidente(20);
        $result41 = $ticket41->getTipoIncidente();

        $this->assertNotNull($result41);
        $this->assertequals(20,$result41);
        $this->assertNotEquals("",$result41);
        $this->assertTrue(is_string($result41));
    }

    public function testExplicacion(){

        $ticket50 = new Tickets();
        $ticket50->setExplicacion("impresora atascada");
        $result50 = $ticket50->getExplicacion();

        $this->assertNotNull($result50);
        $this->assertequals("impresora atascada",$result50);
        $this->assertNotEquals("",$result50);
        $this->assertTrue(is_string($result50));

        $ticket51 = new Tickets();
        $ticket51->setExplicacion(20);
        $result51 = $ticket51->getExplicacion();

        $this->assertNotNull($result51);
        $this->assertequals(20,$result51);
        $this->assertNotEquals("",$result51);
        $this->assertTrue(is_string($result51));

    }

    public function testSolved(){


        $ticket60 = new Tickets();
        $ticket60->setSolved("no");
        $result60 = $ticket60->getSolved();

        $this->assertNotNull($result60);
        $this->assertequals("no",$result60);
        $this->assertNotEquals("",$result60);
        $this->assertTrue(is_string($result60));

        $ticket61 = new Tickets();
        $ticket61->setSolved(20);
        $result61 = $ticket61->getSolved();

        $this->assertNotNull($result61);
        $this->assertequals(20,$result61);
        $this->assertNotEquals("",$result61);
        $this->assertTrue(is_string($result61));

    }

    public function testSolucion(){

        $ticket70 = new Tickets();
        $ticket70->setSolucion("Solucion1");
        $result70 = $ticket70->getSolucion();

        $this->assertNotNull($result70);
        $this->assertequals("Solucion1",$result70);
        $this->assertNotEquals("",$result70);
        $this->assertTrue(is_string($result70));

        $ticket70 = new Tickets();
        $ticket70->setSolucion(NULL);
        $result70 = $ticket70->getSolucion();

        $this->assertNull($result70);
        $this->assertequals(NULL,$result70);
        $this->assertEquals("",$result70);
        $this->assertFalse(is_string($result70));
    }

}