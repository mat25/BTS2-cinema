<?php

namespace App\Services;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiPosts
{
    private HttpClientInterface $httpClient;

    /**
     * @param HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function listerLesFilms() : array {

        // On fais appel au endpoint 127.0.0.1:8000/api/posts
        $responseAPI = $this->httpClient->request(
            'GET',
            'http://172.16.204.126:8000/api/listerFilms'
        );

        $films = $responseAPI->toArray();
        return $films;

    }

    public function detailsFilm($id) : array {

        // On fais appel au endpoint 127.0.0.1:8000/api/film/{id}
        $responseAPI = $this->httpClient->request(
            'GET',
            'http://172.16.204.126:8000/api/film/'.$id
        );

        $films = $responseAPI->toArray();
        return $films;
    }
}
