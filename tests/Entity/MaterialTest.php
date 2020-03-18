<?php

namespace App\Tests\Entity;

use App\Entity\Material;
use PHPUnit\Framework\TestCase;
use DateTime;

class MaterialTest extends TestCase
{

    public function testNombre()
    {
        //Valor normal
        $material = new Material();
        $nombre = $material->setNombre("Ordenador1");
        $testName = "Ordenador1";
        $getNombre = $material->getNombre();
        $this->assertEquals($testName, $getNombre);

        //valores frontera
        $material = new Material();
        $nombre = $material->setNombre("Nombre de material muy largo, extra large, très longue, huge, hasta 100 caracteres, endless, yeahhhh");
        $testName = "Nombre de material muy largo, extra large, très longue, huge, hasta 100 caracteres, endless, yeahhhh";
        $getNombre = $material->getNombre();
        $this->assertEquals($testName, $getNombre);

        $material = new Material();
        $nombre = $material->setNombre("");
        $testName = "";
        $getNombre = $material->getNombre();
        $this->assertEquals($testName, $getNombre);


        //valor exterior a frontera
        $material = new Material();
        $nombre = $material->setNombre("Nombre de material muy largo, extra large, très longue, huge, hasta 100 caracteres, endless, yeahhhhhhh");
        $testName = "Nombre de material muy largo, extra large, très longue, huge, hasta 100 caracteres, endless, yeahhhhhhh";
        $getNombre = $material->getNombre();
        $this->assertEquals($testName, $getNombre);

        //valor interior a frontera
        $material = new Material();
        $nombre = $material->setNombre("Nombre de material muy largo, extra large, très longue, huge, hasta 100 caracteres, endless, yeahhh");
        $testName = "Nombre de material muy largo, extra large, très longue, huge, hasta 100 caracteres, endless, yeahhh";
        $getNombre = $material->getNombre();
        $this->assertEquals($testName, $getNombre);

        $material = new Material();
        $nombre = $material->setNombre("1");
        $testName = "1";
        $getNombre = $material->getNombre();
        $this->assertEquals($testName, $getNombre);
    }

    public function testDescripcion()
    {
        //Valor normal
        $material = new Material();
        $descripcion = $material->setDescripcion("Descripcion corta");
        $testDescripcion = "Descripcion corta";
        $getDescripcion = $material->getDescripcion();
        $this->assertEquals($testDescripcion, $getDescripcion);

        //valores frontera
        $material = new Material();
        $descripcion = $material->setDescripcion("Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo ese");
        $testDescripcion = "Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo ese";
        $getDescripcion = $material->getDescripcion();
        $this->assertEquals($testDescripcion, $getDescripcion);

        $material = new Material();
        $descripcion = $material->setDescripcion("");
        $testDescripcion = "";
        $getDescripcion = $material->getDescripcion();
        $this->assertEquals($testDescripcion, $getDescripcion);

        $material = new Material();
        $descripcion = $material->setDescripcion(NULL);
        $testDescripcion = NULL;
        $getDescripcion = $material->getDescripcion();
        $this->assertEquals($testDescripcion, $getDescripcion);


        //valor exterior a frontera
        $material = new Material();
        $descripcion = $material->setDescripcion("Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo ese de más");
        $testDescripcion = "Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo ese de más";
        $getDescripcion = $material->getDescripcion();
        $this->assertEquals($testDescripcion, $getDescripcion);

        //valor interior a frontera
        $material = new Material();
        $descripcion = $material->setDescripcion("Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo es");
        $testDescripcion = "Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo Describo es";
        $getDescripcion = $material->getDescripcion();
        $this->assertEquals($testDescripcion, $getDescripcion);

        $material = new Material();
        $descripcion = $material->setDescripcion("e");
        $testDescripcion = "e";
        $getDescripcion = $material->getDescripcion();
        $this->assertEquals($testDescripcion, $getDescripcion);
    }

    public function testPrecio()
    {
        //Valor normal
        $material = new Material();
        $precio = $material->setPrecio(300);
        $testPrecio = 300;
        $getPrecio = $material->getPrecio();
        $this->assertEquals($testPrecio, $getPrecio);

        //valores frontera
        $material = new Material();
        $precio = $material->setPrecio(99999999999);
        $testPrecio = 99999999999;
        $getPrecio = $material->getPrecio();
        $this->assertEquals($testPrecio, $getPrecio);

        $material = new Material();
        $precio = $material->setPrecio(0);
        $testPrecio = 0;
        $getPrecio = $material->getPrecio();
        $this->assertEquals($testPrecio, $getPrecio);
        
        $material = new Material();
        $precio = $material->setPrecio(NULL);
        $testPrecio = NULL;
        $getPrecio = $material->getPrecio();
        $this->assertEquals($testPrecio, $getPrecio);


        //valor exterior a frontera
        $material = new Material();
        $precio = $material->setPrecio(300000000005);
        $testPrecio = 300000000005;
        $getPrecio = $material->getPrecio();
        $this->assertEquals($testPrecio, $getPrecio);

        //valor interior a frontera
        $material = new Material();
        $precio = $material->setPrecio(99999999998);
        $testPrecio = 99999999998;
        $getPrecio = $material->getPrecio();
        $this->assertEquals($testPrecio, $getPrecio);

        $material = new Material();
        $precio = $material->setPrecio(1);
        $testPrecio = 1;
        $getPrecio = $material->getPrecio();
        $this->assertEquals($testPrecio, $getPrecio);
    }

    public function testTipo()
    {
        //valor normal
        $material = new Material();
        $tipo = $material->setTipo("Tecnológico");
        $testTipo = "Tecnológico";
        $getTipo = $material->getTipo();
        $this->assertEquals($testTipo, $getTipo);

        //valores frontera
        $material = new Material();
        $tipo = $material->setTipo("Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos ");
        $testTipo = "Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos ";
        $getTipo = $material->getTipo();
        $this->assertEquals($testTipo, $getTipo);

        $material = new Material();
        $tipo = $material->setTipo("");
        $testTipo = "";
        $getTipo = $material->getTipo();
        $this->assertEquals($testTipo, $getTipo);

        $material = new Material();
        $tipo = $material->setTipo(NULL);
        $testTipo = NULL;
        $getTipo = $material->getTipo();
        $this->assertEquals($testTipo, $getTipo);


        //Valores exteriores a frontera
        $material = new Material();
        $tipo = $material->setTipo("Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos Multi");
        $testTipo = "Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos Multi";
        $getTipo = $material->getTipo();
        $this->assertEquals($testTipo, $getTipo);

        //valores interiores a frontera
        $material = new Material();
        $tipo = $material->setTipo("Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos");
        $testTipo = "Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos Multiusos";
        $getTipo = $material->getTipo();
        $this->assertEquals($testTipo, $getTipo);

        $material = new Material();
        $tipo = $material->setTipo("2");
        $testTipo = "2";
        $getTipo = $material->getTipo();
        $this->assertEquals($testTipo, $getTipo);
    }

    public function testDisponible()
    {
        //valor normal
        $material = new Material();
        $disponible = $material->setDisponible("yes");
        $testDisponible = "yes";
        $getDisponible = $material->getDisponible();
        $this->assertEquals($testDisponible, $getDisponible);

        //valores frontera
        $material = new Material();
        $disponible = $material->setDisponible("Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope ");
        $testDisponible = "Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope ";
        $getDisponible = $material->getDisponible();
        $this->assertEquals($testDisponible, $getDisponible);

        $material = new Material();
        $disponible = $material->setDisponible("");
        $testDisponible = "";
        $getDisponible = $material->getDisponible();
        $this->assertEquals($testDisponible, $getDisponible);


        //Valores exteriores a frontera
        $material = new Material();
        $disponible = $material->setDisponible("Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope N");
        $testDisponible = "Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope N";
        $getDisponible = $material->getDisponible();
        $this->assertEquals($testDisponible, $getDisponible);

        //valores interiores a frontera
        $material = new Material();
        $disponible = $material->setDisponible("Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope");
        $testDisponible = "Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope Nope";
        $getDisponible = $material->getDisponible();
        $this->assertEquals($testDisponible, $getDisponible);

        $material = new Material();
        $disponible = $material->setDisponible("y");
        $testDisponible = "y";
        $getDisponible = $material->getDisponible();
        $this->assertEquals($testDisponible, $getDisponible);
    }
    
    public function TestDate()
    {
        //valor normal
        $material = new Material();

                
        $date1 = '2019-06-25 15:30:00';
        $fecha_DateTime = DateTime::createFromFormat('Y-m-d H:i:s', $date1);
        $date = $material->setDate($fecha_DateTime);
        $getDate = $material->getDate();
        $this->assertEquals($date, $getDate);

        //valor frontera (date NULL)
        $material = new Material();
        $date = $material->setDate(NULL);
        $getDate = $material->getDate();
        $this->assertEquals(NULL, $getDate);
    }
}