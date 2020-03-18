<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Entity\Usuario;
use App\Form\BookingType;
use App\Repository\BookingRepository;
use App\Repository\UsuarioRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * Controlador que gestiona el calendario
 * @Route("/booking")
 * @author Ivan
 */
class BookingController extends AbstractController
{
    /**
     * Devuelve toda la informacion del calendario
     * @Route("/", name="booking_index", methods={"GET"})
     * @param bookingRepository Inyeccion de dependencias de la clase BookingRepository
     * @return bookings Renderiza toda la información de booking
     */
    public function index(BookingRepository $bookingRepository): Response
    {
        return $this->render('booking/index.html.twig', [
            'bookings' => $bookingRepository->findAll(),
        ]);
    }

     /**
     * @Route("/calendar", name="booking_calendar", methods={"GET"})
     */
    public function calendar(): Response
    {
        return $this->render('booking/calendar.html.twig');
    }
    
    /**
     * Crear un nuevo evento en el calendario
     * @Route("/new", name="booking_new", methods={"GET","POST"})
     * @param request Inyeccion de dependencias de la clase Request de symfony
     * @return booking Renderiza la informacion del nuevo evento registrado en el calendario
     * @return form Renderiza el formulario para el registro del evento
     */
    public function new(Request $request): Response
    {   
        $usr= $this->get('security.token_storage')->getToken()->getUser();
        $usr->getUsername();

    

        $booking = new Booking();
        $form = $this->createForm(BookingType::class, $booking);
        $booking->setmail_usuario($usr);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($booking);
            $entityManager->flush();

            return $this->redirectToRoute('booking_index');
        }

        return $this->render('booking/new.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Muestra la informacion de un evento seleccionado
     * @Route("/{id}", name="booking_show", methods={"GET"})
     * @param booking Inyeccion de dependencias de la clase Entity/Booking
     * @return booking Renderiza la informacion del evento seleccionado
     */
    public function show(Booking $booking): Response
    {   

        return $this->render('booking/show.html.twig', [
            'booking' => $booking,
        
        ]);
    }

    /**
     * Edita la informacion del evento seleccionado
     * @Route("/{id}/edit", name="booking_edit", methods={"GET","POST"})
     * @param request Inyeccion de dependencias de la clase Request de symfony
     * @param booking Inyeccion de dependencias de la clase Entity/Booking
     * @return booking Renderiza la informacion editada del evento seleccionado
     * @return form Renderiza el formulario para editar la informacion del evento seleccionado
     */
    public function edit(Request $request, Booking $booking): Response
    {
        $form = $this->createForm(BookingType::class, $booking);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('booking_index', [
                'id' => $booking->getId(),
            ]);
        }

        return $this->render('booking/edit.html.twig', [
            'booking' => $booking,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Eliminar un evento seleccionado
     * @Route("/{id}", name="booking_delete", methods={"DELETE"})
     * @param request Inyeccion de dependencias de la clase Request de symfony
     * @param booking Inyeccion de dependencias de la clase Entity/Booking
     */
    public function delete(Request $request, Booking $booking): Response
    {
        if ($this->isCsrfTokenValid('delete'.$booking->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($booking);
            $entityManager->flush();
        }

        return $this->redirectToRoute('booking_index');
    }
}
