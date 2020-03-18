<?php

namespace App\Controller;

use App\Entity\Tickets;
use App\Entity\Usuario;
use App\Form\TicketsType;
use App\Form\TicketsResolvedType;
use App\Repository\TicketsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * Esta clase es el controlador de los tickets.
 * Tiene todos los métodos necesarios para trabajar con tickets en la bbdd.
 * @Route("/tickets")
 * @author Ivan
 */
class TicketsController extends AbstractController
{
    /**
     * Query de todos los tickets creados en la bbdd.
     * @Route("/", name="tickets_index", methods={"GET"})
     * @param ticketsRepository Inyeccion de dependencias de la clase TicketsRepository
     * @return tickets Renderiza la informacion de los tickets al template
     */
    public function index(TicketsRepository $ticketsRepository): Response
    {
        return $this->render('tickets/index.html.twig', [
            'tickets' => $ticketsRepository->findAll(),
        ]);
    }

    /**
     * Crea un nuevo ticket en la bbdd.
     * @Route("/new", name="tickets_new", methods={"GET","POST"})
     * @param request Inyeccion de dependencias de la clase Request de symfony
     * @return ticket Renderiza la informacion del nuevo ticket al template
     * @return form Renderiza el formulario para la creación del ticket
     * @return mail Renderiza el mail del usuario que está creando el ticket
     */
    public function new(Request $request): Response
    {
        $ticket = new Tickets();
        $usr= $this->get('security.token_storage')->getToken()->getUser();
        $ticket->setUsermail($usr);
        $form = $this->createForm(TicketsType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($ticket);
            $entityManager->flush();

            return $this->redirectToRoute('ticketsbyUser');
        }

        return $this->render('tickets/new.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
            'mail' => $usr,
        ]);
    }

    /**
     * Muestra la información del ticket en concreto
     * @Route("/{id}", name="tickets_show", methods={"GET"})
     * @param ticket Inyeccion de dependencias de la clase Entity/Tickets
     * @return ticket Renderiza la información del ticket en concreto
     */
    public function show(Tickets $ticket): Response
    {
        return $this->render('tickets/show.html.twig', [
            'ticket' => $ticket,
        ]);
    }

    /**
     * 
     * @codeCoverageIgnore
     * Edita la informacion del ticket especificado
     * @Route("/{id}/edit", name="tickets_edit", methods={"GET","POST"})
     * @param request Inyección de dependencias de la clase Request de symfony
     * @param ticket Inyeccion de dependencias de la clase Entity/Tickets
     * @return id Renderiza el identificador del ticket seleccionado
     * @return ticket Renderiza la información del ticket seleccionado
     * @return form Renderiza el formulario para editar el ticket seleccionado
     */
    public function edit(Request $request, Tickets $ticket): Response
    {
        $form = $this->createForm(TicketsType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tickets_index', [
                'id' => $ticket->getId(),
            ]);
        }

        return $this->render('tickets/edit.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Muestra los tickets que están resueltos.
     * @Route("../ticketsResolved", name="ticketsbyresolved", methods={"GET", "POST"})
     * @param ticketsRepository Inyeccion de dependencias de la clase TicketsRepository
     * @return tickets Renderiza todos los tickets con el campo solved en yes
     */
    public function ticketsResolved(TicketsRepository $ticketsRepository): Response
    {
        return $this->render('tickets/index.html.twig', [
            'tickets' => $ticketsRepository->findby(['Solved' => 'si']),
        ]);
    }

    /**
     * Muestra los tickets que no están resueltos.
     * @Route("../ticketsNotResolved", name="ticketsbynotresolved", methods={"GET", "POST"})
     * @param ticketsRepository Inyeccion de dependencias de la clase TicketsRepository
     * @return tickets Renderiza todos los tickets con el campo solved en no
     */
    public function ticketsNotResolved(TicketsRepository $ticketsRepository): Response
    {
        return $this->render('tickets/index.html.twig', [
            'tickets' => $ticketsRepository->findby(['Solved' => 'no']),
        ]);
    }

    /**
     * Muestra todos los tickets asociados al usuario logeado en sesion.
     * @Route("../ticketsbyUser", name="ticketsbyUser", methods={"GET", "POST"})
     * @param ticketsRepository Inyeccion de dependencias de la clase TicketsRepository
     * @return tickets Renderiza todos los tickets asociados al mail es
     */
    public function ticketsByUser(TicketsRepository $ticketsRepository): Response
    {
        $usr= $this->get('security.token_storage')->getToken()->getUser();
        return $this->render('tickets/index.html.twig', [
            'tickets' => $ticketsRepository->findby(['usermail' => $usr]),
        ]);
    }

    
    /**
     * Resuelve los tickets abiertos por los usuarios
     * @Route("/{id}/resolve", name="tickets_resolve", methods={"GET","POST"})
     * @param request Inyeccion de dependencias de la clase Request de symfony
     * @param ticket Inyeccion de dependencias de la clase Entity/Tickets
     * @return id Renderiza el id del ticket resuelto
     * @return ticket Renderiza la información del ticket seleccionado
     * @return form Renderiza el formulario para la resolución del ticket
     */
    public function resolve(Request $request, Tickets $ticket): Response
    {
        $form = $this->createForm(TicketsResolvedType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('tickets_index', [
                'id' => $ticket->getId(),
            ]);
        }

        return $this->render('tickets/resolve.html.twig', [
            'ticket' => $ticket,
            'form' => $form->createView(),
        ]);
    }


    /**
     * Elimina el ticket seleccionado
     * @Route("/{id}", name="tickets_delete", methods={"DELETE"})
     * @param request Inyeccion de dependencias de la clase Request de symfony
     * @param ticket Inyeccion de dependencias de la clase Entity/Tickets
     */
    public function delete(Request $request, Tickets $ticket): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ticket->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($ticket);
            $entityManager->flush();
        }

        return $this->redirectToRoute('tickets_index');
    }
}
