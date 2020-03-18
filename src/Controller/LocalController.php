<?php

namespace App\Controller;

use App\Entity\Local;
use App\Form\LocalType;
use App\Repository\LocalRepository;
use App\Repository\DepartamentoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/localdepartamento")
 * Controlador para los locales ingresados en la bbdd
 * @author Ivan&Sergio
 */
class LocalController extends AbstractController
{
    /**
     * Obtiene todos los locales guardados en la bbdd
     * @Route("/", name="local_index", methods={"GET"})
     * @param localRepository Inyeccion de dependencias de la clase LocalRepository
     * @return locals Renderiza la informacion de todos los locales
     */
    public function index(LocalRepository $localRepository, DepartamentoRepository $dep): Response
    {
        return $this->render('local/index.html.twig', [
            'locals' => $localRepository->findAll(),
            'departamentos' => $dep->findAll(),
        ]);
    }

    /**
     * Crear un nuevo local en la bbdd
     * @Route("/new", name="local_new", methods={"GET","POST"})
     * @param request Inyeccion de dependencias de la clase Request de symfony
     * @return local Renderiza la informacion del nuevo local
     * @return form Renderiza el formulario con la informacion del nuevo local
     */
    public function new(Request $request): Response
    {
        $local = new Local();
        $form = $this->createForm(LocalType::class, $local);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($local);
            $entityManager->flush();

            return $this->redirectToRoute('local_index');
        }

        return $this->render('local/new.html.twig', [
            'local' => $local,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Muestra la informacion del local seleccionado
     * @Route("/{id}", name="local_show", methods={"GET"})
     * @param local Inyeccion de dependencias de la clase Entity/Local
     * @return local Renderiza la informacion del local seleccionado
     */
    public function show(Local $local): Response
    {
        return $this->render('local/show.html.twig', [
            'local' => $local,
        ]);
    }

    /**
     * Editar la información del local seleccionado
     * @Route("/{id}/edit", name="local_edit", methods={"GET","POST"})
     * @param request Inyeccion de dependencias de la clase Request de symfony
     * @param local Inyeccion de dependencias de la clase Entity/Local
     * @return local Renderiza la información editada del local seleccionado
     * @return form Renderiza el formulario para la informacion editada
     */
    public function edit(Request $request, Local $local): Response
    {
        $form = $this->createForm(LocalType::class, $local);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('local_index', [
                'id' => $local->getId(),
            ]);
        }

        return $this->render('local/edit.html.twig', [
            'local' => $local,
            'form' => $form->createView(),
        ]);
    }

    /**
     *
     * @codeCoverageIgnore
     * Eliminar un local seleccionado
     * @Route("/{id}", name="local_delete", methods={"DELETE"})
     * @param request Inyeccion de dependencias de la clase Request de symfony
     * @param local Inyeccion de dependencias de la clase Entity/Local
     */
    public function delete(Request $request, Local $local): Response
    {
        if ($this->isCsrfTokenValid('delete'.$local->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($local);
            $entityManager->flush();
        }

        return $this->redirectToRoute('local_index');
    }
}
