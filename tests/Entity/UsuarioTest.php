<?php

namespace App\tests\Entity;

use App\Entity\Persona;
use App\Entity\Local;
use App\Entity\Departamento;
use App\Entity\UserRoles;
use App\Entity\Usuario;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class UsuarioTest extends TestCase
{
	public function testMail()
	{
		//normal
		$mail1 = 'mail@cosas.com';
		$persona1 = new Persona();
		$persona1->setCorreo($mail1);
		$user1 = new usuario();
		$user1->setMail($persona1->getCorreo());
		$this->assertEquals($mail1, $user1->getMail());

		//Null
		$mail2 = '';
		$persona2 = new persona();
		$persona2->setCorreo($mail2);
		$user2 = new usuario();
		$user2->setMail($persona2->getCorreo());
		$this->assertEquals($mail2, $user2->getMail());	

		//Símbolos extraños
		$mail3 = '气ɕ@cosas.com';
		$persona3 = new persona();
		$persona3->setCorreo($mail3);
		$user3 = new usuario();
		$user3->setMail($persona3->getCorreo());
		$this->assertEquals($mail3, $user3->getMail());

		//Longitud 101
		$mail4 = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 101);
		$persona4 = new persona();
		$persona4->setCorreo($mail4);
		$user4 = new usuario();
		$user4->setMail($persona4->getCorreo());
		$this->assertEquals($mail4, $user4->getMail());	
	}

	public function testPassword()
	{
		//normal
		$Password1 = 'Password';
		$persona1 = new Persona();
		$persona1->setCorreo($Password1);
		$user1 = new usuario();
		$user1->setPassword($persona1->getCorreo());
		$this->assertEquals($Password1, $user1->getPassword());

		//Null
		$Password2 = '';
		$persona2 = new persona();
		$persona2->setCorreo($Password2);
		$user2 = new usuario();
		$user2->setPassword($persona2->getCorreo());
		$this->assertEquals($Password2, $user2->getPassword());	

		//Símbolos extraños
		$Password3 = '气ɕ';
		$persona3 = new persona();
		$persona3->setCorreo($Password3);
		$user3 = new usuario();
		$user3->setPassword($persona3->getCorreo());
		$this->assertEquals($Password3, $user3->getPassword());

		//Longitud 101
		$Password4 = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 101);
		$persona4 = new persona();
		$persona4->setCorreo($Password4);
		$user4 = new usuario();
		$user4->setPassword($persona4->getCorreo());
		$this->assertEquals($Password4, $user4->getPassword());	
	}

	public function testNombre()
	{
		//Normal
		$Nombre0 = 'Nombre';
		$persona0 = new Persona();
		$persona0->setCorreo($Nombre0);
		$user0 = new usuario();
		$user0->setNombre($persona0->getCorreo());
		$this->assertEquals($Nombre0, $user0->getNombre());

		//Compuesto
		$Nombre1 = 'Nombre compuesto';
		$persona1 = new Persona();
		$persona1->setCorreo($Nombre1);
		$user1 = new usuario();
		$user1->setNombre($persona1->getCorreo());
		$this->assertEquals($Nombre1, $user1->getNombre());

		//Null
		$Nombre2 = '';
		$persona2 = new persona();
		$persona2->setCorreo($Nombre2);
		$user2 = new usuario();
		$user2->setNombre($persona2->getCorreo());
		$this->assertEquals($Nombre2, $user2->getNombre());	

		//Símbolos extraños
		$Nombre3 = '气ɕ';
		$persona3 = new persona();
		$persona3->setCorreo($Nombre3);
		$user3 = new usuario();
		$user3->setNombre($persona3->getCorreo());
		$this->assertEquals($Nombre3, $user3->getNombre());

		//Longitud 101
		$Nombre4 = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 101);
		$persona4 = new persona();
		$persona4->setCorreo($Nombre4);
		$user4 = new usuario();
		$user4->setNombre($persona4->getCorreo());
		$this->assertEquals($Nombre4, $user4->getNombre());	
	}

	public function testApellidos()
	{
		//Uno
		$Apellidos0 = 'Apellidos';
		$persona0 = new Persona();
		$persona0->setCorreo($Apellidos0);
		$user0 = new usuario();
		$user0->setApellidos($persona0->getCorreo());
		$this->assertEquals($Apellidos0, $user0->getApellidos());

		//Dos
		$Apellidos1 = 'Apellidos compuesto';
		$persona1 = new Persona();
		$persona1->setCorreo($Apellidos1);
		$user1 = new usuario();
		$user1->setApellidos($persona1->getCorreo());
		$this->assertEquals($Apellidos1, $user1->getApellidos());

		//Null
		$Apellidos2 = '';
		$persona2 = new persona();
		$persona2->setCorreo($Apellidos2);
		$user2 = new usuario();
		$user2->setApellidos($persona2->getCorreo());
		$this->assertEquals($Apellidos2, $user2->getApellidos());	

		//Símbolos extraños
		$Apellidos3 = '气ɕ';
		$persona3 = new persona();
		$persona3->setCorreo($Apellidos3);
		$user3 = new usuario();
		$user3->setApellidos($persona3->getCorreo());
		$this->assertEquals($Apellidos3, $user3->getApellidos());

		//Longitud 101
		$Apellidos4 = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 101);
		$persona4 = new persona();
		$persona4->setCorreo($Apellidos4);
		$user4 = new usuario();
		$user4->setApellidos($persona4->getCorreo());
		$this->assertEquals($Apellidos4, $user4->getApellidos());	
	}

	public function testVarios()
	{
		//imagen + numeroEmpleado
		$Apellidos4 = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 101);
		$persona4 = new persona();
		$persona4->setCorreo($Apellidos4);
		$user = new usuario();
		$elGet = $user->getnumeroEmpleado();

		$stringImg = 'ima.jpg';
		$user->setImagen($stringImg);
		$elGetIm = $user->getImagen();
		$this->assertEquals($stringImg, $elGetIm);

		$user->setnumeroEmpleado($persona4);

		//UserRole
		$stringRole = 'prueba';
		$role = new userroles('new');
		$role->setRole("$stringRole");
		$user->setUserRole($role);
		$rolUs = $user->getUserRole();
	}

}