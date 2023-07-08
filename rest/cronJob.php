<?php

include './importManager.php';
include '../services/cronJobService.php';

//IN REALTA BISOGNERA IMPOSTARE GLI ULTIMI 2 GIORNI
$dataInizioIntervallo = new DateTime('1985-01-01');
$dataFineIntervallo = new DateTime('1985-01-01');
$dataFineIntervallo = $dataFineIntervallo->modify('+15 day');

$dataLimite = new DateTime("1985-01-16");
$dataLimite = $dataLimite->modify('+15 day');


while ($dataFineIntervallo < $dataLimite) {

    try {
       // echo "Dal " . $dataInizioIntervallo->format('Y-m-d') . " al " . $dataFineIntervallo->format('Y-m-d') . "</br>";

        $listaGrezza = recuperoTerremotiDaINGV($dataInizioIntervallo->format('Y-m-d'), $dataFineIntervallo->format('Y-m-d'));
        $listaTerremoti = getListaTerremotiFormattata($listaGrezza);

        foreach ($listaTerremoti as $terremoto) {
            try {
                inserisciTerremoto($terremoto);
            } catch (Exception $e) {
                generaLogSuBaseDati("DEBUG","Errore durante l'inserimento del terremoto con id ".$terremoto["id"]."per la seguente motivazione: ".$e->getMessage());
            }
        }
    } catch (Exception $e) {
        echo 'Message: ' . $e->getMessage();
    }





    $dataInizioIntervallo = $dataInizioIntervallo->modify('+15 day');
    $dataFineIntervallo = $dataFineIntervallo->modify('+15 day');
}
