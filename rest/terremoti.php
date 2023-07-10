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
