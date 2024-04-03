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


    public function formateDateTableau(array $films) : array {
        foreach ($films as $key => $film){
            foreach ($film["seances"] as $cle => $seance){
                $films[$key]['seances'][$cle]['dateProjection'] = $this->formateDate($seance['dateProjection']);
            }
        }
        return $films;
    }
    public function formateDate(string $date) : string {
        $dateTime = \DateTime::createFromFormat("Y-m-d\TH:i:sP",$date);
        $date = $dateTime->format("d/m/y à H\hi");
        return $date;
    }

}