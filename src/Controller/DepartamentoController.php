<?php

namespace App\Controller;

use App\Entity\Departamento;
use App\Form\DepartamentoType;
use App\Repository\DepartamentoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * El controlador para gestionar los departamentos registrados en la bbdd
 * @author Ivan
 * @Route("/departamentolocal")
 */
class DepartamentoController extends AbstractController
{
    /**
     * Devuelve todos los departamentos guardados en la bbdd
     * @Route("/", name="departamento_index", methods={"GET"})
     * @param departamentoRepository Inyeccion de dependencias de la clase DepartamentoRepository
     * @return departamentos Renderiza todos los departamentos almacenados en la bbdd
     */
    public function index(DepartamentoRepository $departamentoRepository): Response
    {
        return $this->render('departamento/index.html.twig', [
            'departamentos' => $departamentoRepository->findAll(),
        ]);
    }

    /**
     * Crear un nuevo departamento 
     * @Route("/new", name="departamento_new", methods={"GET","POST"})
     * @param request Inyeccion de dependencias de la clase Request de symfony
     * @return departamento Renderiza la informacion del nuevo departamento
     * @return form Renderiza el formulario para la creacion del nuevo departamento
     */
    public function new(Request $request): Response
    {
        $departamento = new Departamento();
        $form = $this->createForm(DepartamentoType::class, $departamento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($departamento);
            $entityManager->flush();

            return $this->redirectToRoute('departamento_index');
        }

        return $this->render('departamento/new.html.twig', [
            'departamento' => $departamento,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Mostrar la informacion del departamento seleccionado
     * @Route("/{Nombre}", name="departamento_show", methods={"GET"})
     * @param departamento Inyeccion de dependencias de la clase Entity/Departamento
     * @return departamento Renderiza la informacion del departamento seleccionado 
     */
    public function show(Departamento $departamento): Response
    {
        return $this->render('departamento/show.html.twig', [
            'departamento' => $departamento,
        ]);
    }

    /**
     * Edita la informacion del departamento seleccionado
     * @Route("/{Nombre}/edit", name="departamento_edit", methods={"GET","POST"})
     * @param request Inyeccion de dependencias de la clase Request
     * @param departamento Inyeccion de dependencias de la clase Entity/Departamento
     * @return departamento Renderiza la informacion editada del departamento seleccionado
     * @return form Renderiza el formulario para editar la informacion del departamento seleccionado
     */
    public function edit(Request $request, Departamento $departamento): Response
    {
        $form = $this->createForm(DepartamentoType::class, $departamento);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('departamento_index', [
                'Nombre' => $departamento->getNombre(),
            ]);
        }

        return $this->render('departamento/edit.html.twig', [
            'departamento' => $departamento,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Eliminar un departamento seleccionado
     * @Route("/{Nombre}", name="departamento_delete", methods={"DELETE"})
     * @param request Inyeccion de dependencias de la clase Request
     * @param departamento Inyeccion de dependencias de la clase Entity/Departamento
     */
    public function delete(Request $request, Departamento $departamento): Response
    {
        if ($this->isCsrfTokenValid('delete'.$departamento->getNombre(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($departamento);
            $entityManager->flush();
        }

        return $this->redirectToRoute('departamento_index');
    }
}
