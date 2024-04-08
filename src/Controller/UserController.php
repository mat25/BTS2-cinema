<?php

namespace App\Controller;

use App\Form\InscriptionFormType;
use App\Services\ApiUser;
use http\Header;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function inscription(ApiUser $apiUser,Request $request): Response
    {
        $response = "";
        $erreur = "";
        $form = $this->createForm(InscriptionFormType::class);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Traitez le formulaire
            $donnees = $form->getData();

            $email = $donnees["email"];
            $password = $donnees["password"];
            $response = $apiUser->inscription($email,$password);
            if (!isset($response["erreur"])) {
                return $this->redirectToRoute('app_home');
            } else {
                // $form->addError(new FormError($response["erreur"]));
                $erreur = $response["erreur"];
            }
        }

        // Si il y a des erreur on renvoie l'erreur'
        return $this->render('user/inscription.html.twig', [
            'erreur' => $erreur,
            'form' => $form->createView(),
        ]);
    }
}
