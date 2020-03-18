<?php

namespace App\Controller;

use App\Entity\Clientes;
use App\Form\ClientesType;
use App\Repository\ClientesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controlador que gestiona los clientes almacenados en la bbdd
 * @Route("/clientes")
 * @author Ivan
 */
class ClientesController extends AbstractController
{
    /**
     * Devuelve todos los clientes almacenados en la bbdd
     * @Route("/", name="clientes_index", methods={"GET"})
     * @param clientesRepository Inyeccion de dependencias de la clase ClientesRepository
     * @return clientes Renderiza todos los clientes almacenados en la bbdd
     */
    public function index(ClientesRepository $clientesRepository): Response
    {
        return $this->render('clientes/index.html.twig', [
            'clientes' => $clientesRepository->findAll(),
        ]);
    }

    /**
     * Inserta un nuevo cliente en la bbdd
     * @Route("/new", name="clientes_new", methods={"GET","POST"})
     * @param request Inyeccion de dependencias de la clase Request de symfony
     * @return cliente Renderiza la informacion del nuevo cliente
     * @return form Renderiza el formulario para la creacion del nuevo cliente
     */
    public function new(Request $request): Response
    {
        $cliente = new Clientes();
        $form = $this->createForm(ClientesType::class, $cliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cliente);
            $entityManager->flush();

            return $this->redirectToRoute('proveedores_index');
        }

        return $this->render('clientes/new.html.twig', [
            'cliente' => $cliente,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Muestra la informacion del cliente seleccionado
     * @Route("/{id}", name="clientes_show", methods={"GET"})
     * @param cliente Inyeccion de dependencias de la clase Entity/Clientes
     * @return cliente Renderiza la informacion del cliente seleccionado
     */
    public function show(Clientes $cliente): Response
    {
        return $this->render('clientes/show.html.twig', [
            'cliente' => $cliente,
        ]);
    }

    /**
     * Editar la informacion del cliente seleccionado
     * @Route("/{id}/edit", name="clientes_edit", methods={"GET","POST"})
     * @param request Inyeccion de dependencias de la clase Request de symfony
     * @param cliente Inyeccion de dependencias de la clase Entity/Clientes
     * @return cliente Renderiza la información editada del cliente seleccionado
     * @return form Renderiza el formulario para editar la informacion del cliente
     */
    public function edit(Request $request, Clientes $cliente): Response
    {
        $form = $this->createForm(ClientesType::class, $cliente);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('proveedores_index', [
                'id' => $cliente->getId(),
            ]);
        }

        return $this->render('clientes/edit.html.twig', [
            'cliente' => $cliente,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Eliminar el cliente seleccionado
     * @Route("/{id}", name="clientes_delete", methods={"DELETE"})
     * @param request Inyeccion de dependencias de la clase Request de symfony
     * @param cliente Inyeccion de depndencias de la clase Entity/Clientes
     */
    public function delete(Request $request, Clientes $cliente): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cliente->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cliente);
            $entityManager->flush();
        }

        return $this->redirectToRoute('proveedores_index');
    }

    
    
}
