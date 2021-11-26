<?php

namespace App\Controller;

use App\DataTransfertObject\Credentials;
use App\Form\LoginType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{

    /**
     * @Route("/login", name="security_login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $form = $this->createForm(LoginType::class, new Credentials($authenticationUtils->getLastUsername()));

        $error = $authenticationUtils->getLastAuthenticationError();
        if(null !== $error) {
            $form->addError(
                new FormError($error->getMessage())
            );
        }

        return $this->render("security/login.html.twig", [
            'form' => $form->createView()
        ]);
    }
}