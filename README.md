# OpenDistributoriCarburantiBot

## Informazioni generali

Il codice di questo "bot" permette di ricercare il distributore di carburante piu' economico nell intorno di un punto di interesse o all'interno dell'area in un Comune italiano.
 
Se non si puo', o non si vuole, fornire l'attuale posizione il bot prova lo stesso a dare una indicazione: se si fornisce il nome di un Comune italiano si indicherà il/i distributori più economici all'interno del territorio del Comune indicato e li si potra' comunque visualizzare su mappa.

Per fornire la posizione e' sufficiente cliccare sulla graffetta che compare nella videata del telefono / smartphone  e poi selezionare l'opzione 'Posizione'.

Per indicare il Comune di interesse e' sufficiente scriverne il nome.

Verra' interrogata una copia del DataBase OpenData del Ministero dello Sviluppo Economico (rif. http://www.sviluppoeconomico.gov.it/index.php/it/open-data/elenco-dataset/2032336-carburanti-prezzi-praticati-e-anagrafica-degli-impianti),utilizzabile con licenza iodl2.0 (rif. http://www.dati.gov.it/iodl/2.0/).

I dati sono aggiornati giornalmente dalle ore 9.00.

Per maggiori dettagli http://cesaregerbino.wordpress.com/yyyyyyyyyy



## Dati e servizi utilizzati

I dati dei distributori sono forniti dal DataBase OpenData del Ministero dello Sviluppo Economico (rif. http://www.sviluppoeconomico.gov.it/index.php/it/open-data/elenco-dataset/2032336-carburanti-prezzi-praticati-e-anagrafica-degli-impianti),utilizzabile con licenza iodl2.0 (rif. http://www.dati.gov.it/iodl/2.0/).

I dati sono ospitati su un database open source SQLITE3 (rif. https://www.sqlite.org/ ) + SpatiaLite (rif. http://www.gaia-gis.it/gaia-sins/).

Le mappe utilizzate sono quelle derivate dai dati di OpenStreetMap (rif. http://www.openstreetmap.org/) e OSMBuildings (rif. http://www.osmbuildings.org/).

Il calcolo dei percorsi viene realizzato avvalendosi del servizio di routing di MapQuest (rif. https://developer.mapquest.com/products/directions).

L'abbreviazione delle url viene realizzata avvalendosi del servizio Google URL Shortener (rif. https://goo.gl/).
 

## Architettura e dettagli tecnici

Schematicamente l'architettura della soluzione è la seguente:

![alt tag](https://github.com/cesaregerbino/OpenDistributoriCarburantiBot/blob/master/Doc/DistributoriCarburanti.png)


Nel diagramma sono citati i vari componenti del sistema che:

* è ospitato su una macchina Ubuntu 14.0.4 LTS
* utilizza come database SQLite3 + Spatialite 4.4.0
* implementa la logica di business in PHP 5
* si avvale di servizi di OpenStreetMap, OSM Buildings, Mapquest e Google ShortLink

Si tratta di una soluzione che utilizza tutti software open source e attinge da servizi disponibili in rete.

Per una soluzione più scalabile è possibile passare ad utilizzare POSTGRESQL con la sua estensione POSTGIS, non cambiando la logica di funzionamento.

Nel diagramma sono anche illustrati i passi delle diverse operazioni coinvolte e precisamente:

* giornalmente il sistema scarica dal MISE (0a) 
* i dati e li memorizza sul db (0b)
* l'utente invia una richiesta dal proprio client (1)
* il sistema recupera le richieste con un'operazione di getUpdates (2)
* effettua le ricerche su DB (3)
* nell'elaborazione della risposta invoca i servizi di Google Shortlink per abbreviare le url (4)
* con un'operazione di sendMessage risponde (5)
* Telegram inoltra la risposta al client (6)
* l'utente può fruire dei diversi link contenuti nella risposta (7, 7a, 7b e 7c)
   
Per maggiori dettagli http://cesaregerbino.wordpress.com/xxxxxxxxxxxx.


## Note per l'utilizzo del codice

E' necessario impostare il valore di alcune variabili di configurazione nel file 'settings_t.php' che devono riflettere il posizionamento su file system della directory OpenDistributoriCarburantiBot. 

Ad esempio se tale cartella è posizionata in 

/var/www/html/Telegram/OpenDistributoriCarburantiBot

* la variabile BASE_PATH assumerà il valore '/var/www/html/Telegram/OpenDistributoriCarburantiBot'
* la variabile BASE_URL assumerà il valore 'http://www.mysite.com/Telegram/OpenDistributoriCarburantiBot'

E' necessario disporre di
* una chiave per l'utilizzo delle API di Google Shortner
* l'ID di un Bot Telegram
* un eventuale url per il webhook
* una chiave per l'utilizzo delle API di MapQuest Routing

Nel pacchetto è già reso disponibile un database alimentato (PrezziCarburanti): per aggiornare i dati occorre lanciare il comando 

CreaPrezziCarburanti.sh

disponibile nella sottocartella 'Datiì


## Credits

Ringraziamenti e credits vanno a:
* Stefano Sabatini, per il suo lavoro iniziale da cui ho tratto spunto per l'idea
* Matteo Tempestini, Francesco (Piersoft) Paolicelli e Gabriele Grillo da cui ho tratto il codice iniziale e per il loro supporto via mail
* Alessandro Furieri, per il suo grandissimo lavoro su Spatialite, data base open source che ho usato come tool per memorizzare i dati georiferiti, e per il suo paziente supporto online
* Fabrizio Massara per la pazienza e supporto  nonche', per la condivisione del server su cui sta' girando il codice del bot in produzione


## Riferimenti e licenza d'uso

Questo bot e' stato realizzato a titolo sperimentale  da Cesare Gerbino (cesare.gerbino@gmail.com).

Il codice dell'applicazione e' disponibile con licenza [Licenza MIT] (https://opensource.org/licenses/MIT) Copyright (c) [2014] - rif. [https://it.wikipedia.org/wiki/Licenza_MIT] (https://it.wikipedia.org/wiki/Licenza_MIT)
