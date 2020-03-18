<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
/**
 * Esta clase se encarga del login del usuario, para setear la sesion.
 * @author Ivan
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="app_login")
     * @param authenticationUtils Inyeccion de dependencias de la clase AuthenticationUtils de symfony
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        
        $securityContext = $this->container->get('security.authorization_checker');


        if ($securityContext->isGranted('ROLE_USER')) {
            
            return $this->redirectToRoute('main');
            
    }
    

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }
}
