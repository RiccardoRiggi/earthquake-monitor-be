<?php

include './importManager.php';
include '../services/cronJobService.php';

//IN REALTA BISOGNERA IMPOSTARE GLI ULTIMI 2 GIORNI

set_time_limit(1500);


$dataInizioIntervallo = new DateTime();
$dataFineIntervallo = new DateTime();
$dataInizioIntervallo = $dataInizioIntervallo->modify('-15 day');

$dataLimite = new DateTime();
$dataLimite = $dataLimite->modify('+5 day');


while ($dataFineIntervallo < $dataLimite) {

    try {
        echo "Dal " . $dataInizioIntervallo->format('Y-m-d') . " al " . $dataFineIntervallo->format('Y-m-d') . "</br>";

        $listaGrezza = recuperoTerremotiDaINGV($dataInizioIntervallo->format('Y-m-d'), $dataFineIntervallo->format('Y-m-d'));

        $file = fopen("databaseTmp", 'w');
        fwrite($file, $listaGrezza);
        fclose($file);

        $file = fopen("databaseTmp", 'r');
        $listaTerremoti = getListaTerremotiFormattata($file);

        $inseriti = 0;
        foreach ($listaTerremoti as $terremoto) {
            try {
                inserisciTerremoto($terremoto);
                $inseriti++;
            } catch (Exception $e) {
                //generaLogSuBaseDati("DEBUG", "Errore durante l'inserimento del terremoto con id " . $terremoto["id"] . "per la seguente motivazione: " . $e->getMessage());
            }
        }
        echo $inseriti."<br>";
    } catch (Exception $e) {
        echo 'Message: ' . $e->getMessage();
    }





    $dataInizioIntervallo = $dataInizioIntervallo->modify('+15 day');
    $dataFineIntervallo = $dataFineIntervallo->modify('+15 day');
}
