<?php

namespace App\Tests\Entity;

use App\Entity\Local;
use PHPUnit\Framework\TestCase;

class LocalTest extends TestCase
{

    /*
    //getId no se testea porque es el índice incremental de la base de datos
    //
    */

    public function testDireccion()
    {
        //valor normal
        $local = new Local();
        $direccion = $local->setDireccion("Calle Mallorca 4 2");
        $testDireccion = "Calle Mallorca 4 2";
        $getDireccion = $local->getDireccion();
        $this->assertEquals($testDireccion, $getDireccion);
        
        //valores frontera
        $local = new Local();
        $direccion = $local->setDireccion("Gran via de les corts catalanes 111 anexo 3 escalera A piso 3 puerta 2 Barcelona España 08045 Europa");
        $testDireccion = "Gran via de les corts catalanes 111 anexo 3 escalera A piso 3 puerta 2 Barcelona España 08045 Europa";
        $getDireccion = $local->getDireccion();
        $this->assertEquals($testDireccion, $getDireccion);

        $local = new Local();
        $direccion = $local->setDireccion("");
        $testDireccion = "";
        $getDireccion = $local->getDireccion();
        $this->assertEquals($testDireccion, $getDireccion);


        //valor exterior a frontera
        $local = new Local();
        $direccion = $local->setDireccion("Gran via de las cortes catalanas 111 anexo 3 escalera A piso 3 puerta 2 Barcelona España 08045 Europa");
        $testDireccion = "Gran via de las cortes catalanas 111 anexo 3 escalera A piso 3 puerta 2 Barcelona España 08045 Europa";
        $getDireccion = $local->getDireccion();
        $this->assertEquals($testDireccion, $getDireccion);

        //valor interior a frontera
        $local = new Local();
        $direccion = $local->setDireccion("Gran via de les corts catalanes 111 anexo 3 escalera A piso 3 puerta 2 Barcelona España 08045 Europ");
        $testDireccion = "Gran via de les corts catalanes 111 anexo 3 escalera A piso 3 puerta 2 Barcelona España 08045 Europ";
        $getDireccion = $local->getDireccion();
        $this->assertEquals($testDireccion, $getDireccion);

        $local = new Local();
        $direccion = $local->setDireccion("5");
        $testDireccion = "5";
        $getDireccion = $local->getDireccion();
        $this->assertEquals($testDireccion, $getDireccion);
    }  

    public function testPoblacion()
    {
        //Valor normal
        $local = new Local();
        $poblacion = $local->setPoblacion("Barcelona");
        $testPoblacion = "Barcelona";
        $getPoblacion = $local->getPoblacion();
        $this->assertEquals($testPoblacion, $getPoblacion);

        //valores frontera
        $local = new Local();
        $poblacion = $local->setPoblacion("PoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacion1");
        $testPoblacion = "PoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacion1";
        $getPoblacion = $local->getPoblacion();
        $this->assertEquals($testPoblacion, $getPoblacion);

        $local = new Local();
        $poblacion = $local->setPoblacion("");
        $testPoblacion = "";
        $getPoblacion = $local->getPoblacion();
        $this->assertEquals($testPoblacion, $getPoblacion);


        //Valor exterior a frontera
        $local = new Local();
        $poblacion = $local->setPoblacion("PoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacion122");
        $testPoblacion = "PoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacion122";
        $getPoblacion = $local->getPoblacion();
        $this->assertEquals($testPoblacion, $getPoblacion);

        //Valor interior a frontera
        $local = new Local();
        $poblacion = $local->setPoblacion("PoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacion");
        $testPoblacion = "PoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacionPoblacion";
        $getPoblacion = $local->getPoblacion();
        $this->assertEquals($testPoblacion, $getPoblacion);

        $local = new Local();
        $poblacion = $local->setPoblacion("3");
        $testPoblacion = "3";
        $getPoblacion = $local->getPoblacion();
        $this->assertEquals($testPoblacion, $getPoblacion);
    }

    public function testCorreo()
    {
        //valor normal
        $local = new Local();
        $correo = $local->setCorreo("hola@gmail.com");
        $testEmail = "hola@gmail.com";
        $getCorreo = $local->getCorreo();
        $this->assertEquals($testEmail, $getCorreo);

        //valores frontera
        $local = new Local();
        $correo = $local->setCorreo("holagklsjgsrjglsrjgkleskglsekkgelsglesgf@gmail.comholagklsjgsrjglsrjgkleskglsekkgelsglesgf@gmail.com");
        $testEmail = "holagklsjgsrjglsrjgkleskglsekkgelsglesgf@gmail.comholagklsjgsrjglsrjgkleskglsekkgelsglesgf@gmail.com";
        $getCorreo = $local->getCorreo();
        $this->assertEquals($testEmail, $getCorreo);

        $local = new Local();
        $correo = $local->setCorreo("");
        $testEmail = "";
        $getCorreo = $local->getCorreo();
        $this->assertEquals($testEmail, $getCorreo);

        $local = new Local();
        $correo = $local->setCorreo(NULL);
        $testEmail = NULL;
        $getCorreo = $local->getCorreo();
        $this->assertEquals($testEmail, $getCorreo);


        //valor exterior a frontera
        $local = new Local();
        $correo = $local->setCorreo("holagklsjgsrjglsrjgkleskglsekkgelsglesgf@gmail.comholagklsjgsrjglsrjgkleskglsekkgelsglesgf@gmail.com11");
        $testEmail = "holagklsjgsrjglsrjgkleskglsekkgelsglesgf@gmail.comholagklsjgsrjglsrjgkleskglsekkgelsglesgf@gmail.com11";
        $getCorreo = $local->getCorreo();
        $this->assertEquals($testEmail, $getCorreo);        

        //valor interior a frontera
        $local = new Local();
        $correo = $local->setCorreo("holagklsjgsrjglsrjgkleskglsekkgelsglesgf@gmail.comholagklsjgsrjglsrjgkleskglsekkgelsglesg@gmail.com");
        $testEmail = "holagklsjgsrjglsrjgkleskglsekkgelsglesgf@gmail.comholagklsjgsrjglsrjgkleskglsekkgelsglesg@gmail.com";
        $getCorreo = $local->getCorreo();
        $this->assertEquals($testEmail, $getCorreo);

        $local = new Local();
        $correo = $local->setCorreo("a");
        $testEmail = "a";
        $getCorreo = $local->getCorreo();
        $this->assertEquals($testEmail, $getCorreo);
    }

    public function testTelefono()
    {
        //Valor normal
        $local = new Local();
        $telefono = $local->setTelefono("645156852");
        $testTelefono = "645156852";
        $getTelefono = $local->getTelefono();
        $this->assertEquals($testTelefono, $getTelefono);

        //Valores frontera
        $local = new Local();
        $telefono = $local->setTelefono("12345678985485215874771234567898548521587477123456");
        $testTelefono = "12345678985485215874771234567898548521587477123456";
        $getTelefono = $local->getTelefono();
        $this->assertEquals($testTelefono, $getTelefono);

        $local = new Local();
        $telefono = $local->setTelefono("");
        $testTelefono = "";
        $getTelefono = $local->getTelefono();
        $this->assertEquals($testTelefono, $getTelefono);

        $local = new Local();
        $telefono = $local->setTelefono(NULL);
        $testTelefono = NULL;
        $getTelefono = $local->getTelefono();
        $this->assertEquals($testTelefono, $getTelefono);


        //valor exterior a frontera
        $local = new Local();
        $telefono = $local->setTelefono("1234567898548521587477123456789854852158747712345622");
        $testTelefono = "1234567898548521587477123456789854852158747712345622";
        $getTelefono = $local->getTelefono();
        $this->assertEquals($testTelefono, $getTelefono); 
        
        //valor interior a frontera
        $local = new Local();
        $telefono = $local->setTelefono("1234567898548521587477123456789854852158747712345");
        $testTelefono = "1234567898548521587477123456789854852158747712345";
        $getTelefono = $local->getTelefono();
        $this->assertEquals($testTelefono, $getTelefono);

        $local = new Local();
        $telefono = $local->setTelefono("7");
        $testTelefono = "7";
        $getTelefono = $local->getTelefono();
        $this->assertEquals($testTelefono, $getTelefono);
    }
}