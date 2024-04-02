<?php

namespace App\Services;

class FormateHeure
{
    public function __construct()
    {
    }

    public function formateHeureTableau(array $films) : array {
        foreach ($films as $key => $film){
            $films[$key]['duree'] = $this->formateHeure($film['duree']);
        }
        return $films;
    }

    public function formateHeure(int $minutes) : string {
        $heures = floor($minutes / 60);
        $minutesRestantes = $minutes % 60;

        $resultat = "";
        if ($heures > 0) {
            $resultat .= $heures . "h";
        }

        if ($minutesRestantes > 0) {
            if ($heures > 0) {
                $resultat.= " et ";
            }
            $resultat .= $minutesRestantes . "m";
        }

        return $resultat;
    }
}