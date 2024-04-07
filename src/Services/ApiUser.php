<?php

namespace App\Services;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiUser
{
    private HttpClientInterface $httpClient;

    /**
     * @param HttpClientInterface $httpClient
     */
    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function inscription(string $email,string $password) : array {
        $donnees = [
            'email' => $email,
            'password' => $password
        ];

        // On fais appel au endpoint 127.0.0.1:8000/api/inscription
        try {
            $responseAPI = $this->httpClient->request(
                'POST',
                'http://'.$_ENV["ADRESSE_IP_LOCAL"].':8000/api/inscription',
                ['json' => $donnees]
            );
            return $responseAPI->toArray();

        } catch (ClientExceptionInterface $exception) {
            $statusCode = $exception->getResponse()->getStatusCode();
            $messageErreur = $exception->getResponse()->getContent(false);

            return ['erreur' => $messageErreur,'code' => $statusCode];
        }
    }

}
