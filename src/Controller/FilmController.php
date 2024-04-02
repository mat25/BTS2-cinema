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
}
