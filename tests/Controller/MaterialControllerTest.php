<?php
namespace App\tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;
use App\Entity\Usuario;
use App\Entity\Material;
use App\Entity\Local;
use DateTime;

class MaterialControllerTest extends WebTestCase
{	
	protected function createAuthorizedClient()
    {
        $client = static::createClient();
        $container = static::$kernel->getContainer();
        $session = $container->get('session');
        $person = self::$kernel->getContainer()->get('doctrine')->getRepository(Usuario::class)->findOneByMail('test@test.test');
        $token = new UsernamePasswordToken($person, null, 'main', $person->getRoles());
        $session->set('_security_main', serialize($token));
        $session->save();
        $client->getCookieJar()->set(new Cookie($session->getName(), $session->getId()));
        return $client;
    }

    public function testIndex()
    {
        //Testeamos que al hacer una request al indice nos devuelva una pagina
        $client00 = $this->createAuthorizedClient();
        $crawler00 = $client00->request('GET', '/material/listuser');
        $this->assertNotTrue($client00->getResponse()->isNotFound());
        $this->assertEquals(
            200,
            $client00->getResponse()->getStatusCode()
        );		
        /*
        Testeamos que al hacer una request al indice nos devuelva la pagina
        de listado de material
        */
        $crawler00 = $client00->request("GET","/material/listuser");
        $para1 = $crawler00->filter('h1')->first()->text();
        $this->assertEquals("Mi material ",$para1);

    }

    public function testListMaterial(){
        //Testeamos que al hacer una request a /listadmin nos devuelva una pagina
        $client = $this->createAuthorizedClient();
        $crawler = $client->request('GET', '/material/listadmin');
        $this->assertNotTrue($client->getResponse()->isNotFound());
        $this->assertEquals(
            200,
            $client->getResponse()->getStatusCode()
        );		
        /*
        Testeamos que al hacer una request a /listadmin nos devuelva la pagina
        del listado de material
        */
        $crawler = $client->request("GET","/material/listadmin");
        $para1 = $crawler->filter('h1')->first()->text();
        $this->assertEquals("Lista de Material de Empresa ",$para1);
    }

    public function testIndexLocal()
    {
        //Testeamos que al hacer una request al indice nos devuelva una pagina
        $client10 = $this->createAuthorizedClient();
        $crawler10 = $client10->request('GET', '/material/listuser');
        $this->assertNotTrue($client10->getResponse()->isNotFound());
        $this->assertEquals(
            200,
            $client10->getResponse()->getStatusCode()
        );		
        
        $client22 = $this->createAuthorizedClient();
        $crawler22 = $client22->request('GET', '/material../new');
        $buttonCrawlerNode = $crawler22->selectButton('Guardar');
        $form = $buttonCrawlerNode->form();

        //Hacemos inserciones para ver si se muestran todos y más pidiendo los materiales de esta oficina/local
        $form = $buttonCrawlerNode->form([
            'material[Nombre]'    => 'impresora',
            'material[Descripcion]' => 'nueva',
            'material[Precio]' => '10',
            'material[Usuario]' => NULL,
            'material[Tipo]' => 'epson',
            'material[Oficina]' => '1',
        ]);

        $client22->submit($form);

        $client22 = $this->createAuthorizedClient();
        $crawler22 = $client22->request('GET', '/material../new');
        $buttonCrawlerNode = $crawler22->selectButton('Guardar');
        $form = $buttonCrawlerNode->form();

        //Asignamos valores al INSERT
        $form = $buttonCrawlerNode->form([
            'material[Nombre]'    => 'calculadora',
            'material[Descripcion]' => 'test42',
            'material[Precio]' => '10',
            'material[Usuario]' => NULL,
            'material[Tipo]' => 'cientifica',
            'material[Oficina]' => '1',
        ]);

        $client22->submit($form);

        $client22 = $this->createAuthorizedClient();
        $crawler22 = $client22->request('GET', '/material../new');
        $buttonCrawlerNode = $crawler22->selectButton('Guardar');
        $form = $buttonCrawlerNode->form();

        //Asignamos valores al INSERT
        $form = $buttonCrawlerNode->form([
            'material[Nombre]'    => 'calculadora',
            'material[Descripcion]' => 'test50 deleted',
            'material[Precio]' => '10',
            'material[Usuario]' => NULL,
            'material[Tipo]' => 'cientifica',
            'material[Oficina]' => '1',
        ]);
        /*
        Testeamos que al hacer una request al indice nos devuelva la pagina
        principal de material mostrandonos los materiales filtrados por local (es decir, en este caso todos los del local 1)
        */
        $localId = 1;
        $client11 = $this->createAuthorizedClient();
        $crawler11 = $client11->request("GET","/material/listuser/$localId");
        $this->assertSame('test42', $crawler11->filter('td:contains("test42")')->text());
        $this->assertSame('nueva', $crawler11->filter('td:contains("nueva")')->text());
    }

    public function testListMaterialByLocal()
    {
        //Probamos que poniendo una id de local que ya sabemos que existe nos muestre su página principal
        $client1 = $this->createAuthorizedClient();
        $crawler1 = $client1->request('GET', '/material/listadmin/1');
        $this->assertNotTrue($client1->getResponse()->isNotFound());
        $this->assertEquals(
            200,
            $client1->getResponse()->getStatusCode()
        );		
        $this->assertSame('Lista materiales index', $crawler1->filter('title')->text());
        $this->assertSame('Lista de Material de Empresa ', $crawler1->filter('h1')->text());

    }

    public function testasignarUsuarioMaterial()
    {
        $client22 = $this->createAuthorizedClient();

        //llevamos a cabo un insert de un material para después asignarlo
        $crawler22 = $client22->request('GET', '/material../new');
        $buttonCrawlerNode = $crawler22->selectButton('Guardar');
        //Asignamos valores al INSERT
        $form = $buttonCrawlerNode->form([
            'material[Nombre]'    => 'calculadora',
            'material[Descripcion]' => 'testNew',
            'material[Precio]' => '10',
            'material[Usuario]' => NULL,
            'material[Tipo]' => 'cientifica',
            'material[Oficina]' => '1',
        ]);

        //hacemos una búsqueda del total de materiales asignados antes del insert
        $materialSin = self::$kernel->getContainer()->get('doctrine')->getRepository(
            Material::class)->count(array(
                'Usuario'    => 'test@test.test',
            ));
        
        $client22->submit($form);

        //Recogemos de la base de datos el material recién introducido para recuperar su id
        $material22 = self::$kernel->getContainer()->get('doctrine')->getRepository(
            Material::class)->findOneBy(array(
            'Nombre' => 'calculadora',
            'Descripcion' => 'testNew',
            'Precio' => '10',
            'Usuario' => NULL,
            'Tipo' => 'cientifica',
            'Oficina' => '1',                
            ));

        $id_material22 = $material22->getId();
        $date1 = '2019-06-25 15:30:00';
        $fecha_DateTime = DateTime::createFromFormat('Y-m-d H:i:s', $date1);
        $date = $material22->getDate();
        $date = $material22->setDate($fecha_DateTime);

        $this->assertNotNull($id_material22);

        //Pedimos la vista de asignar del material recuperado
        $crawler22 = $client22->request("GET","/material/$id_material22/asignar");

        //redireccion al indice de materiales después de una buena asignación
        $this->assertEquals(302, $client22->getResponse()->getStatusCode());
        
        //cantidad de materiales que tiene ese usuario asignados después del insert
        $materialCon = self::$kernel->getContainer()->get('doctrine')->getRepository(
            Material::class)->count(array(
                'Usuario'    => 'test@test.test',
            ));

        //comparación para ver si se hace bien la asignación y cambia el número de asignados
        $this->assertTrue($materialCon > $materialSin);
    }

    public function testDevolverMaterial()
    {
        $client22 = $this->createAuthorizedClient();

        //Recogemos de la base de datos el material recién introducido para recuperar su id
        $material22 = self::$kernel->getContainer()->get('doctrine')->getRepository(
            Material::class)->findOneBy(array(
            'Nombre' => 'calculadora',
            'Descripcion' => 'testNew',
            'Precio' => '10',
            'Usuario' => 'test@test.test',
            'Tipo' => 'cientifica',
            'Oficina' => '1',                
            ));

        $id_material22 = $material22->getId();
        
        $this->assertNotNull($id_material22);

        //hacemos una búsqueda del total de materiales asignados antes del insert
        $materialCon = self::$kernel->getContainer()->get('doctrine')->getRepository(
            Material::class)->count(array(
                'Usuario'    => 'test@test.test',
            ));

        //Pedimos la vista de asignar del material recuperado
        $crawler22 = $client22->request("GET","/material/$id_material22/devolver");

        //redireccion al indice de materiales después de una buena asignación
        $this->assertEquals(302, $client22->getResponse()->getStatusCode());
        
        //cantidad de materiales que tiene ese usuario asignados después del insert
        $materialSin = self::$kernel->getContainer()->get('doctrine')->getRepository(
            Material::class)->count(array(
                'Usuario'    => 'test@test.test',
            ));

        //comparación para ver si se hace bien la asignación y cambia el número de asignados
        $this->assertTrue($materialCon > $materialSin);
    }

    public function testNew(){

        //Testeamos la request con el método GET
        $client20 = $this->createAuthorizedClient();
        $crawler20 = $client20->request('GET', '/material../new');
        $this->assertEquals(
            200,
            $client20->getResponse()->getStatusCode()
        );
        
        $this->assertNotTrue($client20->getResponse()->isNotFound());

        //Testeamos la request con el método POST
        $client21 = $this->createAuthorizedClient();
        $crawler21 = $client21->request('POST', '/material../new');
        $this->assertEquals(
            200,
            $client21->getResponse()->getStatusCode()
        );
        
        $this->assertNotTrue($client21->getResponse()->isNotFound());


        //Testeamos si guarda el material nuevo

        $client22 = $this->createAuthorizedClient();
        $crawler22 = $client22->request('GET', '/material../new');
        $buttonCrawlerNode = $crawler22->selectButton('Guardar');
        $form = $buttonCrawlerNode->form();

        //Asignamos valores al INSERT
        $form = $buttonCrawlerNode->form([
            'material[Nombre]'    => 'calculadora',
            'material[Descripcion]' => 'test22',
            'material[Precio]' => '10',
            'material[Usuario]' => 'test@test.test',
            'material[Tipo]' => 'cientifica',
            'material[Oficina]' => '1',
        ]);
        
        $client22->submit($form);

        //Testeamos si se ha introducido correctamente

        $material22 = self::$kernel->getContainer()->get('doctrine')->getRepository(
            Material::class)->findOneBy(array(
                'Nombre'    => 'calculadora',
                
            ));

        $id_material22 = $material22->getId();
        
        $this->assertNotNull($id_material22);

        //Vemos si se inserta la informacion en la BD

        $crawler22 = $client22->request("GET","/material/$id_material22/show");
        $para1 = $crawler22->filter('h1')->first()->text();
        $this->assertEquals("Información del material  calculadora ",$para1);

        //Probamos las condiciones del IF
        /*
        La condicion $form->isSubmitted()siempre sera true, pues al simular
        el presionar el boton para guardar la entrada en la BD le estamos 
        proporcionando el valor true a esta variable
        */

        //Condicion True-False
        $client23 = $this->createAuthorizedClient();
        $crawler23 = $client23->request('GET', '/material../new');
        $buttonCrawlerNode = $crawler23->selectButton('Guardar');
        $form = $buttonCrawlerNode->form();

        $form = $buttonCrawlerNode->form([
            'material[Nombre]'    => 23,
            'material[Descripcion]' => 23,
            'material[Precio]' => '23',
            'material[Usuario]' => 'test@test.test',
            'material[Tipo]' => 'cientifica',
            'material[Oficina]' => '1',
        ]);

        $client23->submit($form);

        //Comprobamos que nos redirige al formulario para insertar un material

        $para23 = $crawler23->filter('h1')->first()->text();
        $this->assertEquals("Registrar nuevo material",$para23);

        //Condicion True-True
        $client24 = $this->createAuthorizedClient();
        $crawler24 = $client24->request('GET', '/material../new');
        $buttonCrawlerNode = $crawler24->selectButton('Guardar');
        $form = $buttonCrawlerNode->form();

        $form = $buttonCrawlerNode->form([
            'material[Nombre]'    => 'calculadora',
            'material[Descripcion]' => 'test',
            'material[Precio]' => '23',
            'material[Usuario]' => 'test@test.test',
            'material[Tipo]' => 'cientifica',
            'material[Oficina]' => '1',
        ]);

        $client24->submit($form);

        //Comprobamos que nos redirige al formulario para insertar un material

        $para24 = $crawler24->filter('h1')->first()->text();
        $this->assertEquals("Registrar nuevo material",$para24);
    }

    public function testShow(){
        $client40 = $this->createAuthorizedClient();
        //Probamos a mostrar un material ya existente
        $material22 = self::$kernel->getContainer()->get('doctrine')->getRepository(
            Material::class)->findOneBy(array(
            'Nombre'    => 'calculadora',
            'Descripcion' => 'test22',
            'Precio' => '10',
            'Usuario' => 'test@test.test',
            'Tipo' => 'cientifica',
            'Oficina' => '1',
        ));

        $id_material22 = $material22->getId();
        
        $this->assertNotNull($id_material22);

        $client30 = $this->createAuthorizedClient();
        $crawler30 = $client30->request('GET', "/material/$id_material22/show");

        $this->assertEquals(
            200,
            $client30->getResponse()->getStatusCode()
        );
        $this->assertNotTrue($client30->getResponse()->isNotFound());

        //Probamos a pasar parámetros incorrectos

        //Le pasamos un string
        $client31 = $this->createAuthorizedClient();
        $crawler31 = $client31->request('GET', '/material/error/show');
        $this->assertEquals(
            404,
            $client31->getResponse()->getStatusCode()
        );
        $this->assertTrue($client31->getResponse()->isNotFound());

        //Le pasamos un id inexistente
        $client32 = $this->createAuthorizedClient();
        $crawler = $client32->request('GET', '/material/1256564/show');
        $this->assertEquals(
            404,
            $client32->getResponse()->getStatusCode()
        );
        $this->assertTrue($client32->getResponse()->isNotFound());

        //Le pasamos unos carácteres extraños
        $client33 = $this->createAuthorizedClient();
        $crawler = $client33->request('GET', '/material/€¬·$%&&/show');
        $this->assertEquals(
            404,
            $client33->getResponse()->getStatusCode()
        );
        $this->assertTrue($client33->getResponse()->isNotFound());

    }

    public function testEdit(){

        //Testeamos la request con el método GET (petición de material inexistente)
        $client40 = $this->createAuthorizedClient();
        $crawler40 = $client40->request('GET', '/material/1gfkjsgjsriu/edit');
        $this->assertEquals(
            404,
            $client40->getResponse()->getStatusCode()
        );
        
        $this->assertTrue($client40->getResponse()->isNotFound());

        //Insertamos un nuevo material
        $client42 = $this->createAuthorizedClient();
        $crawler42 = $client42->request('GET', '/material../new');
        $buttonCrawlerNode = $crawler42->selectButton('Guardar');
        $form42 = $buttonCrawlerNode->form();

        //Asignamos valores al INSERT
        $form42 = $buttonCrawlerNode->form([
            'material[Nombre]'    => 'calculadora',
            'material[Descripcion]' => 'test42',
            'material[Precio]' => '10',
            'material[Usuario]' => NULL,
            'material[Tipo]' => 'cientifica',
            'material[Oficina]' => '1',
        ]);
        
        $client42->submit($form42);

        //Testeamos si se ha introducido correctamente

        $material42 = self::$kernel->getContainer()->get('doctrine')->getRepository(
            Material::class)->findOneBy(array(
                'Nombre'    => 'calculadora',
                'Descripcion' => 'test42',
                'Precio' => '10',
                'Usuario' => NULL,
                'Tipo' => 'cientifica',
                'Oficina' => '1',
                
            ));

        $id_material42 = $material42->getId();
        
        $this->assertNotNull($id_material42);

        //Creamos otro cliente que edite el material introducido anteriormente
        //La edicion cumple las condiciones isSubmitted() y isValid() (True-True)
        $client43 = $this->createAuthorizedClient();
        $crawler43 = $client43->request('GET', "/material/$id_material42/edit");
        $buttonCrawlerNode = $crawler43->selectButton('Actualizar');
        $form43 = $buttonCrawlerNode->form();

        //Asignamos valores al EDIT
        $form43 = $buttonCrawlerNode->form([
            'material[Nombre]'    => 'calculadora43',
            'material[Descripcion]' => 'test43 editada',
            'material[Precio]' => '10',
            'material[Usuario]' => NULL,
            'material[Tipo]' => 'cientifica',
            'material[Oficina]' => '1',
        ]);
        
        $client43->submit($form43);

        //Insertamos un nuevo material
        $client44 = $this->createAuthorizedClient();
        $crawler44 = $client44->request('GET', '/material../new');
        $buttonCrawlerNode = $crawler44->selectButton('Guardar');
        $form44 = $buttonCrawlerNode->form();

        //Asignamos valores al INSERT
        $form44 = $buttonCrawlerNode->form([
            'material[Nombre]'    => 'calculadora',
            'material[Descripcion]' => 'test44',
            'material[Precio]' => '10',
            'material[Usuario]' => NULL,
            'material[Tipo]' => 'cientifica',
            'material[Oficina]' => '1',
        ]);
        
        $client44->submit($form44);

        //Testeamos si se ha introducido correctamente

        $material44 = self::$kernel->getContainer()->get('doctrine')->getRepository(
            Material::class)->findOneBy(array(
                'Nombre'    => 'calculadora',
                'Descripcion' => 'test44',
                'Precio' => '10',
                'Usuario' => NULL,
                'Tipo' => 'cientifica',
                'Oficina' => '1',
            ));

        $id_material44 = $material44->getId();
        
        $this->assertNotNull($id_material44);

        //Creamos otro cliente que edite el material introducido anteriormente
        //La edicion cumple la condiciones isSubmitted() pero no isValid() (True-False)
        $client45 = $this->createAuthorizedClient();
        $crawler45 = $client45->request('GET', "/material/$id_material44/edit");
        $buttonCrawlerNode = $crawler45->selectButton('Actualizar');
        $form45 = $buttonCrawlerNode->form();

        //Asignamos valores al EDIT incorrectos
        $form45 = $buttonCrawlerNode->form([
            'material[Nombre]'    => 'calculadora45',
            'material[Descripcion]' => 'test45 editada',
            'material[Precio]' => 45,
            'material[Usuario]' => 'test@test.test',
            'material[Tipo]' => 'cientifica',
            'material[Oficina]' => '1',
        ]);
        
        $client45->submit($form45);

        //Comprobamos que se nos redirecciona a la edicion de material
        $para45 = $crawler45->filter('h1')->first()->text();
        $this->assertEquals("Editar Material",$para45);

    }

    public function testDelete(){
 
        //Insertamos un nuevo material
        $client50 = $this->createAuthorizedClient();
        $crawler50 = $client50->request('GET', '/material../new');
        $buttonCrawlerNode = $crawler50->selectButton('Guardar');
        $form50 = $buttonCrawlerNode->form();

        //Asignamos valores al INSERT
        $form50 = $buttonCrawlerNode->form([
            'material[Nombre]'    => 'calculadora',
            'material[Descripcion]' => 'test50 deleted',
            'material[Precio]' => '10',
            'material[Usuario]' => NULL,
            'material[Tipo]' => 'cientifica',
            'material[Oficina]' => '1',
        ]);
        
        $client50->submit($form50);

        //Testeamos si se ha introducido correctamente

        $material50 = self::$kernel->getContainer()->get('doctrine')->getRepository(
            Material::class)->findOneBy(array(
                'Nombre'    => 'calculadora',
                'Descripcion' => 'test50 deleted',
                'Precio' => '10',
                'Usuario' => NULL,
                'Tipo' => 'cientifica',
                'Oficina' => '1',
            ));

        $id_material50 = $material50->getId();
        
        $this->assertNotNull($id_material50);

        //Creamos otro cliente que elimine el material introducido anteriormente
        
        $client51 = $this->createAuthorizedClient();
        $crawler51 = $client51->request('GET', "/material/$id_material50/edit");
        $buttonCrawlerNode = $crawler51->selectButton('Eliminar');
        $form51 = $buttonCrawlerNode->form();

        $client51->submit($form51);

        //Con otro cliente intentamos mostrar la informacion del material eliminado
        $client52 = $this->createAuthorizedClient();
        $crawler52 = $client52->request('GET', "/material/$id_material50/show");
        $this->assertEquals(
            404,
            $client52->getResponse()->getStatusCode()
        );
        $this->assertTrue($client52->getResponse()->isNotFound());
    }



}