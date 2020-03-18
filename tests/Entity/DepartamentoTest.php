<?php

namespace App\Tests\Entity;

use App\Entity\Departamento;
use PHPUnit\Framework\TestCase;

class DepartamentoTest extends TestCase
{

    /*
    //getId no se testea porque es el índice incremental de la base de datos
    //
    */

    public function testNombre()
    {
        //Valor normal
        $departamento = new Departamento();
        $nombre = $departamento->setNombre("Recursos Humanos");
        $testName = "Recursos Humanos";
        $getNombre = $departamento->getNombre();
        $this->assertEquals($testName, $getNombre);

        //valores frontera
        $departamento = new Departamento();
        $nombre = $departamento->setNombre("Nombre de departamento muy largo, extra large, très longue, huge, hasta 100 caracteres, endless, yes");
        $testName = "Nombre de departamento muy largo, extra large, très longue, huge, hasta 100 caracteres, endless, yes";
        $getNombre = $departamento->getNombre();
        $this->assertEquals($testName, $getNombre);

        $departamento = new Departamento();
        $nombre = $departamento->setNombre("");
        $testName = "";
        $getNombre = $departamento->getNombre();
        $this->assertEquals($testName, $getNombre);


        //valor exterior a frontera
        $departamento = new Departamento();
        $nombre = $departamento->setNombre("Nombre de departamento muy largo, extra large, très longue, huge, hasta 100 caracteres, endless, yes. Se pasa.");
        $testName = "Nombre de departamento muy largo, extra large, très longue, huge, hasta 100 caracteres, endless, yes. Se pasa.";
        $getNombre = $departamento->getNombre();
        $this->assertEquals($testName, $getNombre);

        //valor interior a frontera
        $departamento = new Departamento();
        $nombre = $departamento->setNombre("Nombre de departamento muy largo, extra large, très longue, huge, hasta 100 caracteres, endless, ye");
        $testName = "Nombre de departamento muy largo, extra large, très longue, huge, hasta 100 caracteres, endless, ye";
        $getNombre = $departamento->getNombre();
        $this->assertEquals($testName, $getNombre);

        $departamento = new Departamento();
        $nombre = $departamento->setNombre("3");
        $testName = "3";
        $getNombre = $departamento->getNombre();
        $this->assertEquals($testName, $getNombre);
    }
}