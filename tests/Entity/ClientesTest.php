<?php

namespace App\Tests\Entity;

use App\Entity\Clientes;
use PHPUnit\Framework\TestCase;

class ClientesTest extends TestCase
{
    /*
    //getId no se testea porque es el índice incremental de la base de datos
    //
    */

    public function testNombre()
    {
        //Valor normal
        $clientes = new Clientes();
        $nombre = $clientes->setNombre("Noelia Lopez");
        $testName = "Noelia Lopez";
        $getNombre = $clientes->getNombre();
        $this->assertEquals($testName, $getNombre);

        //valores frontera
        $clientes = new Clientes();
        $nombre = $clientes->setNombre("Nombre super largo hasta los 50 pero bueno, a ver.");
        $testName = "Nombre super largo hasta los 50 pero bueno, a ver.";
        $getNombre = $clientes->getNombre();
        $this->assertEquals($testName, $getNombre);

        $clientes = new Clientes();
        $nombre = $clientes->setNombre("");
        $testName = "";
        $getNombre = $clientes->getNombre();
        $this->assertEquals($testName, $getNombre);


        //valor exterior a frontera
        $clientes = new Clientes();
        $nombre = $clientes->setNombre("Nombre que se pasa del límite, a ver qué tal, je, je.");
        $testName = "Nombre que se pasa del límite, a ver qué tal, je, je.";
        $getNombre = $clientes->getNombre();
        $this->assertEquals($testName, $getNombre);

        //valor interior a frontera
        $clientes = new Clientes();
        $nombre = $clientes->setNombre("Nombre super largo hasta los 50 pero bueno, a ver");
        $testName = "Nombre super largo hasta los 50 pero bueno, a ver";
        $getNombre = $clientes->getNombre();
        $this->assertEquals($testName, $getNombre);

        $clientes = new Clientes();
        $nombre = $clientes->setNombre("a");
        $testName = "a";
        $getNombre = $clientes->getNombre();
        $this->assertEquals($testName, $getNombre);
    }

    public function testCorreo()
    {
        //Valor normal
        $clientes = new Clientes();
        $correo = $clientes->setCorreo("hola@gmail.com");
        $testEmail = "hola@gmail.com";
        $getCorreo = $clientes->getCorreo();
        $this->assertEquals($testEmail, $getCorreo);

        //valores frontera
        $clientes = new Clientes();
        $correo = $clientes->setCorreo("holagklsjgsrjglsrjgkleskglsekkgelsglesgf@gmail.com");
        $testEmail = "holagklsjgsrjglsrjgkleskglsekkgelsglesgf@gmail.com";
        $getCorreo = $clientes->getCorreo();
        $this->assertEquals($testEmail, $getCorreo);

        $clientes = new Clientes();
        $correo = $clientes->setCorreo("");
        $testEmail = "";
        $getCorreo = $clientes->getCorreo();
        $this->assertEquals($testEmail, $getCorreo);


        //Valor exterior a frontera
        $clientes = new Clientes();
        $correo = $clientes->setCorreo("holagklsjgsrjglsrjgkleskglsekkgelsglesgfgg@gmail.com");
        $testEmail = "holagklsjgsrjglsrjgkleskglsekkgelsglesgfgg@gmail.com";
        $getCorreo = $clientes->getCorreo();
        $this->assertEquals($testEmail, $getCorreo);
        
        //valor interior a frontera
        $clientes = new Clientes();
        $correo = $clientes->setCorreo("holagklsjgsrjglsrjgkleskglsekkgelsglesg@gmail.com");
        $testEmail = "holagklsjgsrjglsrjgkleskglsekkgelsglesg@gmail.com";
        $getCorreo = $clientes->getCorreo();
        $this->assertEquals($testEmail, $getCorreo);

        $clientes = new Clientes();
        $correo = $clientes->setCorreo("h");
        $testEmail = "h";
        $getCorreo = $clientes->getCorreo();
        $this->assertEquals($testEmail, $getCorreo);
    }
    
    public function testTelefono()
    {
        //Valor normal
        $clientes = new Clientes();
        $telefono = $clientes->setTelefono("645156852");
        $testTelefono = "645156852";
        $getTelefono = $clientes->getTelefono();
        $this->assertEquals($testTelefono, $getTelefono);

        //valores frontera
        $clientes = new Clientes();
        $telefono = $clientes->setTelefono("12345678985485215874");
        $testTelefono = "12345678985485215874";
        $getTelefono = $clientes->getTelefono();
        $this->assertEquals($testTelefono, $getTelefono);

        $clientes = new Clientes();
        $telefono = $clientes->setTelefono(NULL);
        $testTelefono = NULL;
        $getTelefono = $clientes->getTelefono();
        $this->assertEquals($testTelefono, $getTelefono);

        $clientes = new Clientes();
        $telefono = $clientes->setTelefono("");
        $testTelefono = "";
        $getTelefono = $clientes->getTelefono();
        $this->assertEquals($testTelefono, $getTelefono);


        //Valor exterior a frontera
        $clientes = new Clientes();
        $telefono = $clientes->setTelefono("1234567898548521587477");
        $testTelefono = "1234567898548521587477";
        $getTelefono = $clientes->getTelefono();
        $this->assertEquals($testTelefono, $getTelefono);      

        //valor interior a frontera
        $clientes = new Clientes();
        $telefono = $clientes->setTelefono("1234567898548521587");
        $testTelefono = "1234567898548521587";
        $getTelefono = $clientes->getTelefono();
        $this->assertEquals($testTelefono, $getTelefono);

        $clientes = new Clientes();
        $telefono = $clientes->setTelefono("1");
        $testTelefono = "1";
        $getTelefono = $clientes->getTelefono();
        $this->assertEquals($testTelefono, $getTelefono);
    }

    public function testDireccion()
    {
        //Valor normal
        $clientes = new Clientes();
        $direccion = $clientes->setDireccion("Calle Mallorca 4 2");
        $testDireccion = "Calle Mallorca 4 2";
        $getDireccion = $clientes->getDireccion();
        $this->assertEquals($testDireccion, $getDireccion);
        
        //valores frontera
        $clientes = new Clientes();
        $direccion = $clientes->setDireccion("Gran via de les corts catalanes 111 anexo 3 escalera A piso 3 puerta 2 Barcelona España 08045 Europa");
        $testDireccion = "Gran via de les corts catalanes 111 anexo 3 escalera A piso 3 puerta 2 Barcelona España 08045 Europa";
        $getDireccion = $clientes->getDireccion();
        $this->assertEquals($testDireccion, $getDireccion);

        $clientes = new Clientes();
        $direccion = $clientes->setDireccion("");
        $testDireccion = "";
        $getDireccion = $clientes->getDireccion();
        $this->assertEquals($testDireccion, $getDireccion);

        $clientes = new Clientes();
        $direccion = $clientes->setDireccion(NULL);
        $testDireccion = NULL;
        $getDireccion = $clientes->getDireccion();
        $this->assertEquals($testDireccion, $getDireccion);


        //Valor exterior a frontera
        $clientes = new Clientes();
        $direccion = $clientes->setDireccion("Gran via de las cortes catalanas 111 anexo 3 escalera A piso 3 puerta 2 Barcelona España 08045 Europa");
        $testDireccion = "Gran via de las cortes catalanas 111 anexo 3 escalera A piso 3 puerta 2 Barcelona España 08045 Europa";
        $getDireccion = $clientes->getDireccion();
        $this->assertEquals($testDireccion, $getDireccion);

        //valor interior a frontera
        $clientes = new Clientes();
        $direccion = $clientes->setDireccion("Gran via de les corts catalanes 111 anexo 3 escalera A piso 3 puerta 2 Barcelona España 08045 Europ");
        $testDireccion = "Gran via de les corts catalanes 111 anexo 3 escalera A piso 3 puerta 2 Barcelona España 08045 Europ";
        $getDireccion = $clientes->getDireccion();
        $this->assertEquals($testDireccion, $getDireccion);

        $clientes = new Clientes();
        $direccion = $clientes->setDireccion("3");
        $testDireccion = "3";
        $getDireccion = $clientes->getDireccion();
        $this->assertEquals($testDireccion, $getDireccion);
    } 

    public function testResponsable()
    {
        //Valor normal
        $clientes = new Clientes();
        $responsable = $clientes->setResponsable("Carlos Pérez");
        $testResponsable = "Carlos Pérez";
        $getResponsable = $clientes->getResponsable();
        $this->assertEquals($testResponsable, $getResponsable);
 
        //valores frontera
        $clientes = new Clientes();
        $responsable = $clientes->setResponsable("Eugenio Pancracio López y Lucía Geniosas Rodríguez");
        $testResponsable = "Eugenio Pancracio López y Lucía Geniosas Rodríguez";
        $getResponsable = $clientes->getResponsable();
        $this->assertEquals($testResponsable, $getResponsable);

        $clientes = new Clientes();
        $responsable = $clientes->setResponsable("");
        $testResponsable = "";
        $getResponsable = $clientes->getResponsable();
        $this->assertEquals($testResponsable, $getResponsable);

        $clientes = new Clientes();
        $responsable = $clientes->setResponsable(NULL);
        $testResponsable = NULL;
        $getResponsable = $clientes->getResponsable();
        $this->assertEquals($testResponsable, $getResponsable);


        //Valor exterior a frontera
        $clientes = new Clientes();
        $responsable = $clientes->setResponsable("Eugenio Pancracio López Ibor y Lucía Geniosas Rodríguez");
        $testResponsable = "Eugenio Pancracio López Ibor y Lucía Geniosas Rodríguez";
        $getResponsable = $clientes->getResponsable();
        $this->assertEquals($testResponsable, $getResponsable);

        //valor interior a frontera
        $clientes = new Clientes();
        $responsable = $clientes->setResponsable("Eugenio Pancracio López y Lucía Geniosas Rodrígue");
        $testResponsable = "Eugenio Pancracio López y Lucía Geniosas Rodrígue";
        $getResponsable = $clientes->getResponsable();
        $this->assertEquals($testResponsable, $getResponsable);

        $clientes = new Clientes();
        $responsable = $clientes->setResponsable("g");
        $testResponsable = "g";
        $getResponsable = $clientes->getResponsable();
        $this->assertEquals($testResponsable, $getResponsable);
    }

    public function testServicio()
    {
        //Valor normal
        $clientes = new Clientes();
        $servicio = $clientes->setServicio("Informática");
        $testServicio = "Informática";
        $getServicio = $clientes->getServicio();
        $this->assertEquals($testServicio, $getServicio);
 
        //Valor frontera
        $clientes = new Clientes();
        $servicio = $clientes->setServicio("Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática in");
        $testServicio = "Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática in";
        $getServicio = $clientes->getServicio();
        $this->assertEquals($testServicio, $getServicio);
 
        $clientes = new Clientes();
        $servicio = $clientes->setServicio("");
        $testServicio = "";
        $getServicio = $clientes->getServicio();
        $this->assertEquals($testServicio, $getServicio);

        $clientes = new Clientes();
        $servicio = $clientes->setServicio(NULL);
        $testServicio = NULL;
        $getServicio = $clientes->getServicio();
        $this->assertEquals($testServicio, $getServicio);


        //Valor exterior a frontera
        $clientes = new Clientes();
        $servicio = $clientes->setServicio("Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática inf");
        $testServicio = "Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática inf";
        $getServicio = $clientes->getServicio();
        $this->assertEquals($testServicio, $getServicio);

        //Valor interior a frontera
        $clientes = new Clientes();
        $servicio = $clientes->setServicio("Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática i");
        $testServicio = "Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática Informática i";
        $getServicio = $clientes->getServicio();
        $this->assertEquals($testServicio, $getServicio);
 
        $clientes = new Clientes();
        $servicio = $clientes->setServicio(" ");
        $testServicio = " ";
        $getServicio = $clientes->getServicio();
        $this->assertEquals($testServicio, $getServicio);
    }
}