<?php

namespace App\Controller;

use App\Form\ReservationFormType;
use App\Services\ApiReservation;
use App\Services\UserConnecter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class ReservationController extends AbstractController
{
    #[Route('/reservation/{idSeance}', name: 'app_reservation')]
    public function index(int $idSeance,SessionInterface $session,UserConnecter $userConnecter,Request $request, ApiReservation $apiReservation): Response
    {
        $form = $this->createForm(ReservationFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Traitez le formulaire
            $donnees = $form->getData();

            $nbPlace = $donnees["nbPlace"];


            if ($nbPlace <> "") {
                if ($session->get("token") <> "") {
                    $token = $session->get("token");
                    $response = $apiReservation->reservation($idSeance,$nbPlace,$token);
                } else {
                    $form->addError(new FormError("Veuillez vous connecter avant de réserver une séance !"));
                }


                if (!isset($response["erreur"])) {
                    // Message flash
                    $session->getFlashBag()->add('success', 'Votre réservation a été enregistrée avec succès !');
                    // Redirect vers la page d'accueil
                    return $this->redirectToRoute('app_home');
                } else {
                    $form->addError(new FormError($response["erreur"]));
                }
            }


        }

        return $this->render('reservation/index.html.twig', [
            'controller_name' => 'ReservationController',
            'connecter' => $userConnecter->connecter($session),
            'form' => $form
        ]);
    }
}
