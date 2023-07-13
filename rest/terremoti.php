<?php

include './importManager.php';
include '../services/terremotiService.php';


try {
    if (ABILITA_CORS) {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: PUT, GET, POST, DELETE, OPTIONS');
        header('Access-Control-Allow-Headers: *');
        header('Access-Control-Expose-Headers: *');
        header('Access-Control-Max-Age: 86400');
        if (strtolower($_SERVER['REQUEST_METHOD']) == 'options')
            exit();
    }

    verificaIndirizzoIp();

    if (!isset($_GET["nomeMetodo"]))
        throw new ErroreServerException("Non è stato fornito il riferimento del metodo da invocare");


    if ($_GET["nomeMetodo"] == "getTerremoti") {

        if ($_SERVER['REQUEST_METHOD'] != "GET")
            throw new MetodoHttpErratoException();


        if (!isset($_GET["magnitudo"]))
            throw new OtterGuardianException(400, "Il campo magnitudo è richiesto");


        if (isset($_GET["distanza"]) && (!isset($_GET["latitudine"]) || !isset($_GET["longitudine"])))
            throw new OtterGuardianException(400, "Specificando la distanza è necessario inserire la latitudine e la longitudine");

        if (isset($_GET["dataInizioIntervallo"]) != isset($_GET["dataFineIntervallo"]))
            throw new OtterGuardianException(400, "Devi specificare sia la dataInizioIntervallo che la dataFineIntervallo");

        $response = getTerremoti(isset($_GET["latitudine"]) ? $_GET["latitudine"] : null, isset($_GET["longitudine"]) ? $_GET["longitudine"] : null, isset($_GET["distanza"]) ? $_GET["distanza"] : null, $_GET["magnitudo"], isset($_GET["dataInizioIntervallo"]) ? $_GET["dataInizioIntervallo"] : null, isset($_GET["dataFineIntervallo"]) ? $_GET["dataFineIntervallo"] : null, isset($_GET["pagina"]) ? $_GET["pagina"] : null);
        http_response_code(200);
        exit(json_encode($response));
    } else  if ($_GET["nomeMetodo"] == "getTerremoto") {

        if ($_SERVER['REQUEST_METHOD'] != "GET")
            throw new MetodoHttpErratoException();


        if (!isset($_GET["id"]))
            throw new OtterGuardianException(400, "Il campo id è richiesto");

        $response = getTerremoto($_GET["id"]);
        http_response_code(200);
        exit(json_encode($response));
    } else  if ($_GET["nomeMetodo"] == "getCoordinateDatoIndirizzo") {

        if ($_SERVER['REQUEST_METHOD'] != "GET")
            throw new MetodoHttpErratoException();


        if (!isset($_GET["regione"]))
            throw new OtterGuardianException(400, "Il campo regione è richiesto");

        if (!isset($_GET["provincia"]))
            throw new OtterGuardianException(400, "Il campo provincia è richiesto");

        if (!isset($_GET["comune"]))
            throw new OtterGuardianException(400, "Il campo comune è richiesto");

        if (!isset($_GET["indirizzo"]))
            throw new OtterGuardianException(400, "Il campo indirizzo è richiesto");

        $response = getCoordinateDatoIndirizzo($_GET["regione"], $_GET["provincia"], $_GET["comune"], $_GET["indirizzo"]);
        http_response_code(200);
        exit($response);
    } else if ($_GET["nomeMetodo"] == "getFiltriPersonali") {

        if ($_SERVER['REQUEST_METHOD'] != "GET")
            throw new MetodoHttpErratoException();

        if (!isset($_GET["pagina"]))
            throw new OtterGuardianException(400, "Il campo pagina è richiesto");


        $response = getFiltriPersonali($_GET["pagina"]);
        http_response_code(200);
        exit(json_encode($response));
    } else if ($_GET["nomeMetodo"] == "getFiltroPersonale") {

        if ($_SERVER['REQUEST_METHOD'] != "GET")
            throw new MetodoHttpErratoException();

        if (!isset($_GET["idFiltroPersonale"]))
            throw new OtterGuardianException(400, "Il campo idFiltroPersonale è richiesto");


        $response = getFiltroPersonale($_GET["idFiltroPersonale"]);
        http_response_code(200);
        exit(json_encode($response));
    } else if ($_GET["nomeMetodo"] == "inserisciFiltroPersonale") {

        if ($_SERVER['REQUEST_METHOD'] != "POST")
            throw new MetodoHttpErratoException();

        $jsonBody = json_decode(file_get_contents('php://input'), true);

        if (!isset($jsonBody["idTipoFiltroPersonale"]))
            throw new OtterGuardianException(400, "Il campo idTipoFiltroPersonale è richiesto");

        if (!isset($jsonBody["nomeFiltro"]))
            throw new OtterGuardianException(400, "Il campo nomeFiltro è richiesto");

        if ($jsonBody["idTipoFiltroPersonale"] == "MAGNITUDO" || $jsonBody["idTipoFiltroPersonale"] == "MAGNITUDO_DISTANZA") {

            if (!isset($jsonBody["magnitudo"]))
                throw new OtterGuardianException(400, "Il campo magnitudo è richiesto");
        }

        if ($jsonBody["idTipoFiltroPersonale"] == "DISTANZA" || $jsonBody["idTipoFiltroPersonale"] == "MAGNITUDO_DISTANZA") {

            if (!isset($jsonBody["codiceRegione"]))
                throw new OtterGuardianException(400, "Il campo codiceRegione è richiesto");

            if (!isset($jsonBody["descrizioneRegione"]))
                throw new OtterGuardianException(400, "Il campo descrizioneRegione è richiesto");

            if (!isset($jsonBody["codiceProvincia"]))
                throw new OtterGuardianException(400, "Il campo codiceProvincia è richiesto");

            if (!isset($jsonBody["descrizioneProvincia"]))
                throw new OtterGuardianException(400, "Il campo descrizioneProvincia è richiesto");

            if (!isset($jsonBody["codiceComune"]))
                throw new OtterGuardianException(400, "Il campo codiceComune è richiesto");

            if (!isset($jsonBody["descrizioneComune"]))
                throw new OtterGuardianException(400, "Il campo descrizioneComune è richiesto");

            if (!isset($jsonBody["cap"]))
                throw new OtterGuardianException(400, "Il campo cap è richiesto");

            if (!isset($jsonBody["latitudine"]))
                throw new OtterGuardianException(400, "Il campo latitudine è richiesto");

            if (!isset($jsonBody["longitudine"]))
                throw new OtterGuardianException(400, "Il campo longitudine è richiesto");

            if (!isset($jsonBody["distanza"]))
                throw new OtterGuardianException(400, "Il campo distanza è richiesto");

            if (!isset($jsonBody["indirizzo"]))
                throw new OtterGuardianException(400, "Il campo indirizzo è richiesto");
        }


        $idTipoFiltroPersonale = isset($jsonBody["idTipoFiltroPersonale"]) ? ($jsonBody["idTipoFiltroPersonale"]) : null;
        $nomeFiltro = isset($jsonBody["nomeFiltro"]) ? ($jsonBody["nomeFiltro"]) : null;
        $magnitudo = isset($jsonBody["magnitudo"]) ? ($jsonBody["magnitudo"]) : null;
        $codiceRegione = isset($jsonBody["codiceRegione"]) ? ($jsonBody["codiceRegione"]) : null;
        $descrizioneRegione = isset($jsonBody["descrizioneRegione"]) ? ($jsonBody["descrizioneRegione"]) : null;
        $codiceProvincia = isset($jsonBody["codiceProvincia"]) ? ($jsonBody["codiceProvincia"]) : null;
        $descrizioneProvincia = isset($jsonBody["descrizioneProvincia"]) ? ($jsonBody["descrizioneProvincia"]) : null;
        $codiceComune = isset($jsonBody["codiceComune"]) ? ($jsonBody["codiceComune"]) : null;
        $descrizioneComune = isset($jsonBody["descrizioneComune"]) ? ($jsonBody["descrizioneComune"]) : null;
        $cap = isset($jsonBody["cap"]) ? ($jsonBody["cap"]) : null;
        $latitudine = isset($jsonBody["latitudine"]) ? ($jsonBody["latitudine"]) : null;
        $longitudine = isset($jsonBody["longitudine"]) ? ($jsonBody["longitudine"]) : null;
        $distanza = isset($jsonBody["distanza"]) ? ($jsonBody["distanza"]) : null;
        $indirizzo = isset($jsonBody["indirizzo"]) ? ($jsonBody["indirizzo"]) : null;

        $response = inserisciFiltroPersonale($idTipoFiltroPersonale, $nomeFiltro, $magnitudo, $codiceRegione, $descrizioneRegione, $codiceProvincia, $descrizioneProvincia, $codiceComune, $descrizioneComune, $cap, $latitudine, $longitudine, $distanza, $indirizzo);
        http_response_code(200);
    } else if ($_GET["nomeMetodo"] == "deleteFiltroPersonale") {

        if ($_SERVER['REQUEST_METHOD'] != "DELETE")
            throw new MetodoHttpErratoException();

        if (!isset($_GET["idFiltroPersonale"]))
            throw new OtterGuardianException(400, "Il campo idFiltroPersonale è richiesto");


        deleteFiltroPersonale($_GET["idFiltroPersonale"]);
        http_response_code(204);
    } else if ($_GET["nomeMetodo"] == "getDistanzaComuniDatoTerremoto") {

        if ($_SERVER['REQUEST_METHOD'] != "GET")
            throw new MetodoHttpErratoException();

        if (!isset($_GET["id"]))
            throw new OtterGuardianException(400, "Il campo id è richiesto");

        if (!isset($_GET["pagina"]))
            throw new OtterGuardianException(400, "Il campo pagina è richiesto");


        $response = getDistanzaComuniDatoTerremoto($_GET["id"],$_GET["pagina"]);
        http_response_code(200);
        exit(json_encode($response));
    } else if ($_GET["nomeMetodo"] == "getDistanzaLuoghiPersonaliDatoTerremoto") {

        if ($_SERVER['REQUEST_METHOD'] != "GET")
            throw new MetodoHttpErratoException();

        if (!isset($_GET["id"]))
            throw new OtterGuardianException(400, "Il campo id è richiesto");

        if (!isset($_GET["pagina"]))
            throw new OtterGuardianException(400, "Il campo pagina è richiesto");


        $response = getDistanzaLuoghiPersonaliDatoTerremoto($_GET["id"],$_GET["pagina"]);
        http_response_code(200);
        exit(json_encode($response));
    } else if ($_GET["nomeMetodo"] == "getCronJobs") {

        if ($_SERVER['REQUEST_METHOD'] != "GET")
            throw new MetodoHttpErratoException();

        if (!isset($_GET["pagina"]))
            throw new OtterGuardianException(400, "Il campo pagina è richiesto");


        $response = getCronJobs($_GET["pagina"]);
        http_response_code(200);
        exit(json_encode($response));
    } else {
        throw new ErroreServerException("Metodo non implementato");
    }
} catch (AccessoNonAutorizzatoLoginException $e) {
    httpAccessoNonAutorizzatoLogin();
} catch (AccessoNonAutorizzatoException $e) {
    httpAccessoNonAutorizzato();
} catch (MetodoHttpErratoException $e) {
    httpMetodoHttpErrato();
} catch (ErroreServerException $e) {
    httpErroreServer($e->getMessage());
} catch (OtterGuardianException $e) {
    http_response_code($e->getStatus());
    $oggetto = new stdClass();
    $oggetto->codice = $e->getStatus();
    $oggetto->descrizione = $e->getMessage();
    exit(json_encode($oggetto));
} catch (Exception $e) {
    if (!ABILITA_VERIFICA_TOKEN) {
        echo $e->getMessage();
    } else {
        generaLogSuFile("Errore sconosciuto: " . $e->getMessage());
        httpErroreServer("Errore sconosciuto");
    }
}
