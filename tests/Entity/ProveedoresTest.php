<?php

namespace App\tests\Entity;

use App\Entity\Proveedores;
use PHPUnit\Framework\TestCase;

class ProveedoresTest extends TestCase
{
	public function testNombre()
	{
		//nombre normal
		$nombre1 = 'Francisco';
		$prov1 = new Proveedores();
		$prov1->setNombre($nombre1);
		$this->assertEquals($nombre1, $prov1->getNombre());

		//nombres compuestos
		$nombre2 = 'Ferreteria Juan Carlos';
		$prov2 = new Proveedores();
		$prov2->setNombre($nombre2);
		$this->assertEquals($nombre2, $prov2->getNombre());

		//símbolos extraños
		$nombre3 = '\n * 气 ɕ';
		$prov3 = new Proveedores();
		$prov3->setNombre($nombre3);
		$this->assertEquals($nombre3, $prov3->getNombre());
	}

	public function testCorreo()
	{
		//Valor normal
		$prod1 = 'correo@cosas.com';
		$Proveedores1 = new Proveedores();
		$Proveedores1->setCorreo($prod1);
		$this->assertEquals($prod1, $Proveedores1->getCorreo());

		//Null
		$prod2 = '';
		$Proveedores2 = new Proveedores();
		$Proveedores2->setCorreo($prod2);
		$this->assertEquals($prod2, $Proveedores2->getCorreo());		

		//Símbolos extraños
		$prod3 = '气ɕ@cosas.com';
		$Proveedores3 = new Proveedores();
		$Proveedores3->setCorreo($prod3);
		$this->assertEquals($prod3, $Proveedores3->getCorreo());		

		//Longitud 101
		$prod4 = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 101);
		$Proveedores4 = new Proveedores();
		$Proveedores4->setCorreo($prod4);
		$this->assertEquals($prod4, $Proveedores4->getCorreo());		
	}

	public function testTelefono()
	{
		//Probamos valor normal
		$Telefono1 = 968433165;
		$proveedores1 = new proveedores();
		$proveedores1->setTelefono($Telefono1);
		$this->assertEquals($Telefono1, $proveedores1->getTelefono());

		//Probamos valor con símbolos extraños
		$Telefono2 = '\n * 气 ɕ';
		$proveedores2 = new proveedores();
		$proveedores2->setTelefono($Telefono2);
		$this->assertEquals($Telefono2, $proveedores2->getTelefono());

		//Probamos valor nulo
		$Telefono5 = '';
		$proveedores5 = new proveedores();
		$proveedores5->setTelefono($Telefono5);
		$this->assertEquals($Telefono5, $proveedores5->getTelefono());	

		//Probamos valor con 101 caracteres
		$Telefono5 = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 101);
		$proveedores5 = new proveedores();
		$proveedores5->setTelefono($Telefono5);
		$this->assertEquals($Telefono5, $proveedores5->getTelefono());	
	}

	public function testProducto()
	{
		//Valor normal
		$prod0 = 'Producto';
		$Proveedores0 = new Proveedores();
		$Proveedores0->setProducto($prod0);
		$this->assertEquals($prod0, $Proveedores0->getProducto());

		//Con espacio
		$prod1 = 'Producto barato';
		$Proveedores1 = new Proveedores();
		$Proveedores1->setProducto($prod1);
		$this->assertEquals($prod1, $Proveedores1->getProducto());

		//Null
		$prod2 = '';
		$Proveedores2 = new Proveedores();
		$Proveedores2->setProducto($prod2);
		$this->assertEquals($prod2, $Proveedores2->getProducto());		
		//Símbolos extraños
		$prod3 = '气ɕ';
		$Proveedores3 = new Proveedores();
		$Proveedores3->setProducto($prod3);
		$this->assertEquals($prod3, $Proveedores3->getProducto());	

		//Longitud 101
		$prod4 = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 101);
		$Proveedores4 = new Proveedores();
		$Proveedores4->setProducto($prod4);
		$this->assertEquals($prod4, $Proveedores4->getProducto());		
	}

	public function testResponsable()
	{
		//Valor normal
		$res0 = 'Responsable';
		$Proveedores0 = new Proveedores();
		$Proveedores0->setResponsable($res0);
		$this->assertEquals($res0, $Proveedores0->getResponsable());

		//Con espacio
		$res1 = 'Responsable majo';
		$Proveedores1 = new Proveedores();
		$Proveedores1->setResponsable($res1);
		$this->assertEquals($res1, $Proveedores1->getResponsable());

		//Null
		$res2 = '';
		$Proveedores2 = new Proveedores();
		$Proveedores2->setResponsable($res2);
		$this->assertEquals($res2, $Proveedores2->getResponsable());		
		//Símbolos extraños
		$res3 = '气ɕ';
		$Proveedores3 = new Proveedores();
		$Proveedores3->setResponsable($res3);
		$this->assertEquals($res3, $Proveedores3->getResponsable());	

		//Longitud 101
		$res4 = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 101);
		$Proveedores4 = new Proveedores();
		$Proveedores4->setResponsable($res4);
		$this->assertEquals($res4, $Proveedores4->getResponsable());		
	}

	public function testDireccion()
	{		
		//Valor normal
		$dir1 = 'c/ Direccion normal, 86';
		$Proveedores1 = new Proveedores();
		$Proveedores1->setDireccion($dir1);
		$this->assertEquals($dir1, $Proveedores1->getDireccion());

		//Null
		$dir2 = '';
		$Proveedores2 = new Proveedores();
		$Proveedores2->setDireccion($dir2);
		$this->assertEquals($dir2, $Proveedores2->getDireccion());		
		//Símbolos extraños
		$dir3 = '气ɕ';
		$Proveedores3 = new Proveedores();
		$Proveedores3->setDireccion($dir3);
		$this->assertEquals($dir3, $Proveedores3->getDireccion());	

		//Longitud 101
		$dir4 = substr(str_shuffle('abcdefghijklmnopqrstuvwxyz'), 0, 101);
		$Proveedores4 = new Proveedores();
		$Proveedores4->setDireccion($dir4);
		$this->assertEquals($dir4, $Proveedores4->getDireccion());		
	}


}	