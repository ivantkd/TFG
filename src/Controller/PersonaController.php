<?php

namespace App\Controller;

use App\Entity\Persona;
use App\Entity\Usuario;
use App\Form\PersonaType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\PersonaRepository;
use App\Repository\UsuarioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Esta clase es el controlador para las personas.
 * La entidad Personas gestiona la información personal de un usuario en la bbdd.
 * @Route("/persona")
 * @author Ivan&Sergio
 */
class PersonaController extends AbstractController
{
    /**
     * Devuelve toda la información de Personas que haya en la bbdd
     * @Route("/", name="persona_index", methods={"GET"})
     * @param personaRepository Inyeccion de dependencias de la clase PersonaRepository
     * @return personas Renderiza todas las personas registradas en la bbdd
     */
    public function index(PersonaRepository $personaRepository): Response
    {
        return $this->render('persona/index.html.twig', [
            'personas' => $personaRepository->findAll(),
        ]);
    }    

    /**
     * Crea una nueva persona a la bbdd
     * @Route("/new", name="persona_new", methods={"GET","POST"})
     * @param request Inyeccion de dependencias de la clase Request de symfony
     * @return persona Renderiza la informacion de la persona a registrar
     * @return form Renderiza el formulario para la creacion de la persona
     */
    public function new(Request $request): Response
    {
        $persona = new Persona();
        $form = $this->createForm(PersonaType::class, $persona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($persona);
            $entityManager->flush();

            return $this->redirectToRoute('persona_index');
        }

        return $this->render('persona/new.html.twig', [
            'persona' => $persona,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Muestra la información de la persona seleccionada
     * @Route("/{Numero_Empleado}", name="persona_show", methods={"GET"})
     * @param persona Inyeccion de dependencias de la clase Entity/Persona
     * @return persona Renderiza la información de la persona seleccionada
     */
    public function show(Persona $persona): Response
    {
        return $this->render('persona/show.html.twig', [
            'persona' => $persona,
        ]);
    }

    /**
     * Edita la informacion de la persona seleccionada
     * @Route("/{Numero_Empleado}/edit", name="persona_edit", methods={"GET","POST"})
     * @param request Inyeccion de dependencias de la clase Request de symfony
     * @param persona Inyeccion de dependencias de la clase Entity/Persona
     * @return persona Renderiza la informacion editada de la persona seleccionada
     * @return form Renderiza el formulario para actualizar la informacion de la persona seleccionada
     */
    public function edit(Request $request, Persona $persona): Response
    {
        $form = $this->createForm(PersonaType::class, $persona);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('persona_index', [
                'Numero_Empleado' => $persona->getNumero_Empleado(),
            ]);
        }

        return $this->render('persona/edit.html.twig', [
            'persona' => $persona,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Elimina la persona seleccionada
     * @Route("/{Numero_Empleado}", name="persona_delete", methods={"DELETE"})
     * @param request Inyeccion de dependencias de la clase Request de symfony
     * @param persona Inyeccion de dependencias de la clase Entity/Persona
     */
    public function delete(Request $request, Persona $persona): Response
    {
        if ($this->isCsrfTokenValid('delete'.$persona->getNumero_Empleado(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($persona);
            $entityManager->flush();
        }

        return $this->redirectToRoute('persona_index');
    }
}
