<?php

namespace App\Controller;

use App\Form\ReservationFormType;
use App\Services\ApiFilm;
use App\Services\ApiReservation;
use App\Services\FormateHeure;
use App\Services\UserConnecter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class FilmController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    #[Route('/films', name: 'app_film')]
    public function index(ApiFilm $apiFilm, FormateHeure $formateHeure,SessionInterface $session,UserConnecter $userConnecter): Response
    {
        // Liste les films
        $films = $apiFilm->listerLesFilms();

        // Modifie le format de la duree
        $films = $formateHeure->formateHeureTableau($films);

        return $this->render('film/index.html.twig', [
            'films' => $films,
            'connecter' => $userConnecter->connecter($session)
        ]);
    }

    #[Route('/film/{id}', name: 'app_film_details')]
    public function detailsFilm(ApiFilm $apiFilm, FormateHeure $formateHeure, int $id,SessionInterface $session,Request $request,UserConnecter $userConnecter, ApiReservation $apiReservation): Response
    {
        // Liste le details d'un film
        $film = $apiFilm->detailsFilm($id);
        // Modifie le format de la duree
        $film = $formateHeure->formateHeureTableau($film);
        $film = $formateHeure->formateDateTableau($film);

        return $this->render('film/details_film.html.twig', [
            'film' => $film,
            'connecter' => $userConnecter->connecter($session)
        ]);
    }
}
