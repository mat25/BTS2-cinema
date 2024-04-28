<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class UserConnecter
{

    public function connecter(SessionInterface $session):bool {
        if ($session->get("token") <> "") {
           return true;
        } else {
            return false;
        }
    }
}