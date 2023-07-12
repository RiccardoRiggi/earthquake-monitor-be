<?php

include './importManager.php';
include '../services/cronJobService.php';

include '../services/terremotiService.php';
include '../services/webHookTelegramService.php';


//IN REALTA BISOGNERA IMPOSTARE GLI ULTIMI 2 GIORNI

set_time_limit(1500);


$dataInizioIntervallo = new DateTime();
$dataFineIntervallo = new DateTime();
$dataInizioIntervallo = $dataInizioIntervallo->modify('-3 day');

$dataLimite = new DateTime();
$dataLimite = $dataLimite->modify('+5 day');

// CARICAMENTO DATI
while ($dataFineIntervallo < $dataLimite) {

    try {
        //echo "Dal " . $dataInizioIntervallo->format('Y-m-d') . " al " . $dataFineIntervallo->format('Y-m-d') . "</br>";

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
    } catch (Exception $e) {
        echo 'Message: ' . $e->getMessage();
    }





    $dataInizioIntervallo = $dataInizioIntervallo->modify('+15 day');
    $dataFineIntervallo = $dataFineIntervallo->modify('+15 day');
}
// FINE CARICAMENTO DATI

// INVIO NOTIFICHE
$ultimoCronJob = getCronJobs(1)[0];
$listaTerremoti = getListaTerremotiAppenaInseriti($ultimoCronJob["dataEvento"]);
$listaFiltriPersonali = getFiltriPersonaliDiTutti();

foreach ($listaFiltriPersonali as $filtroPersonale) {

    if ("MAGNITUDO" == $filtroPersonale["idTipoFiltroPersonale"]) {
        foreach ($listaTerremoti as $terremoto) {
            if ($terremoto["magnitude"] >= $filtroPersonale["magnitudo"]) {
                inviaNotificaSisma($terremoto,$filtroPersonale,null);
            }
        }
    } else if ("DISTANZA" == $filtroPersonale["idTipoFiltroPersonale"] || "MAGNITUDO_DISTANZA" == $filtroPersonale["idTipoFiltroPersonale"]) {
        foreach ($listaTerremoti as $terremoto) {
            $distanzaTmp = getDistanzaLuoghiPersonaliDatoTerremotoDatoFiltroCron($terremoto["id"], $filtroPersonale["idFiltroPersonale"])["distanza"];
            if ($distanzaTmp <= $filtroPersonale["distanza"] && "DISTANZA" == $filtroPersonale["idTipoFiltroPersonale"]) {
                inviaNotificaSisma($terremoto,$filtroPersonale,$distanzaTmp);
            } else if ($terremoto["magnitude"] >= $filtroPersonale["magnitudo"] && $distanzaTmp <= $filtroPersonale["distanza"] && "MAGNITUDO_DISTANZA" == $filtroPersonale["idTipoFiltroPersonale"]) {
                inviaNotificaSisma($terremoto,$filtroPersonale,$distanzaTmp);
            }
        }
    }
}


// FINE INVIO NOTIFICHE

// REGISTRAZIONE EVENTO
try {
    registraEvento();
} catch (Exception $e) {
    generaLogSuBaseDati("DEBUG", "Errore durante l'inserimento del timestamp del cronjob");
}
// FINE REGISTRAZIONE EVENTO