<?php


/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getComboVociMenu
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getComboVociMenu')) {
    function getComboVociMenu()
    {
        verificaValiditaToken();

        $conn = apriConnessione();
        $stmt = $conn->prepare("SELECT idVoceMenu, descrizione FROM " . PREFISSO_TAVOLA . "_voci_menu WHERE dataEliminazione IS NULL ORDER BY descrizione");
        $stmt->execute();
        $result = $stmt->fetchAll();
        chiudiConnessione($conn);
        return $result;
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getComboRuoli
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getComboRuoli')) {
    function getComboRuoli()
    {
        verificaValiditaToken();

        $conn = apriConnessione();
        $stmt = $conn->prepare("SELECT idTipoRuolo, descrizione FROM " . PREFISSO_TAVOLA . "_t_ruoli ORDER BY descrizione");
        $stmt->execute();
        $result = $stmt->fetchAll();
        chiudiConnessione($conn);
        return $result;
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getTipologiaFiltriPersonali
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getTipologiaFiltriPersonali')) {
    function getTipologiaFiltriPersonali()
    {
        verificaValiditaToken();

        $conn = apriConnessione();
        $stmt = $conn->prepare("SELECT* FROM " . PREFISSO_TAVOLA . "_t_filtri_personali ORDER BY idTipoFiltroPersonale");
        $stmt->execute();
        $result = $stmt->fetchAll();
        chiudiConnessione($conn);
        return $result;
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getRegioni
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getRegioni')) {
    function getRegioni()
    {
        verificaValiditaToken();

        $conn = apriConnessione();
        $stmt = $conn->prepare("SELECT* FROM " . PREFISSO_TAVOLA . "_regioni ORDER BY descrizioneRegione");
        $stmt->execute();
        $result = $stmt->fetchAll();
        chiudiConnessione($conn);
        return $result;
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getProvince
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getProvince')) {
    function getProvince($codiceRegione)
    {
        verificaValiditaToken();

        $conn = apriConnessione();
        $stmt = $conn->prepare("SELECT* FROM " . PREFISSO_TAVOLA . "_province WHERE codiceRegione = :codiceRegione ORDER BY descrizioneProvincia");
        $stmt->bindParam(':codiceRegione', $codiceRegione);
        $stmt->execute();
        $result = $stmt->fetchAll();
        chiudiConnessione($conn);
        return $result;
    }
}

/*-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------
Funzione: getComuni
------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------*/

if (!function_exists('getComuni')) {
    function getComuni($codiceProvincia)
    {
        verificaValiditaToken();

        $conn = apriConnessione();
        $stmt = $conn->prepare("SELECT* FROM " . PREFISSO_TAVOLA . "_comuni WHERE codiceProvincia = :codiceProvincia ORDER BY descrizioneComune");
        $stmt->bindParam(':codiceProvincia', $codiceProvincia);
        $stmt->execute();
        $result = $stmt->fetchAll();
        chiudiConnessione($conn);
        return $result;
    }
}
