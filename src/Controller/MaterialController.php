<?php

namespace App\Controller;

use App\Entity\Usuario;
use App\Repository\UsuarioRepository;

use App\Entity\Material;
use App\Entity\Local;
use App\Form\MaterialType;
use App\Form\LocalType;
use App\Repository\MaterialRepository;
use App\Repository\LocalRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Este es el controlador para el material en la bbdd.
 * @Route("/material")
 * @author Sergi
 */
class MaterialController extends AbstractController
{
    /**
     * Devuelve todos los materiales por usuario y por local
     * @Route("/listuser", name="material_index", methods={"GET"})
     * @param materialRepository Inyeccion de dependencias de la clase MaterialRepository
     * @param a Inyeccion de dependencias de la clase LocalRepository
     * @return materials Todos los materiales de la bbdd
     * @return materialsAsigned Todos los materiales asignados a un usuario
     * @return local Todos los locales que tienen materiales
     */
    public function index(MaterialRepository $materialRepository, LocalRepository $a): Response
    {
        $userId= $this->get('security.token_storage')->getToken()->getUser();


        return $this->render('material/index.html.twig', [
            'materials' => $materialRepository->findBy(['Usuario' => NULL]),
            'materialsAsigned' => $materialRepository->findBy(['Usuario' => $userId]),
            'local' =>  $a->findAll(),
        ]);
    }

    /**
     * Devuelve los materiales disponibles y no disponibles en funcion de si han sido cogidos por un usario o no.
     * @Route("/listadmin", name="listmaterial_index", methods={"GET"})
     * @param materialRepository Inyeccion de dependencias de la clase MaterialRepository
     * @param a Inyeccion de depndencias de la clase LocalRepository
     * @return materials Todos los materiales de la bbdd
     * @return materialesNoDisponibles Todos los materiales que no estan disponibles para poder utilizar
     * @return local Todos los locales que tienen materiales
     */
    public function listMaterial(MaterialRepository $materialRepository, LocalRepository $a): Response
    {
        return $this->render('material/listmaterial.html.twig', [
            'materials' => $materialRepository->findBy(['disponible' => 'yes']),
            'materialesNoDisponibles' => $materialRepository->findBy(['disponible' => 'no']),
            'local' =>  $a->findAll(),
        ]);
    }

    
     /**
     * Devuelve los materiales en función de la oficina seleccionada
     * @Route("/listuser/{id}", name="material_bylocal", methods={"GET","POST"})
     * @param materialRepository Inyeccion de dependencias de la clase MaterialRepository
     * @param a Inyeccion de dependencias de la clase LocalRepository
     * @param id Inyeccion de dependencias de la clase LocalRepository
     * @return materials Todos los materiales de la bbdd
     * @return materialesAsigned Todos los materiales asignados a un usuario
     * @return local Todos los locales que tienen materiales
     */
    public function indexLocal(MaterialRepository $materialRepository, LocalRepository $a, $id): Response
    {
        $userId= $this->get('security.token_storage')->getToken()->getUser();
        
        return $this->render('material/index.html.twig', [
            'materials' => $materialRepository->findBy(['Oficina' => $id]),
            'materialsAsigned' => $materialRepository->findBy(['Usuario' => $userId]),
            'local' =>  $a->findAll(),
        ]);
    }

    /**
     * Lista todo el material tanto disponible como no disponible por local
     * @Route("/listadmin/{id}", name="listmaterial_bylocal", methods={"GET"})
     * @param materialRepository Inyeccion de dependencias de la clase MaterialRepository
     * @param a Inyeccion de dependencias de la clase LocalRepository
     * @param id Inyeccion de dependencias de la clase LocalRepository
     * @return materials Renderiza todos los materiales disponibles y por oficina
     * @return local Renderiza todos los locales 
     * @return materialesNoDisponibles Renderiza todos los materiales no disponibles y por oficina
     */
    public function listMaterialByLocal(MaterialRepository $materialRepository, LocalRepository $a, $id): Response
    {
        return $this->render('material/listmaterial.html.twig', [
            'materials' => $materialRepository->findBy(['disponible' => 'yes','Oficina' => $id]),
            'local' =>  $a->findAll(),
            'materialesNoDisponibles' => $materialRepository->findBy(['disponible' => 'no','Oficina' => $id]),
        ]);
    }
    
    /**
     * Asigna un material seleccionado al usuario logeado
     * @Route("/{id}/asignar", name="asignar_material", methods={"GET", "POST"})
     * @param material Inyeccion de dependencias de la clase Entity/Material
     * @param usuarioRepository Inyeccion de dependencias de la clase UsuarioRepository
     */
    public function asignarUsuarioMaterial(Material $material, UsuarioRepository $usuarioRepository): Response
    {

        $userId= $this->get('security.token_storage')->getToken()->getUser();
        $entityManager = $this->getDoctrine()->getManager();
        $material->setUsuario($userId); 
        $material->setDisponible("no");
        $entityManager->persist($material);
        $entityManager->flush();
        return $this->redirectToRoute('material_index');
    }
    
    /**
     * Devolver un material registrado por un usuario
     * @Route("/{id}/devolver", name="devolver_material", methods={"GET", "POST"})
     * @param material Inyeccion de dependencias de la clase Entity/Material
     */
    public function devolverMaterial(Material $material): Response
    {
        $entityManager = $this->getDoctrine()->getManager();
        $material->setUsuario(NULL); 
        $material->setDisponible("yes");
        $entityManager->persist($material);
        $entityManager->flush();
        return $this->redirectToRoute('material_index');
    }

    /**
     * Crear un nuevo material
     * @Route("../new", name="material_new", methods={"GET","POST"})
     * @param request Inyeccion de dependencias de la clase Request de symfony
     * @return material Renderiza la informacion del nuevo material
     * @return form Renderiza el formulario con la informacion del nuevo material
     */
    public function new(Request $request): Response
    {
        $material = new Material();
        $form = $this->createForm(MaterialType::class, $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($material);
            $entityManager->flush();

            return $this->redirectToRoute('material_index');
        }

        return $this->render('material/new.html.twig', [
            'material' => $material,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Mostrar la información de un material seleccionado
     * @Route("/{id}/show", name="material_show", methods={"GET"})
     * @param material Inyeccion de dependencias de la clase Entity/Material
     * @return material Renderiza la informacion del material seleccionado
     */
    public function show(Material $material): Response
    {
        return $this->render('material/show.html.twig', [
            'material' => $material,
        ]);
    }

    /**
     * Editar la informacion de un material seleccionado
     * @Route("/{id}/edit", name="material_edit", methods={"GET","POST"})
     * @param request Inyeccion de dependencias de la clase Request de symfony
     * @param material Inyeccion de dependencias de la clase Entity/Material
     * @return material Renderiza la informacion editada del material seleccionado
     * @return form Renderiza el formulalrio con la informacion editada del material seleccionado
     */
    public function edit(Request $request, Material $material): Response
    {
        $form = $this->createForm(MaterialType::class, $material);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('material_index', [
                'id' => $material->getId(),
            ]);
        }

        return $this->render('material/edit.html.twig', [
            'material' => $material,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Elimina un material seleccionado
     * @Route("/{id}", name="material_delete", methods={"DELETE"})
     * @param request Inyeccion de dependencias de la clase Request de symfony
     * @param material Inyeccion de dependencias de la clase Entity/Material
     */
    public function delete(Request $request, Material $material): Response
    {
        if ($this->isCsrfTokenValid('delete'.$material->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($material);
            $entityManager->flush();
        }

        return $this->redirectToRoute('material_index');
    }
}
