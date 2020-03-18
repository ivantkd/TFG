<?php

namespace App\tests\Entity;

use App\Entity\UserRoles;
use PHPUnit\Framework\TestCase;

class UserRolesTest extends TestCase
{

    public function testRole()
    {
    	//El constructor crea un rol, pero no lo guarda en el campo Role
 	    $userRole = new userroles('admin');        
        $this->assertNull($userRole->getRole());


        //Prueba setter/getter
        $userRoleTest1 = new userroles('admin');     
        $role1 = 'prueba';
        $userRoleTest1->setRole($role1);
        $this->assertEquals($role1, $userRoleTest1->getRole());

        //Probamos símbolo extraños
        $userRoleTest2 = new userroles('admin');     
        $role2 = '\n * 气 ɕ';
        $userRoleTest2->setRole($role2);
        $this->assertEquals($role2, $userRoleTest2->getRole());

        //Probamos vacío
        $userRoleTest3 = new userroles('admin');     
        $role3 = '';
        $userRoleTest3->setRole($role3);
        $this->assertEquals($role3, $userRoleTest3->getRole());

    }

}