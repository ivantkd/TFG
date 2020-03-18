<?php

namespace App\tests\Entity;

use App\Entity\Persona;
use App\Entity\Local;
use App\Entity\Departamento;
use PHPUnit\Framework\TestCase;

class PersonaTest extends TestCase
{
	//NUMERO EMPLEADO NO SE PUEDE TESTEAR YA QUE DOCTRINE ASIGNA LA PK 
    public function testNumeroEmpleado()
    {
    	//La entity por defecto no genera número de empleado
        $persona1 = new persona();        
 	    $result = $persona1->getNumero_Empleado();
        $this->assertNull($result);

    }
    
    public function testLocalId()
    {
		//Probamos valor válido
		$local1 = new local();
        $persona1 = new persona();        
 	    $persona1->setLocalId($local1);
 	    $this->assertEquals($local1, $persona1->getLocalId());

	}

	public function testCorreo()
	{
		//Valor normal
		$mail1 = 'correo@cosas.com';
		$persona1 = new persona();
		$persona1->setCorreo($mail1);
		$this->assertEquals($mail1, $persona1->getCorreo());

		//Null
		$mail2 = '';
		$persona2 = new persona();
		$persona2->setCorreo($mail2);
		$this->assertEquals($mail2, $persona2->getCorreo());		

		//Símbolos extraños
		$mail3 = '气ɕ@cosas.com';
		$persona3 = new persona();
		$persona3->setCorreo($mail3);
		$this->assertEquals($mail3, $persona3->getCorreo());		

		//Longitud 101
		$mail4 = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 101);
		$persona4 = new persona();
		$persona4->setCorreo($mail4);
		$this->assertEquals($mail4, $persona4->getCorreo());		
	}

	public function testDepartamento()
	{
		//Probamos valor válido
		$dep1 = new departamento();
		$persona1 = new persona();        
 	    $persona1->setDepartamento($dep1);
 	    $this->assertEquals($dep1, $persona1->getDepartamento());
	}

	public function testNombre()
	{
		//Probamos valor normal
		$nombre1 = 'prueba';
		$persona1 = new persona();
		$persona1->setNombre($nombre1);
		$this->assertEquals($nombre1, $persona1->getNombre());

		//Probamos valor con espacios
		$nombre2 = 'nombre de prueba';
		$persona2 = new persona();
		$persona2->setNombre($nombre2);
		$this->assertEquals($nombre2, $persona2->getNombre());

		//Probamos valor con símbolos extraños
		$nombre3 = '\n * 气 ɕ';
		$persona3 = new persona();
		$persona3->setNombre($nombre3);
		$this->assertEquals($nombre3, $persona3->getNombre());

		//Probamos valor nulo
		$nombre4 = '';
		$persona4 = new persona();
		$persona4->setNombre($nombre4);
		$this->assertEquals($nombre4, $persona4->getNombre());	

		//Probamos valor con 101 caracteres
		$nombre5 = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 101);
		$persona5 = new persona();
		$persona5->setNombre($nombre5);
		$this->assertEquals($nombre5, $persona5->getNombre());	
	}

	public function testApellidos()
	{
		//Probamos valor normal
		$Apellidos1 = 'prueba';
		$persona1 = new persona();
		$persona1->setApellidos($Apellidos1);
		$this->assertEquals($Apellidos1, $persona1->getApellidos());

		//Probamos valor con espacios
		$Apellidos2 = 'Apellidos de prueba';
		$persona2 = new persona();
		$persona2->setApellidos($Apellidos2);
		$this->assertEquals($Apellidos2, $persona2->getApellidos());

		//Probamos valor con símbolos extraños
		$Apellidos3 = '\n * 气 ɕ';
		$persona3 = new persona();
		$persona3->setApellidos($Apellidos3);
		$this->assertEquals($Apellidos3, $persona3->getApellidos());

		//Probamos valor nulo
		$Apellidos4 = '';
		$persona4 = new persona();
		$persona4->setApellidos($Apellidos4);
		$this->assertEquals($Apellidos4, $persona4->getApellidos());	

		//Probamos valor con 101 caracteres
		$Apellidos5 = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 101);
		$persona5 = new persona();
		$persona5->setApellidos($Apellidos5);
		$this->assertEquals($Apellidos5, $persona5->getApellidos());	
	}

	public function testCargo()
	{
		//Probamos valor normal
		$Cargo1 = 'prueba';
		$persona1 = new persona();
		$persona1->setCargo($Cargo1);
		$this->assertEquals($Cargo1, $persona1->getCargo());

		//Probamos valor con espacios
		$Cargo2 = 'Cargo de prueba';
		$persona2 = new persona();
		$persona2->setCargo($Cargo2);
		$this->assertEquals($Cargo2, $persona2->getCargo());

		//Probamos valor con símbolos extraños
		$Cargo3 = '\n * 气 ɕ';
		$persona3 = new persona();
		$persona3->setCargo($Cargo3);
		$this->assertEquals($Cargo3, $persona3->getCargo());

		//Probamos valor nulo
		$Cargo4 = '';
		$persona4 = new persona();
		$persona4->setCargo($Cargo4);
		$this->assertEquals($Cargo4, $persona4->getCargo());	

		//Probamos valor con 101 caracteres
		$Cargo5 = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 101);
		$persona5 = new persona();
		$persona5->setCargo($Cargo5);
		$this->assertEquals($Cargo5, $persona5->getCargo());	
	}

	public function testTelefono()
	{
		//Probamos valor normal
		$Telefono1 = 968433165;
		$persona1 = new persona();
		$persona1->setTelefono($Telefono1);
		$this->assertEquals($Telefono1, $persona1->getTelefono());

		//Probamos valor con símbolos extraños
		$Telefono2 = '\n * 气 ɕ';
		$persona2 = new persona();
		$persona2->setTelefono($Telefono2);
		$this->assertEquals($Telefono2, $persona2->getTelefono());

		//Probamos valor nulo
		$Telefono5 = '';
		$persona5 = new persona();
		$persona5->setTelefono($Telefono5);
		$this->assertEquals($Telefono5, $persona5->getTelefono());	

		//Probamos valor con 101 caracteres
		$Telefono5 = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 101);
		$persona5 = new persona();
		$persona5->setTelefono($Telefono5);
		$this->assertEquals($Telefono5, $persona5->getTelefono());	
	}


}