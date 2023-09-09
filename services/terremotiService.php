<?php


/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getTerremoti
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getTerremoti')) {
    function getTerremoti($latitudine, $longitudine, $distanza, $magnitudo, $dataInizioIntervallo, $dataFineIntervallo, $pagina)
    {
        verificaValiditaToken();

        $paginaDaEstrarre = ($pagina - 1) * ELEMENTI_PER_PAGINA;


        $sql = "SELECT T.id, T.time, T.latitude, T.longitude, T.depth, T.author, T.catalog, T.contributor, T.contributorId, T.magType, T.magnitude, T.magAuthor, T.eventLocationName, T.eventType, T.dataCreazione  ";

        if ($distanza != null) {
            $sql = $sql . ", ROUND((6371 *
            acos(
            cos(radians(:latitudine)) *
            cos(radians(latitude)) *
            cos(radians(longitude) - radians(:longitudine)) +
            sin(radians(:latitudine)) *
            sin(radians(latitude))
            ))) AS distanza ";
        }



        $sql = $sql . " FROM " . PREFISSO_TAVOLA . "_TERREMOTI T";


        if ($distanza != null) {

            $sql = $sql . "    , (SELECT ID, ROUND((6371 *
        acos(
        cos(radians(:latitudine)) *
        cos(radians(latitude)) *
        cos(radians(longitude) - radians(:longitudine)) +
        sin(radians(:latitudine)) *
        sin(radians(latitude))
        ))) AS distanza FROM " . PREFISSO_TAVOLA . "_TERREMOTI ) d ";
        }

        $sql = $sql . " WHERE 1=1 AND T.magnitude >= :magnitudo ";

        if ($distanza != null) {
            $sql = $sql . "AND d.ID = T.ID ";
        }

        if ($distanza != null) {
            $sql = $sql . " AND d.DISTANZA <= :distanza ";
        }


        if ($dataInizioIntervallo != null) {
            $sql = $sql . " AND T.time >= :dataInizioIntervallo AND T.time <= :dataFineIntervallo ";
        }


        if ($distanza != null) {
            $sql = $sql . " ORDER BY d.distanza ";
        } else {
            $sql = $sql . " ORDER BY T.time DESC ";
        }

        if ($pagina != null) {
            $sql = $sql . " LIMIT :pagina, " . ELEMENTI_PER_PAGINA;
        }


        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);


        if ($distanza != null) {
            $stmt->bindParam(':latitudine', $latitudine);
            $stmt->bindParam(':longitudine', $longitudine);
            $stmt->bindParam(':distanza', $distanza);
        }


        $stmt->bindParam(':magnitudo', $magnitudo);

        if ($dataInizioIntervallo != null) {
            $stmt->bindParam(':dataInizioIntervallo', $dataInizioIntervallo);
            $dataFineIntervallo = new DateTime($dataFineIntervallo);
            $dataFineIntervallo = $dataFineIntervallo->modify('+1 day');
            $dataFineIntervallo = $dataFineIntervallo->format('Y-m-d');
            $stmt->bindParam(':dataFineIntervallo', $dataFineIntervallo);
        }

        if ($pagina != null) {
            $stmt->bindParam(':pagina', $paginaDaEstrarre, PDO::PARAM_INT);
        }

        $stmt->execute();
        $result = $stmt->fetchAll();
        chiudiConnessione($conn);
        return $result;
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getTerremoto
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getTerremoto')) {
    function getTerremoto($id)
    {
        verificaValiditaToken();

        $sql = "SELECT * FROM " . PREFISSO_TAVOLA . "_terremoti WHERE id = :id ";


        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        chiudiConnessione($conn);
        return $result;
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getTerremotoCron
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getTerremotoCron')) {
    function getTerremotoCron($id)
    {

        $sql = "SELECT * FROM " . PREFISSO_TAVOLA . "_terremoti WHERE id = :id ";


        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch();
        chiudiConnessione($conn);
        return $result;
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getCoordinateDatoIndirizzo
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getCoordinateDatoIndirizzo')) {
    function getCoordinateDatoIndirizzo($regione, $provincia, $comune, $indirizzo)
    {
        verificaValiditaToken();
        $url = BASE_URL_HERE . "&apiKey=" . API_KEY_HERE;
        $q = urlencode($indirizzo . " " . $comune . " " . $provincia . " " . $regione);
        $url = $url . "&q=" . $q;
        $response = file_get_contents($url);
        return $response;
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getFiltriPersonali
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getFiltriPersonali')) {
    function getFiltriPersonali($pagina)
    {
        verificaValiditaToken();
        $idUtente = getIdUtenteDaToken($_SERVER["HTTP_TOKEN"]);

        $paginaDaEstrarre = ($pagina - 1) * ELEMENTI_PER_PAGINA;


        $sql = "SELECT * FROM " . PREFISSO_TAVOLA . "_filtri_personali INNER JOIN ".PREFISSO_TAVOLA."_t_filtri_personali ON ".PREFISSO_TAVOLA."_filtri_personali.idTipoFiltroPersonale = ".PREFISSO_TAVOLA."_t_filtri_personali.idTipoFiltroPersonale WHERE idUtente = :idUtente AND dataEliminazione IS NULL ORDER BY dataCreazione DESC LIMIT :pagina, " . ELEMENTI_PER_PAGINA;


        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idUtente', $idUtente);
        $stmt->bindParam(':pagina', $paginaDaEstrarre, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll();
        chiudiConnessione($conn);
        return $result;
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getFiltriPersonaliDiTutti
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getFiltriPersonaliDiTutti')) {
    function getFiltriPersonaliDiTutti()
    {
        $sql = "SELECT * FROM " . PREFISSO_TAVOLA . "_filtri_personali WHERE dataEliminazione IS NULL ORDER BY dataCreazione DESC";


        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        chiudiConnessione($conn);
        return $result;
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getFiltroPersonale
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getFiltroPersonale')) {
    function getFiltroPersonale($idFiltroPersonale)
    {
        verificaValiditaToken();
        $idUtente = getIdUtenteDaToken($_SERVER["HTTP_TOKEN"]);
        $sql = "SELECT * FROM " . PREFISSO_TAVOLA . "_filtri_personali WHERE idUtente = :idUtente AND idFiltroPersonale = :idFiltroPersonale AND dataEliminazione IS NULL ";
        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idUtente', $idUtente);
        $stmt->bindParam(':idFiltroPersonale', $idFiltroPersonale);
        $stmt->execute();
        $result = $stmt->fetch();
        chiudiConnessione($conn);
        return $result;
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: inserisciFiltroPersonale
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('inserisciFiltroPersonale')) {
    function inserisciFiltroPersonale($idTipoFiltroPersonale, $nomeFiltro, $magnitudo, $codiceRegione, $descrizioneRegione, $codiceProvincia, $descrizioneProvincia, $codiceComune, $descrizioneComune, $cap, $latitudine, $longitudine, $distanza, $indirizzo)
    {
        verificaValiditaToken();
        $idUtente = getIdUtenteDaToken($_SERVER["HTTP_TOKEN"]);

        $sql = "INSERT INTO " . PREFISSO_TAVOLA . "_filtri_personali (idUtente, idTipoFiltroPersonale, nomeFiltro, codiceRegione, descrizioneRegione, codiceProvincia, descrizioneProvincia, codiceComune, descrizioneComune, cap, latitudine, longitudine, magnitudo, distanza, indirizzo, dataCreazione) VALUES (:idUtente, :idTipoFiltroPersonale, :nomeFiltro, :codiceRegione, :descrizioneRegione, :codiceProvincia, :descrizioneProvincia, :codiceComune, :descrizioneComune, :cap, :latitudine, :longitudine, :magnitudo, :distanza, :indirizzo, CURRENT_TIMESTAMP)";


        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idUtente', $idUtente);
        $stmt->bindParam(':idTipoFiltroPersonale', $idTipoFiltroPersonale);
        $stmt->bindParam(':nomeFiltro', $nomeFiltro);
        $stmt->bindParam(':codiceRegione', $codiceRegione);
        $stmt->bindParam(':descrizioneRegione', $descrizioneRegione);
        $stmt->bindParam(':codiceProvincia', $codiceProvincia);
        $stmt->bindParam(':descrizioneProvincia', $descrizioneProvincia);
        $stmt->bindParam(':codiceComune', $codiceComune);
        $stmt->bindParam(':descrizioneComune', $descrizioneComune);
        $stmt->bindParam(':cap', $cap);
        $stmt->bindParam(':latitudine', $latitudine);
        $stmt->bindParam(':longitudine', $longitudine);
        $stmt->bindParam(':magnitudo', $magnitudo);
        $stmt->bindParam(':distanza', $distanza);
        $stmt->bindParam(':indirizzo', $indirizzo);
        $stmt->execute();
        chiudiConnessione($conn);
        return null;
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: deleteFiltroPersonale
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('deleteFiltroPersonale')) {
    function deleteFiltroPersonale($idFiltroPersonale)
    {
        verificaValiditaToken();
        $idUtente = getIdUtenteDaToken($_SERVER["HTTP_TOKEN"]);



        $sql = "UPDATE " . PREFISSO_TAVOLA . "_filtri_personali SET dataEliminazione = CURRENT_TIMESTAMP WHERE idUtente = :idUtente AND idFiltroPersonale = :idFiltroPersonale";


        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':idUtente', $idUtente);
        $stmt->bindParam(':idFiltroPersonale', $idFiltroPersonale);
        $stmt->execute();
        chiudiConnessione($conn);
        return null;
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getDistanzaComuniDatoTerremoto
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getDistanzaComuniDatoTerremoto')) {
    function getDistanzaComuniDatoTerremoto($id,$pagina)
    {
        verificaValiditaToken();

        $terremoto = getTerremoto($id);
        $paginaDaEstrarre = ($pagina - 1) * ELEMENTI_PER_PAGINA;



        $sql = "SELECT R.codiceRegione, R.descrizioneRegione, P.descrizioneProvincia, C.codiceProvincia, C.codiceComune, C.descrizioneComune, C.residenti, C.longitudine, C.latitudine ";

        $sql = $sql . ", ROUND((6371 *
            acos(
            cos(radians(:latitudine)) *
            cos(radians(C.latitudine)) *
            cos(radians(C.longitudine) - radians(:longitudine)) +
            sin(radians(:latitudine)) *
            sin(radians(C.latitudine))
            ))) AS distanza ";

        $sql = $sql . " FROM " . PREFISSO_TAVOLA . "_COMUNI C, ".PREFISSO_TAVOLA."_PROVINCE P, ".PREFISSO_TAVOLA."_REGIONI R ";
        $sql = $sql . " WHERE 1=1 AND C.codiceProvincia = P.codiceProvincia AND P.codiceRegione = R.codiceRegione";
        $sql = $sql . " ORDER BY distanza ";
        $sql = $sql . " LIMIT :pagina, " . ELEMENTI_PER_PAGINA;

        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':latitudine', $terremoto["latitude"]);
        $stmt->bindParam(':longitudine', $terremoto["longitude"]);
        $stmt->bindParam(':pagina', $paginaDaEstrarre, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll();
        chiudiConnessione($conn);
        return $result;
    }
}


/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getDistanzaLuoghiPersonaliDatoTerremoto
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getDistanzaLuoghiPersonaliDatoTerremoto')) {
    function getDistanzaLuoghiPersonaliDatoTerremoto($id,$pagina)
    {
        verificaValiditaToken();
        $idUtente = getIdUtenteDaToken($_SERVER["HTTP_TOKEN"]);
        $terremoto = getTerremoto($id);

        $paginaDaEstrarre = ($pagina - 1) * ELEMENTI_PER_PAGINA;


        $sql = "SELECT idFiltroPersonale, idTipoFiltroPersonale, nomeFiltro, codiceRegione, descrizioneRegione, codiceProvincia, descrizioneProvincia, codiceComune, descrizioneComune, cap, latitudine, longitudine, magnitudo, distanza, indirizzo, dataCreazione ";

        $sql = $sql . ", ROUND((6371 *
            acos(
            cos(radians(:latitudine)) *
            cos(radians(F.latitudine)) *
            cos(radians(F.longitudine) - radians(:longitudine)) +
            sin(radians(:latitudine)) *
            sin(radians(F.latitudine))
            ))) AS distanza ";

        $sql = $sql . " FROM " . PREFISSO_TAVOLA . "_FILTRI_PERSONALI F ";
        $sql = $sql . " WHERE 1=1 AND F.idTipoFiltroPersonale IN ('DISTANZA','MAGNITUDO_DISTANZA') AND F.idUtente = :idUtente AND F.dataEliminazione IS NULL";
        $sql = $sql . " ORDER BY distanza ";
        $sql = $sql . " LIMIT :pagina, " . ELEMENTI_PER_PAGINA;

        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':latitudine', $terremoto["latitude"]);
        $stmt->bindParam(':longitudine', $terremoto["longitude"]);
        $stmt->bindParam(':idUtente', $idUtente);
        $stmt->bindParam(':pagina', $paginaDaEstrarre, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll();
        chiudiConnessione($conn);
        return $result;
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getDistanzaLuoghiPersonaliDatoTerremotoDatoFiltro
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getDistanzaLuoghiPersonaliDatoTerremotoDatoFiltro')) {
    function getDistanzaLuoghiPersonaliDatoTerremotoDatoFiltro($id,$idFiltroPersonale)
    {
        verificaValiditaToken();
        $terremoto = getTerremoto($id);

        $sql = "SELECT idFiltroPersonale, idTipoFiltroPersonale, nomeFiltro, codiceRegione, descrizioneRegione, codiceProvincia, descrizioneProvincia, codiceComune, descrizioneComune, cap, latitudine, longitudine, magnitudo, distanza, indirizzo, dataCreazione ";

        $sql = $sql . ", ROUND((6371 *
            acos(
            cos(radians(:latitudine)) *
            cos(radians(F.latitudine)) *
            cos(radians(F.longitudine) - radians(:longitudine)) +
            sin(radians(:latitudine)) *
            sin(radians(F.latitudine))
            ))) AS distanza ";

        $sql = $sql . " FROM " . PREFISSO_TAVOLA . "_FILTRI_PERSONALI F ";
        $sql = $sql . " WHERE 1=1 AND F.idTipoFiltroPersonale IN ('DISTANZA','MAGNITUDO_DISTANZA') AND F.idFiltroPersonale = :idFiltroPersonale";
        $sql = $sql . " ORDER BY distanza ";

        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':latitudine', $terremoto["latitude"]);
        $stmt->bindParam(':longitudine', $terremoto["longitude"]);
        $stmt->bindParam(':idFiltroPersonale', $idFiltroPersonale);
        $stmt->execute();
        $result = $stmt->fetch();
        chiudiConnessione($conn);
        return $result;
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getDistanzaLuoghiPersonaliDatoTerremotoDatoFiltroCron
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getDistanzaLuoghiPersonaliDatoTerremotoDatoFiltroCron')) {
    function getDistanzaLuoghiPersonaliDatoTerremotoDatoFiltroCron($id,$idFiltroPersonale)
    {
        $terremoto = getTerremotoCron($id);

        $sql = "SELECT idFiltroPersonale, idTipoFiltroPersonale, nomeFiltro, codiceRegione, descrizioneRegione, codiceProvincia, descrizioneProvincia, codiceComune, descrizioneComune, cap, latitudine, longitudine, magnitudo, distanza, indirizzo, dataCreazione ";

        $sql = $sql . ", ROUND((6371 *
            acos(
            cos(radians(:latitudine)) *
            cos(radians(F.latitudine)) *
            cos(radians(F.longitudine) - radians(:longitudine)) +
            sin(radians(:latitudine)) *
            sin(radians(F.latitudine))
            ))) AS distanza ";

        $sql = $sql . " FROM " . PREFISSO_TAVOLA . "_FILTRI_PERSONALI F ";
        $sql = $sql . " WHERE 1=1 AND F.idTipoFiltroPersonale IN ('DISTANZA','MAGNITUDO_DISTANZA') AND F.idFiltroPersonale = :idFiltroPersonale";
        $sql = $sql . " ORDER BY distanza ";

        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':latitudine', $terremoto["latitude"]);
        $stmt->bindParam(':longitudine', $terremoto["longitude"]);
        $stmt->bindParam(':idFiltroPersonale', $idFiltroPersonale);
        $stmt->execute();
        $result = $stmt->fetch();
        chiudiConnessione($conn);
        return $result;
    }
}


/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getCronJobs
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getCronJobs')) {
    function getCronJobs($pagina)
    {

        $paginaDaEstrarre = ($pagina - 1) * ELEMENTI_PER_PAGINA;


        $sql = "SELECT * FROM " . PREFISSO_TAVOLA . "_log_cron_job ORDER BY dataEvento DESC LIMIT :pagina, " . ELEMENTI_PER_PAGINA;


        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':pagina', $paginaDaEstrarre, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetchAll();
        chiudiConnessione($conn);
        return $result;
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getListaTerremotiAppenaInseriti
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getListaTerremotiAppenaInseriti')) {
    function getListaTerremotiAppenaInseriti($dataEvento)
    {
        $sql = "SELECT * FROM " . PREFISSO_TAVOLA . "_terremoti WHERE dataCreazione > :dataEvento ORDER BY dataCreazione";
        $conn = apriConnessione();
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':dataEvento', $dataEvento);
        $stmt->execute();
        $result = $stmt->fetchAll();
        chiudiConnessione($conn);
        return $result;
    }
}


