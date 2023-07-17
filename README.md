# Earthquake Monitor BE

Questo repository contiene la componente di backend di Earthquake Monitor basata sul progetto boilerplate [Otter Guardian BE](https://github.com/RiccardoRiggi/php-rest-authenticator/)  sviluppata in PHP nativo, la struttura del database, gli script di configurazione e altre informazioni utili. Questa documentazione contiene soltanto le differenze tra il progetto boilerplate e le implementazioni di questo progetto specifico. Per una panoramica completa è consiglabile prima leggere questa [questa documentazione](https://github.com/RiccardoRiggi/php-rest-authenticator/#readme)

<ins>Attenzione! Il progetto non è ancora totalmente ultimato, è in corso la revisione della documentazione, del codice e l'ottimizzazione del database</ins>

## Struttura del progetto

## Database

All'interno della cartella configurazioneDatabase è presente il file php-rest-authenticator.sql che contiene la struttura del database. 

## API Rest

Il progetto è suddiviso in macro aree / moduli. Per ogni modulo è presente un file che contiene tutti i servizi esposti. Per richiamare ciascun metodo è necessario invocare il servizio utilizzando la seguente struttura:

```markdown
rest/{nomeModulo}.php?nomeMetodo={nomeMetodo}
```

I metodi seguenti dovranno essere chiamati passando negli headers http un TOKEN:

```json
TOKEN	63f907cf1a77f9.37723667-63f907cf1a7aa9.47241054-63f907cf1a7c77.48547232-63f907cf1a7cb9.68996169-63f907cf1a7ce1.39915789-63f907cf1a7d35.59532876
```

### Combo.php

**getRegioni** | **GET**

Descrizione: Servizio che restituisce l'elenco delle regioni italiane


Headers aggiuntivi: nessuno

Body:

```json

```

Response

```json
[
    {
		"codiceRegione": 8,
		"descrizioneRegione": "Liguria",
	}
]
```

---

**getProvince** | **GET**

Descrizione: Servizio che restituisce l'elenco delle province italiane

Query param aggiuntivi: 

| Nome | Valore |
| --- | --- |
| codiceRegione | 8 |


Headers aggiuntivi: nessuno

Body:

```json

```

Response

```json
[
   {
		"codiceRegione": 8,
		"codiceProvincia": "GE",
		"descrizioneProvincia": "Genova",
	}
]
```

---

**getComuni** | **GET**

Descrizione: Servizio che restituisce l'elenco dei comuni italiani

Query param aggiuntivi: 

| Nome | Valore |
| --- | --- |
| codiceProvincia | GE |


Headers aggiuntivi: nessuno

Body:

```json

```

Response

```json
[
   {
		"codiceProvincia": "GE",
		"codiceComune": 10009,
		"descrizioneComune": "Campomorone",
		"residenti": 7279,
		"longitudine": "8.89267577",
		"latitudine": "44.50669550",
	}
]
```

---

**getTipologiaFiltriPersonali** | **GET**

Descrizione: Servizio che restituisce l'elenco delle tipologie dei filtri personali

Headers aggiuntivi: nessuno

Body:

```json

```

Response

```json
[  
    {
		"idTipoFiltroPersonale": "DISTANZA",
		"descrizione": "Ricevi le notifiche di eventi sismici avvenuti nel raggio di una distanza prefissata da un punto specifico",
	}
]
```

---

### Terremoti.php

**getTerremoti** | **GET**

Descrizione: Servizio che restituisce l'elenco dei terremoti dati dei filtri in input

Query param aggiuntivi: 

| Nome | Valore |
| --- | --- |
| pagina | 1 |
| magnitudo | 2 |
| distanza | 150 |
| latitudine | 45.45 |
| longitudine | 10.10 |
| dataInizioIntervallo | 2023-07-02 |
| dataFineIntervallo | 2023-07-05 |

Headers aggiuntivi: nessuno

Body:

```json

```

Response

```json
[
  {
		"id": "80599",
		"time": "1986-05-19 16:41:12",
		"latitude": 44.524,
		"longitude": 8.863,
		"depth": 10,
		"author": "BULLETIN-VAX",
		"catalog": "",
		"contributor": "",
		"contributorId": "",
		"magType": "Md",
		"magnitude": 2,
		"magAuthor": "--",
		"eventLocationName": "3 km NW Campomorone (GE)",
		"eventType": "earthquake\n",
		"dataCreazione": "2023-07-08 15:06:44",
		"distanza": 3,
	},
]
```

---

**getTerremoto** | **GET**

Descrizione: Servizio che restituisce un terremoto dato il suo id

Query param aggiuntivi: 

| Nome | Valore |
| --- | --- |
| id | 80599 |

Headers aggiuntivi: nessuno

Body:

```json

```

Response

```json
{
	"id": "80599",
	"time": "1986-05-19 16:41:12",
	"latitude": 44.524,
	"longitude": 8.863,
	"depth": 10,
	"author": "BULLETIN-VAX",
	"catalog": "",
	"contributor": "",
	"contributorId": "",
	"magType": "Md",
	"magnitude": 2,
	"magAuthor": "--",
	"eventLocationName": "3 km NW Campomorone (GE)",
	"eventType": "earthquake\n",
	"dataCreazione": "2023-07-08 15:06:44",
	"distanza": 3,
}
```

---

**getCoordinateDatoIndirizzo** | **GET**

Descrizione: Servizio che restituisce le coordinate geografiche dato un indirizzo

Query param aggiuntivi: 

| Nome | Valore |
| --- | --- |
| regione | Liguria |
| provincia | Genova |
| comune | Genova |
| indirizzo | Piazza De Ferrari |


Headers aggiuntivi: nessuno

Body:

```json

```

Response

```json
{
	"items": [
                {
                "position": {
				    "lat": 45.45,
				    "lng": 10.10
			    },
            }
    ]
}
```

---

**inserisciFiltroPersonale** | **POST**

Descrizione: Servizio che inserisce un filtro personale

Headers aggiuntivi: nessuno

Body:

```json
{
	"idTipoFiltroPersonale":"MAGNITUDO_DISTANZA",
	"nomeFiltro":"Nome del filtro",
	"codiceRegione":7,
	"descrizioneRegione":"Liguria",
	"codiceProvincia": "GE",
	"descrizioneProvincia":"Genova",
	"codiceComune":"10009",
	"descrizioneComune":"Campomorone",
	"cap":"16014",
	"latitudine":45.45,
	"longitudine":10.10,
	"distanza":10,
	"indirizzo":"Piazza Guglielmo Marconi",
	"magnitudo":0
}
```

Response

```json

```

---

**getFiltriPersonali** | **GET**

Descrizione: Servizio che restituisce l'elenco dei filtri personali

Query param aggiuntivi: 

| Nome | Valore |
| --- | --- |
| pagina | 1 |


Headers aggiuntivi: nessuno

Body:

```json

```

Response

```json
[
    {
		"idFiltroPersonale": 4,
		"idUtente": 1,
		"idTipoFiltroPersonale": "MAGNITUDO_DISTANZA",
		"nomeFiltro": "Nome del filtro",
		"codiceRegione": "7",
		"descrizioneRegione": "Liguria",
		"codiceProvincia": "GE",
		"descrizioneProvincia": "Genova",
		"codiceComune": "10009",
		"descrizioneComune": "Campomorone",
		"cap": "16014",
		"latitudine": 45.45,
		"longitudine": 10.1008,
		"magnitudo": 1,
		"distanza": 10,
		"indirizzo": "Piazza Guglielmo Marconi",
		"dataCreazione": "2000-06-12 00:00:00",
		"dataEliminazione": null,
		"descrizione": "Ricevi le notifiche di eventi sismici avvenuti nel raggio di una distanza prefissata da un punto specifico con una magnitudo superiore ad un valore di soglia",
	}
]
```

---

**getFiltroPersonale** | **GET**

Descrizione: Servizio che restituisce un filtro personale dato il suo id

Query param aggiuntivi: 

| Nome | Valore |
| --- | --- |
| idFiltroPersonale | 4 |


Headers aggiuntivi: nessuno

Body:

```json

```

Response

```json

    {
		"idFiltroPersonale": 4,
		"idUtente": 1,
		"idTipoFiltroPersonale": "MAGNITUDO_DISTANZA",
		"nomeFiltro": "Nome del filtro",
		"codiceRegione": "7",
		"descrizioneRegione": "Liguria",
		"codiceProvincia": "GE",
		"descrizioneProvincia": "Genova",
		"codiceComune": "10009",
		"descrizioneComune": "Campomorone",
		"cap": "16014",
		"latitudine": 45.45,
		"longitudine": 10.1008,
		"magnitudo": 1,
		"distanza": 10,
		"indirizzo": "Piazza Guglielmo Marconi",
		"dataCreazione": "2000-06-12 00:00:00",
		"dataEliminazione": null,
		"descrizione": "Ricevi le notifiche di eventi sismici avvenuti nel raggio di una distanza prefissata da un punto specifico con una magnitudo superiore ad un valore di soglia",
	}

```

---

**deleteFiltroPersonale** | **DELETE**

Descrizione: Servizio che elimina un filtro personale dato il suo id

Query param aggiuntivi: 

| Nome | Valore |
| --- | --- |
| idFiltroPersonale | 4 |


Headers aggiuntivi: nessuno

Body:

```json

```

Response

```json

```

---

**getDistanzaComuniDatoTerremoto** | **GET**

Descrizione: Servizio che restituisce l'elenco dei comuni con la relativa distanza dall'epicentro dato un id terremoto

Query param aggiuntivi: 

| Nome | Valore |
| --- | --- |
| pagina | 1 |
| id | 123456 |



Headers aggiuntivi: nessuno

Body:

```json

```

Response

```json
[
   {
		"codiceRegione": 8,
		"descrizioneRegione": "Liguria",
		"descrizioneProvincia": "Genova",
		"codiceProvincia": "GE",
		"codiceComune": 10009,
		"descrizioneComune": "Campomorone",
		"residenti": 7279,
		"longitudine": "8.89267577",
		"latitudine": "44.50669550",
		"distanza": 7,
	}
]
```

---

**getDistanzaLuoghiPersonaliDatoTerremoto** | **GET**

Descrizione: Servizio che restituisce l'elenco dei luoghi personali con la relativa distanza dall'epicentro dato un id terremoto

Query param aggiuntivi: 

| Nome | Valore |
| --- | --- |
| pagina | 1 |
| id | 123456 |



Headers aggiuntivi: nessuno

Body:

```json

```

Response

```json
[
   {
		"idFiltroPersonale": 5,
		"idTipoFiltroPersonale": "DISTANZA",
		"nomeFiltro": "Casa",
		"codiceRegione": "8",
		"descrizioneRegione": "Liguria",
		"codiceProvincia": "GE",
		"descrizioneProvincia": "Genova",
		"codiceComune": "10025",
		"descrizioneComune": "Genova",
		"cap": "16100",
		"latitudine": 45.45,
		"longitudine": 10.10,
		"magnitudo": 0,
		"distanza": 7,
		"indirizzo": "Via Programmatore, 101011, 16100 Genova GE, Italia",
		"dataCreazione": "2000-06-12 00:00:00",
	}
]
```

---

**getCronJobs** | **GET**

Descrizione: Servizio che restituisce l'elenco dei timestamp dell'esecuzione del batch di aggiornamento della banca dati

Query param aggiuntivi: 

| Nome | Valore |
| --- | --- |
| pagina | 1 |



Headers aggiuntivi: nessuno

Body:

```json

```

Response

```json
[
   {
		"idCronJob": 1,
		"dataEvento": "2000-06-12 00:00:00",
	}
]
```

---

## Bom / Diba

Il codice è scritto in php nativo, non sono stati utilizzati framework. 

## Licenza
Il codice sorgente viene rilasciato con licenza [MIT](https://github.com/RiccardoRiggi/earthquake-monitor-be/blob/main/LICENSE). Le varie esensioni di php utilizzate mantengono le loro relative licenze. I dati dei terremoti sono di proprietà dell'[Istituto Nazionale di Geofisica e Vulcanologia](https://www.ingv.it/) e vengono distribuiti con licenza [Creative Commons Attribution 4.0 International License](http://creativecommons.org/licenses/by/4.0/). I dati relativi a Regioni, Province e Comuni vengono distribuiti dall'[Istituto nazionale di statistica](https://www.istat.it/it/) con licenza ( Creative Commons – Attribuzione – versione 3.0)[https://creativecommons.org/licenses/by/3.0/it/]. I dati relativi alle coordinate geografiche vengono recuperati ed elaborati tramite le API di [Here](https://www.here.com/get-started/pricing).   
  

## Garanzia limitata ed esclusioni di responsabilità

Il software viene fornito "così com'è", senza garanzie. Riccardo Riggi non concede alcuna garanzia per il software e la relativa documentazione in termini di correttezza, accuratezza, affidabilità o altro. L'utente si assume totalmente il rischio utilizzando questo applicativo.