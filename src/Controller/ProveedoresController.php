<?php

namespace App\Controller;

use App\Entity\Proveedores;
use App\Entity\Clientes;
use App\Form\ProveedoresType;
use App\Form\ClientesType;
use App\Repository\ProveedoresRepository;
use App\Repository\ClientesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Esta clase es el controlador de los proveedores.
 * Tiene todos los metodos necesarios para poder trabajar con los proveedores guardados en la bbdd.
 * @Route("/proveedores")
 * @author Ivan
 */
class ProveedoresController extends AbstractController
{
    /**
     * Muestra todos los proveedores y clientes guardados en la bbdd
     * @Route("/", name="proveedores_index", methods={"GET"})
     * @param proveedoresRepository Inyeccion de dependencias de la clase ProveedoresRepository 
     * @param a Inyeccion de dependencias de la clase ClientesRepository
     * @return proveedores Renderiza todos los proveedores de la bbdd
     * @return clientes Renderiza todos los clientes de la bbdd
     */
    public function index(ProveedoresRepository $proveedoresRepository, ClientesRepository $a): Response
    {
        return $this->render('proveedores/index.html.twig', [
            'proveedores' => $proveedoresRepository->findAll(),
            'clientes' => $a->findAll()
        ]);
    }

    /**
     * Crea un nuevo proveedor en la bbdd
     * @Route("/new", name="proveedores_new", methods={"GET","POST"})
     * @param request Inyeccion de dependencias de la clase Request de symfony
     * @return proveedore Renderiza los datos del proveedor a introducir en la bbdd
     * @return form Renderiza el formulario para la creacion del proveedor
     */
    public function new(Request $request): Response
    {
        $proveedore = new Proveedores();
        $form = $this->createForm(ProveedoresType::class, $proveedore);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($proveedore);
            $entityManager->flush();

            return $this->redirectToRoute('proveedores_index');
        }

        return $this->render('proveedores/new.html.twig', [
            'proveedore' => $proveedore,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Muestra la información del proveedor seleccionado
     * @Route("/{id}", name="proveedores_show", methods={"GET"})
     * @param proveedore Inyeccion de dependencias de la clase Entity/Proveedores
     */
    public function show(Proveedores $proveedore): Response
    {
        return $this->render('proveedores/show.html.twig', [
            'proveedore' => $proveedore,
        ]);
    }

    /**
     * Edita la informacion de un proveedor seleccionado
     * @Route("/{id}/edit", name="proveedores_edit", methods={"GET","POST"})
     * @param request Inyeccion de dependencias de la clase Request de symfony
     * @param proveedore Inyeccion de dependencias de la clase Entity/Proveedores
     * @return id Redirige al index el identificador del proveedor seleccionado
     * @return proveedore Renderiza la informacion editada del proveedor seleccionado 
     * @return form Renderiza el formulario para editar la informacion del proveedor
     */
    public function edit(Request $request, Proveedores $proveedore): Response
    {
        $form = $this->createForm(ProveedoresType::class, $proveedore);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('proveedores_index', [
                'id' => $proveedore->getId(),
            ]);
        }

        return $this->render('proveedores/edit.html.twig', [
            'proveedore' => $proveedore,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Elimina un proveedor seleccionado
     * @Route("/{id}", name="proveedores_delete", methods={"DELETE"})
     * @param request Inyeccion de dependencias de la clase Request de symfony
     * @param proveedore Inyeccion de dependencias de la clase Entity/Proveedores
     */
    public function delete(Request $request, Proveedores $proveedore): Response
    {
        if ($this->isCsrfTokenValid('delete'.$proveedore->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($proveedore);
            $entityManager->flush();
        }

        return $this->redirectToRoute('proveedores_index');
    }

    
}
