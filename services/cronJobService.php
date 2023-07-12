<?php


/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: recuperoTerremotiDaINGV
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('recuperoTerremotiDaINGV')) {
    function recuperoTerremotiDaINGV($dataInizioIntervallo, $dataFineIntervallo)
    {

        $url = "https://webservices.ingv.it/fdsnws/event/1/query?starttime=" . $dataInizioIntervallo . "T00%3A00%3A00&endtime=" . $dataFineIntervallo . "T23%3A59%3A59&minmag=-1&maxmag=10&mindepth=-10&maxdepth=1000&minlat=35&maxlat=49&minlon=5&maxlon=20&minversion=100&orderby=time-asc&format=text&limit=10000";
        $datasetGrezzo = file_get_contents($url);

        return $datasetGrezzo;
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getListaTerremotiFormattata
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/



//14

if (!function_exists('getListaTerremotiFormattata')) {
    function getListaTerremotiFormattata($listaGrezza)
    {

        $terremoti = array();

        $numeroRiga = 1;
        $numeroColonna = 1;

        while (($line = fgets($listaGrezza)) !== false) {
            // process the line read.

            $numeroColonna = 1;

            $id = null;
            $time = null;
            $latitude = null;
            $longitude = null;
            $depth = null;
            $author = null;
            $catalog = null;
            $contributor = null;
            $contributorId = null;
            $magType = null;
            $magnitude = null;
            $magAuthor = null;
            $eventLocationName = null;
            $eventType = null;

            if ($numeroRiga != 1) {
                foreach (explode('|', $line) as $p) {
                    if ($numeroColonna == 1) {
                        $id = $p;
                    } else if ($numeroColonna == 2) {
                        $time = $p;
                    } else if ($numeroColonna == 3) {
                        $latitude = $p;
                    } else if ($numeroColonna == 4) {
                        $longitude = $p;
                    } else if ($numeroColonna == 5) {
                        $depth = $p;
                    } else if ($numeroColonna == 6) {
                        $author = $p;
                    } else if ($numeroColonna == 7) {
                        $catalog = $p;
                    } else if ($numeroColonna == 8) {
                        $contributor = $p;
                    } else if ($numeroColonna == 9) {
                        $contributorId = $p;
                    } else if ($numeroColonna == 10) {
                        $magType = $p;
                    } else if ($numeroColonna == 11) {
                        $magnitude = $p;
                    } else if ($numeroColonna == 12) {
                        $magAuthor = $p;
                    } else if ($numeroColonna == 13) {
                        $eventLocationName = $p;
                    } else if ($numeroColonna == 14) {
                        $eventType = $p;
                    }
                    $numeroColonna++;
                }

                $terremotoTmp = [
                    'id' => $id,
                    'time' => $time,
                    'latitude' => $latitude,
                    'longitude' => $longitude,
                    'depth' => $depth,
                    'author' => $author,
                    'catalog' => $catalog,
                    'contributor' => $contributor,
                    'contributorId' => $contributorId,
                    'magType' => $magType,
                    'magnitude' => $magnitude,
                    'magAuthor' => $magAuthor,
                    'eventLocationName' => $eventLocationName,
                    'eventType' => $eventType,
                ];

                array_push($terremoti, $terremotoTmp);
            }
            $numeroRiga++;
        }


        return $terremoti;
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: inserisciTerremoto
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('inserisciTerremoto')) {
    function inserisciTerremoto($terremoto)
    {
        $sql = "INSERT INTO " . PREFISSO_TAVOLA . "_terremoti(id, time, latitude, longitude, depth, author, catalog, contributor, contributorId, magType, magnitude, magAuthor, eventLocationName, eventType, dataCreazione) VALUES (:id, :time, :latitude, :longitude, :depth, :author, :catalog, :contributor, :contributorId, :magType, :magnitude, :magAuthor, :eventLocationName, :eventType, CURRENT_TIMESTAMP)";


        $conn = apriConnessione();


        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $terremoto["id"]);
        $stmt->bindParam(':time', $terremoto["time"]);
        $stmt->bindParam(':latitude', $terremoto["latitude"]);
        $stmt->bindParam(':longitude', $terremoto["longitude"]);
        $stmt->bindParam(':depth', $terremoto["depth"]);
        $stmt->bindParam(':author', $terremoto["author"]);
        $stmt->bindParam(':catalog', $terremoto["catalog"]);
        $stmt->bindParam(':contributor', $terremoto["contributor"]);
        $stmt->bindParam(':contributorId', $terremoto["contributorId"]);
        $stmt->bindParam(':magType', $terremoto["magType"]);
        $stmt->bindParam(':magnitude', $terremoto["magnitude"]);
        $stmt->bindParam(':magAuthor', $terremoto["magAuthor"]);
        $stmt->bindParam(':eventLocationName', $terremoto["eventLocationName"]);
        $stmt->bindParam(':eventType', $terremoto["eventType"]);
        $stmt->execute();
        chiudiConnessione($conn);
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: registraEvento
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('registraEvento')) {
    function registraEvento()
    {
        $sql = "INSERT INTO " . PREFISSO_TAVOLA . "_log_cron_job(dataEvento) VALUES (CURRENT_TIMESTAMP)";
        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        chiudiConnessione($conn);
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: inviaNotificaSisma
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('inviaNotificaSisma')) {
    function inviaNotificaSisma($terremoto, $filtroPersonale, $distanza)
    {
        $idTelegram = getIdTelegramByIdUtente($filtroPersonale["idUtente"]);

        $messaggio = getMessaggio($terremoto, $filtroPersonale, $distanza);

        inviaNotificaTelegram($idTelegram, $messaggio);
    }
}

if (!function_exists('getMessaggio')) {
    function getMessaggio($terremoto, $filtroPersonale, $distanza)
    {
        $time = new DateTime($terremoto["time"]);
        $timeData = $time->format('d-m-Y');
        $timeOra = $time->format('h:m:s');

        $messaggio = "Un terremoto di magnitudo <b>" . $terremoto["magType"] . " " . $terremoto["magnitude"] . "</b> è avvenuto alle ore " . $timeOra . " del " . $timeData . " a <b>" . $terremoto["eventLocationName"] . "</b> ad una profondità di " . $terremoto["depth"] . " Km. 
        
Id evento: ".$terremoto["id"]." - <a href='https://www.google.com/maps/dir/?api=1&origin=&destination=" . $terremoto["latitude"] . "," . $terremoto["longitude"] . "&travelmode=driving'>Vedi sulla mappa</a>

";

         if ($filtroPersonale["idTipoFiltroPersonale"] == "MAGNITUDO") {
            $messaggio = $messaggio . "Ricevi questo messaggio perché la magnitudo è maggiore o uguale a <b>" . $filtroPersonale["magnitudo"] . "</b>";
        } else if ($filtroPersonale["idTipoFiltroPersonale"] == "DISTANZA") {
            $messaggio = $messaggio . "Ricevi questo messaggio perché l'epicentro del terremoto è a <b>" . $distanza . "</b> Km di distanza da <b>" . $filtroPersonale["indirizzo"].",  ".$filtroPersonale["descrizioneComune"]." (".$filtroPersonale["codiceProvincia"].") </b> ";
        } else if ($filtroPersonale["idTipoFiltroPersonale"] == "MAGNITUDO_DISTANZA") {
            $messaggio = $messaggio . "Ricevi questo messaggio perché l'epicentro del terremoto è a <b>" . $distanza . "</b> Km di distanza da <b>" . $filtroPersonale["indirizzo"].",  ".$filtroPersonale["descrizioneComune"]." (".$filtroPersonale["codiceProvincia"].") </b> e la magnitudo è maggiore o uguale a <b>" . $filtroPersonale["magnitudo"] . "</b>";
        }

        return $messaggio;
    }
}
