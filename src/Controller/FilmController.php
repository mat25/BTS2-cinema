<?php

namespace App\Controller;

use App\Services\ApiPosts;
use App\Services\FormateHeure;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FilmController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    #[Route('/films', name: 'app_film')]
    public function index(ApiPosts $apiPosts, FormateHeure $formateHeure): Response
    {
        // Liste les films
        $films = $apiPosts->listerLesFilms();

        // Modifie le format de la duree
        $films = $formateHeure->formateHeureTableau($films);

        return $this->render('film/index.html.twig', [
            'films' => $films,
        ]);
    }

    #[Route('/film/{id}', name: 'app_film_details')]
    public function detailsFilm(ApiPosts $apiPosts, FormateHeure $formateHeure, int $id): Response
    {
        // Liste le details d'un film
        $film = $apiPosts->detailsFilm($id);
        // Modifie le format de la duree
        $film = $formateHeure->formateHeureTableau($film);
        $film = $formateHeure->formateDateTableau($film);

        return $this->render('film/details_film.html.twig', [
            'film' => $film,
        ]);
    }
}
