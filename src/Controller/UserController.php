<?php

namespace App\Controller;

use App\Form\ConnexionFormType;
use App\Form\InscriptionFormType;
use App\Services\ApiUser;
use App\Services\UserConnecter;
use http\Header;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function inscription(ApiUser $apiUser,Request $request,SessionInterface $session,UserConnecter $userConnecter): Response
    {

        $form = $this->createForm(InscriptionFormType::class);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Traitez le formulaire
            $donnees = $form->getData();

            $email = $donnees["email"];
            $password = $donnees["password"];
            $confirmPassword = $donnees["confirmPassword"];
            if ($password == $confirmPassword) {
                $response = $apiUser->inscription($email,$password);
            } else {
                $response["erreur"] = "Les mots de passe ne sont pas identique !";
            }
            if (!isset($response["erreur"])) {
                // Message flash
                $session->getFlashBag()->add('success', 'Votre compte a bien été créer !');
                return $this->redirectToRoute('app_home');
            } else {
                $form->addError(new FormError($response["erreur"]));
            }
        }

        // Si il y a des erreur on renvoie l'erreur'
        return $this->render('user/inscription.html.twig', [
            'form' => $form,
            'connecter' => $userConnecter->connecter($session)
        ]);
    }

    #[Route('/connexion', name: 'app_connexion')]
    public function connexion(ApiUser $apiUser,Request $request,SessionInterface $session,UserConnecter $userConnecter): Response
    {

        $form = $this->createForm(ConnexionFormType::class);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Traitez le formulaire
            $donnees = $form->getData();

            $email = $donnees["email"];
            $password = $donnees["password"];
            $response = $apiUser->login($email,$password);

            if (!isset($response["erreur"])) {
                // Ajoute le token a la session
                $session->set('token',$response["token"]);
                // Message flash
                $session->getFlashBag()->add('success', 'Bienvenue ! Vous êtes maintenant connecté(e) avec succès.');
                return $this->redirectToRoute('app_home');
            } else {
                $form->addError(new FormError("Votre adresse e-mail ou votre mot de passe est incorrect. Veuillez réessayer."));
            }
        }

        // Si il y a des erreur on renvoie l'erreur
        return $this->render('user/connexion.html.twig', [
            'form' => $form,
            'connecter' => $userConnecter->connecter($session)
        ]);
    }

    #[Route('/deconnexion', name: 'app_deconnexion')]
    public function deconnexion(SessionInterface $session): Response
    {
        $session->clear();
        $session->getFlashBag()->add('success', 'Vous êtes déconnecté !');
        return $this->redirectToRoute('app_home');
    }
}
