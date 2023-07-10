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
