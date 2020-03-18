<?php

namespace App\Controller;

use App\Entity\Mensajes;
use App\Form\MensajesType;
use App\Repository\MensajesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Este es el controlador de los mensajes generados en la funcionalidad de Feed.
 * @Route("/mensajes")
 * @author Ivan
 */
class MensajesController extends AbstractController
{
    /**
     * Devuelve todos los mensajes almacenados en la bbdd
     * @Route("/", name="mensajes_index", methods={"GET"})
     * @param mensajesRepository Inyeccion de dependencias de la clase MensajesRepository
     * @return mensajes Renderiza todos los mensajes guardados en la bbdd
     */
    public function index(MensajesRepository $mensajesRepository): Response
    {
        return $this->render('mensajes/index.html.twig', [
            'mensajes' => $mensajesRepository->findAll(),
        ]);
    }

    /**
     * Crea un nuevo mensaje firmado por el usuario logeado
     * @Route("/new", name="mensajes_new", methods={"GET","POST"})
     * @param request Inyeccion de dependencias de la clase Request
     * @return mensaje Renderiza la informacion del mensaje creado
     * @return form Renderiza el formulario para crear el nuevo mensaje
     */
    public function new(Request $request): Response
    {   
        $usr= $this->get('security.token_storage')->getToken()->getUser();
        $usr->getUsername();

        $mensaje = new Mensajes();
        $form = $this->createForm(MensajesType::class, $mensaje);
        $form->handleRequest($request);
        $mensaje->setmailusuario($usr);



        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mensaje);
            $entityManager->flush();

            return $this->redirectToRoute('mensajes_index');
        }

        return $this->render('mensajes/new.html.twig', [
            'mensaje' => $mensaje,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Muestra la informacion de un mensaje seleccionado
     * @Route("/{id}", name="mensajes_show", methods={"GET"})
     * @param mensaje Inyeccion de dependencias de la clase Entity/Mensajes
     * @return mensaje Renderiza la informacion a mostrar del mensaje seleccionado
     */
    public function show(Mensajes $mensaje): Response
    {
        return $this->render('mensajes/show.html.twig', [
            'mensaje' => $mensaje,
        ]);
    }
    

    /**
     * Elimina un mensaje seleccionado
     * @Route("/{id}", name="mensajes_delete", methods={"DELETE"})
     * @param request Inyeccion de dependencias de la clase Request de symfony
     * @param mensaje Inyeccion de dependencias de la clase Entity/Mensajes
     */
    public function delete(Request $request, Mensajes $mensaje): Response
    {
        if ($this->isCsrfTokenValid('delete'.$mensaje->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($mensaje);
            $entityManager->flush();
        }

        return $this->redirectToRoute('mensajes_index');
    }
}
