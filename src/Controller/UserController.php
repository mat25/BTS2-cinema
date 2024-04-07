<?php

namespace App\Controller;

use App\Services\ApiUser;
use http\Header;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function inscription(ApiUser $apiUser,Request $request): Response
    {
        $response = "";
        $method = $request->getMethod();
        if ($method == "POST") {
            // récupere les infos du formulaire
            $email = $request->get("email");
            $password = $request->get("password");
            // Fais appel a l'API
            $response = $apiUser->inscription($email,$password);
            // Si il y a pas d'erreur on renvoie vers la page d'accueil
            if (!isset($response["erreur"])) {
                return $this->redirectToRoute('app_home');
            }
        }
        // Si il y a des erreur on renvoie l'erreur'
        return $this->render('user/inscription.html.twig', [
            'erreur' => $response,
            'method' => $method
        ]);
    }
}
