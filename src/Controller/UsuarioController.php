<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Entity\Tickets;
use App\Entity\Persona;
use App\Form\TicketsType;
use App\Form\UsuarioType;
use App\Repository\UsuarioRepository;
use App\Repository\TicketsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#Sergio: El siguiente use sirve para permitir el acceso a diferentes metodos de controladores.
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Esta clase es el controlador de usuario.
 * Tiene todos los métodos necesarios para poder trabajar con un usuario en la bbdd.
 * @Route("/usuario")
 * @author Ivan&Sergio
 */
class UsuarioController extends AbstractController
{

    
    /**
     * Query de todos los usuarios en la bbdd.
     * @Route("/", name="usuario_index", methods={"GET"})
     * @param usuarioRepository Inyeccion de dependencia de la clase UsuarioRepository
     * @return usuarios Renderiza la informacion de los usuarios al template
     */
    public function index(UsuarioRepository $usuarioRepository): Response
    {
        return $this->render('usuario/index.html.twig', [
            'usuarios' => $usuarioRepository->findAll(),
        ]);
    }

    /**
     * Query que nos devuelve los datos de perfil de un usuario en concreto. 
     * @Route("/{mail}", name="perfil", methods={"GET"})
     * @param usuario Inyeccion de dependencia de la clase Entity/Usuario
     * @param ticketsRepository Inyeccion de dependencia de la clase TicketsRepository
     * @param mail Inyeccion de dependencia de la clase TicketsRepository
     * @return usuario Renderiza la información del perfil del usuario
     * @return tickets Renderiza la información de los ticket del usuario especificado
     */
    public function perfil(Usuario $usuario, TicketsRepository $ticketsRepository, $mail): Response
    {  

        $repository = $this->getDoctrine()->getManager();
       
        $usuario = $repository->getRepository(Usuario::class)->find($mail);

        //$persona= $repository->getRepository(Persona::class)->findOneBy([
        //    'correo' => $mail,
            
        //]);
        
        return $this->render('usuario/prueba.html.twig', [
           'usuario' => $usuario,
           'tickets' => $ticketsRepository->findby([ 'usermail' => $mail]),
        ]);

    }
    

    /**
     * Crear nuevo usuario login en la base de datos.
     * Hace un encode del password proporcionado con el algoritmo bcrypt.
     * @Route("../new", name="usuario_new", methods={"GET","POST"})
     * @param request Inyeccion de dependencia de la clase Request de symfony
     * @param encoder Inyeccion de dependencia de la clase UserPasswordEncoderInterface de symfony.
     * @return usuario Renderiza la información del usuario
     * @return form Renderiza el formulario para la creación del usuario
     */
    public function new(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $usuario = new Usuario();
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            


            $file = $request->files->get('usuario')['imagen'];
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();
            
            $persona1 = new Persona();
            $persona = $usuario->getNumeroEmpleado();
            $numero = $persona->getNumero_Empleado();

            $us = $this->getDoctrine()
            ->getRepository(Usuario::class)
            ->findByNumeroEmpleado($numero);
    
                if ($us){

                    return $this->redirectToRoute('usuario_index');

                    }
          
            try {
                $file->move(
                    $this->getParameter('directorio_imagenes'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            $usuario->setImagen($fileName);

            $plainPassword = $usuario->getPassword(); 
            $hashedPassword = $encoder->encodePassword($usuario, $plainPassword);
            $usuario->setPassword($hashedPassword);

            $entityManager->persist($usuario);
            $entityManager->flush();

            return $this->redirectToRoute('usuario_index');
        }

        return $this->render('usuario/new.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
        ]);
    }
    /**
     * Metodo que genera un identificador unico para el nombre de la imagen de perfil
     * @return md5 Identificador único
     */
    private function generateUniqueFileName()
    {
        // md5() reduces the similarity of the file names generated by
        // uniqid(), which is based on timestamps
        return md5(uniqid());
    }
    /**
     * Muestra los datos de un usuario en concreto.
     * @Route("/show/{mail}", name="usuario_show", methods={"GET"})
     * @param usuario Inyeccion de dependencias de la clase Entity/Usuario
     * @return usuario Renderiza la información del usuario
     */
    public function show(Usuario $usuario): Response
    {
        return $this->render('usuario/show.html.twig', [
            'usuario' => $usuario,
        ]);
    }

    /**
     * Edita un usuario en concreto.
     * @Route("/{mail}/edit", name="usuario_edit", methods={"GET","POST"})
     * @param request Inyeccion de dependencia de la clase Request de symfony.
     * @param usuario Inyeccion de dependencia de la clase Entity/Usuario
     * @param encoder Inyeccion de dependencia de la clase UserPasswordEncoderInterface
     * @return mail Renderiza el mail del usuario especificado
     * @return usuario Renderiza la información del usuario especificado
     * @return form Renderiza el formulario que se utilizará para editar el usuario especificado
     */
    public function edit(Request $request, Usuario $usuario, UserPasswordEncoderInterface $encoder): Response
    {
        $form = $this->createForm(UsuarioType::class, $usuario);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $file = $request->files->get('usuario')['imagen'];
           
            $fileName = $this->generateUniqueFileName().'.'.$file->guessExtension();

            try {
                $file->move(
                    $this->getParameter('directorio_imagenes'),
                    $fileName
                );
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            $usuario->setImagen($fileName);
            $plainPassword = $usuario->getPassword(); 
            $hashedPassword = $encoder->encodePassword($usuario, $plainPassword);
            $usuario->setPassword($hashedPassword);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('usuario_index', [
                'mail' => $usuario->getMail(),
            ]);
        }

        return $this->render('usuario/edit.html.twig', [
            'usuario' => $usuario,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Elimina de la bbdd un usuario en concreto.
     * @Route("/{mail}", name="usuario_delete", methods={"DELETE"})
     * @param request Inyeccion de dependencias de la clase Request de symfony
     * @param usuario Inyeccion de dependencias de la clase Entity/Usuario 
     */
    public function delete(Request $request, Usuario $usuario): Response
    {
        if ($this->isCsrfTokenValid('delete'.$usuario->getMail(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($usuario);
            $entityManager->flush();
        }

        return $this->redirectToRoute('usuario_index');
    }


    /**
    * Realiza el cierre de la sesion establecida
    * @Route("/logout", name="logout")
    * @param request Inyeccion de dependencias de la clase Request de symfony
    */
    public function logoutAction(Request $request)
    {
    // UNREACHABLE CODE
    }
}
