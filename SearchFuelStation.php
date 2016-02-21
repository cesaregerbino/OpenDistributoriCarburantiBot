<?php
  /**
   * Search the fuel stations  
   * Author: Cesare Gerbino code in https://github.com/cesaregerbino/????? 
  */

function SearchFuelStation($comune, $carburante, $lat, $lon, $dist, $my_lat, $my_lon) 
  {
   $base_path = BASE_PATH;

   # DB Connection ...
   $db = new SQLite3($base_path.'/PrezziCarburanti');

   # Loading SpatiaLite as an extension ...
   $db->loadExtension('mod_spatialite.so');

# Get the Comune name ...
#$comune = $_GET['comune'];
#echo 'Comune = '.$comune;
#echo '<br>';

# Get the Carburante type ...
#$carburante = $_GET['carburante'];
#echo 'Carburante = '.$carburante;
#echo '<br>';

# Get the Latitude ...
#$lat = $_GET['lat'];
#echo 'Latitudine = '.$lat;
#echo '<br>';

# Get the Longitude ...
#$lon = $_GET['lon'];
#echo 'Longitudine = '.$lon;
#echo '<br>';

# Get the Distance to search ...
#$dist = $_GET['dist'];
#echo 'Distanza di ricerca = '.$dist;
#echo '<br>';

# Get my Location Latitude  ...
#$my_lat = $_GET['my_lat'];
#echo 'My Latitudine = '.$my_lat;
#echo '<br>';

# Get my Location Longitude ...
#$my_lon = $_GET['my_lon'];
#echo 'My Longitudine = '.$my_lon;
#echo '<br>';


#echo '<br>';

$data="";

$limit_search = 10;

$base_url = BASE_URL;
 
if (empty($comune) && empty($carburante) && empty($lat) && empty($lon) && empty($dist))
   {
    print 'Mancano i parametri per effettuare una ricerca!!!';
    print '<br>';
   }
if (!empty($comune))
   {
       if (empty($carburante) && empty($lat) && empty($lon) && empty($dist))
          {
           $q="SELECT distr.Comune, distr.Gestore, distr.Indirizzo, distr.Bandiera, distr.Latitudine, distr.Longitudine, prz.descCarburante, prz.prezzo, prz.dtComu
           FROM anagrafica_impianti_attivi as distr
           JOIN prezzo_alle_8 as prz ON (prz.idImpianto = distr.IdImpianto) WHERE distr.Comune = :Comune ORDER BY prz.prezzo ASC";
           try {
                $stmt = $db->prepare($q);
                $stmt->bindvalue(':Comune', $comune, SQLITE3_TEXT);
                $results = $stmt->execute();
                $count = 0;
                while (($row = $results->fetchArray(SQLITE3_ASSOC)) AND ($count < $limit_search)){
                  $data .= "Marca: ".$row['Bandiera'];
                  $data .= "\n";
                  $data .= "Carburante: ".$row['descCarburante'];
                  $data .= "\n";
                  $data .= "Prezzo: ".$row['prezzo'];
                  $data .= "\n";
                  $data .= "Data: ".$row['dtComu'];
                  $data .= "\n";

                  $longUrl = "http://www.openstreetmap.org/?mlat=".$row['Latitudine']."&mlon=".$row['Longitudine']."&zoom=18";
                  $shortUrl = CompactUrl($longUrl);
                  $data .= "Mappa: ".$shortUrl;                  

                  $data .= "\n";
                  $data .= "\n";
                  $count = $count + 1;
                }
                if ($count >= $limit_search){
                  $data .= "La ricerca ha prodotto troppi risultati !!! Si presentano solo i primi ".$limit_search.". Per maggiori dettagli provare a cambiare comune, tipo di carburante, posizione o raggio di ricerca";
                  $data .= "\n";
                }
                return $data;                                
               }
           catch(PDOException $e) {
             print "Something went wrong or Connection to database failed! ".$e->getMessage();
           }
          }


       if (!empty($carburante) && empty($lat) && empty($lon) && empty($dist))
          {
           $q="SELECT distr.Comune, distr.Gestore, distr.Indirizzo, distr.Bandiera, distr.Latitudine, distr.Longitudine, prz.descCarburante, prz.prezzo, prz.dtComu
           FROM anagrafica_impianti_attivi as distr
           JOIN prezzo_alle_8 as prz ON (prz.idImpianto = distr.IdImpianto) WHERE distr.Comune = :comune AND prz.descCarburante = :carburante ORDER BY prz.prezzo ASC";
           try {
                $stmt = $db->prepare($q);
                $stmt->bindvalue(':comune', $comune, SQLITE3_TEXT);
                $stmt->bindvalue(':carburante', $carburante, SQLITE3_TEXT);
                $results = $stmt->execute();
                $count = 0;
                while (($row = $results->fetchArray(SQLITE3_ASSOC)) AND ($count < $limit_search)){
                  $data .= "Marca: ".$row['Bandiera'];
                  $data .= "\n";
                  $data .= "Carburante: ".$row['descCarburante'];
                  $data .= "\n";
                  $data .= "Prezzo: ".$row['prezzo'];
                  $data .= "\n";
                  $data .= "Data: ".$row['dtComu'];
                  $data .= "\n";

                  $longUrl = "http://www.openstreetmap.org/?mlat=".$row['Latitudine']."&mlon=".$row['Longitudine']."&zoom=18";
                  $shortUrl = CompactUrl($longUrl);
                  $data .= "Mappa: ".$shortUrl;                  
                  $data .= "\n";

                  if (!empty($my_lat) && !empty($my_lon)) 
                     {
                      $longUrl = $base_url."/RenderRoute.php?lat_from=".$lat."&lon_from=".$lon."&lat_to=".$row['Latitudine']."&lon_to=".$row['Longitudine']."&map_type=0";
                      $shortUrl = CompactUrl($longUrl);
                      $data .= "Descrizione Percorso: ".$shortUrl;                  
                      $data .= "\n";

                      $longUrl = $base_url."/RenderRoute.php?lat_from=".$lat."&lon_from=".$lon."&lat_to=".$row['Latitudine']."&lon_to=".$row['Longitudine']."map_type=2";
                      $shortUrl = CompactUrl($longUrl);
                      $data .= "Percorso su mappa 2D: ".$shortUrl;                  
                      $data .= "\n";

                      $longUrl = $base_url."/RenderRoute.php?lat_from=".$lat."&lon_from=".$lon."&lat_to=".$row['Latitudine']."&lon_to=".$row['Longitudine']."map_type=3";
                      $shortUrl = CompactUrl($longUrl);
                      $data .= "Percorso su mappa 3D: ".$shortUrl;                  
                      $data .= "\n";
                     }
                  $data .= "\n";
                  $count = $count + 1;
                }
                if ($count >= $limit_search){
                   $data .= "La ricerca ha prodotto troppi risultati !!! Si presentano solo i primi ".$limit_search.". Per maggiori dettagli provare a cambiare comune, tipo di carburante, posizione o raggio di ricerca";
                   $data .= "\n";
                }
                return $data;                                
               }
           catch(PDOException $e) {
             print "Something went wrong or Connection to database failed! ".$e->getMessage();
           }
          }

   }

if (!empty($lat) && !empty($lon) && !empty($dist))
   {
       if (empty($carburante))
          {
           $q='SELECT distr.Comune, distr.Gestore, distr.Indirizzo, distr.Bandiera, distr.Latitudine, distr.Longitudine, prz.descCarburante, prz.prezzo, prz.dtComu,
                      ST_Distance(distr.geometry, MakePoint('.$lon.', '.$lat.', 4326), 1) AS dist          
              FROM anagrafica_impianti_attivi as distr
              JOIN prezzo_alle_8 as prz ON (prz.idImpianto = distr.IdImpianto)
              WHERE dist <= '.$dist.'
              ORDER BY prz.prezzo ASC, dist ASC';

           try {
               $stmt = $db->prepare($q);
               $results = $stmt->execute();
               $count = 0;
               while (($row = $results->fetchArray(SQLITE3_ASSOC)) AND ($count < $limit_search)){
                 $data .= "Marca: ".$row['Bandiera'];
                 $data .= "\n";
                 $data .= "Carburante: ".$row['descCarburante'];
                 $data .= "\n";
                 $data .= "Prezzo: ".$row['prezzo'];
                 $data .= "\n";
                 $data .= "Data: ".$row['dtComu'];
                 $data .= "\n";

                 $longUrl = "http://www.openstreetmap.org/?mlat=".$row['Latitudine']."&mlon=".$row['Longitudine']."&zoom=18";
                 $shortUrl = CompactUrl($longUrl);
                 $data .= "Mappa: ".$shortUrl;                  
                 $data .= "\n";

                 $longUrl = $base_url."/RenderRoute.php?lat_from=".$lat."&lon_from=".$lon."&lat_to=".$row['Latitudine']."&lon_to=".$row['Longitudine']."&map_type=0";
                 $shortUrl = CompactUrl($longUrl);
                 $data .= "Descrizione Percorso: ".$shortUrl;                  
                 $data .= "\n";

                 $longUrl = $base_url."/RenderRoute.php?lat_from=".$lat."&lon_from=".$lon."&lat_to=".$row['Latitudine']."&lon_to=".$row['Longitudine']."&map_type=2";
                 $shortUrl = CompactUrl($longUrl);
                 $data .= "Percorso su mappa 2D: ".$shortUrl;                  
                 $data .= "\n";

                 $longUrl = $base_url."/RenderRoute.php?lat_from=".$lat."&lon_from=".$lon."&lat_to=".$row['Latitudine']."&lon_to=".$row['Longitudine']."&map_type=3";
                 $shortUrl = CompactUrl($longUrl);
                 $data .= "Percorso su mappa 3D: ".$shortUrl;                  
                 $data .= "\n";

                 $data .= "\n";
                 $count = $count + 1;
              }
              if ($count >= $limit_search){
                 $data .= "La ricerca ha prodotto troppi risultati !!! Si presentano solo i primi ".$limit_search.". Per maggiori dettagli provare a cambiare comune, tipo di carburante, posizione o raggio di ricerca";
                 $data .= "\n";
              }
               
              return $data;                                
           }
           catch(PDOException $e) {
                print "Something went wrong or Connection to database failed! ".$e->getMessage();
           }
          }

       if (!empty($carburante))
          {
           $q='SELECT distr.Comune, distr.Gestore, distr.Indirizzo, distr.Bandiera, distr.Latitudine, distr.Longitudine, prz.descCarburante, prz.prezzo, prz.dtComu,
                      ST_Distance(distr.geometry, MakePoint('.$lon.', '.$lat.', 4326), 1) AS dist          
              FROM anagrafica_impianti_attivi as distr
              JOIN prezzo_alle_8 as prz ON (prz.idImpianto = distr.IdImpianto)
              WHERE dist <= '.$dist.' AND prz.descCarburante = \''.$carburante.'\'
              ORDER BY prz.prezzo ASC, dist ASC';

           try {
               $stmt = $db->prepare($q);
               $results = $stmt->execute();
               $count = 0;
               while (($row = $results->fetchArray(SQLITE3_ASSOC)) AND ($count < $limit_search)){
                 $data .= "Marca: ".$row['Bandiera'];
                 $data .= "\n";
                 $data .= "Carburante: ".$row['descCarburante'];
                 $data .= "\n";
                 $data .= "Prezzo: ".$row['prezzo'];
                 $data .= "\n";
                 $data .= "Data: ".$row['dtComu'];
                 $data .= "\n";

                 $longUrl = "http://www.openstreetmap.org/?mlat=".$row['Latitudine']."&mlon=".$row['Longitudine']."&zoom=18";
                 $shortUrl = CompactUrl($longUrl);
                 $data .= "Mappa: ".$shortUrl;                  
                 $data .= "\n";

                 $longUrl = $base_url."/RenderRoute.php?lat_from=".$lat."&lon_from=".$lon."&lat_to=".$row['Latitudine']."&lon_to=".$row['Longitudine']."&map_type=0";
                 $shortUrl = CompactUrl($longUrl);
                 $data .= "Descrizione Percorso: ".$shortUrl;                  
                 $data .= "\n";

                 $longUrl = $base_url."/RenderRoute.php?lat_from=".$lat."&lon_from=".$lon."&lat_to=".$row['Latitudine']."&lon_to=".$row['Longitudine']."&map_type=2";
                 $shortUrl = CompactUrl($longUrl);
                 $data .= "Percorso su mappa 2D: ".$shortUrl;                  
                 $data .= "\n";

                 $longUrl = $base_url."/RenderRoute.php?lat_from=".$lat."&lon_from=".$lon."&lat_to=".$row['Latitudine']."&lon_to=".$row['Longitudine']."&map_type=3";
                 $shortUrl = CompactUrl($longUrl);
                 $data .= "Percorso su mappa 3D: ".$shortUrl;                  
                 $data .= "\n";

                 $data .= "\n";
                 $count = $count + 1;
              }
              if ($count >= $limit_search){
                 $data .= "La ricerca ha prodotto troppi risultati !!! Si presentano solo i primi ".$limit_search.". Per maggiori dettagli provare a cambiare comune, tipo di carburante, posizione o raggio di ricerca";
                 $data .= "\n";
              }
              return $data;                                
           }
           catch(PDOException $e) {
                 print "Something went wrong or Connection to database failed! ".$e->getMessage();
           }
          }
          
   }

 }


function CompactUrl($longUrl) 
  {
   $apiKey = API;

   $postData = array('longUrl' => $longUrl);
   $jsonData = json_encode($postData);

   $curlObj = curl_init();

   curl_setopt($curlObj, CURLOPT_URL, 'https://www.googleapis.com/urlshortener/v1/url?key='.$apiKey.'&fields=id');
   curl_setopt($curlObj, CURLOPT_RETURNTRANSFER, 1);
   curl_setopt($curlObj, CURLOPT_SSL_VERIFYPEER, 0);
   curl_setopt($curlObj, CURLOPT_HEADER, 0);
   curl_setopt($curlObj, CURLOPT_HTTPHEADER, array('Content-type:application/json'));
   curl_setopt($curlObj, CURLOPT_POST, 1);
   curl_setopt($curlObj, CURLOPT_POSTFIELDS, $jsonData);

   $response = curl_exec($curlObj);

   // Change the response json string to object
   $json = json_decode($response);

   curl_close($curlObj);
   $shortLink = get_object_vars($json);
   
   return $shortLink['id'];
 }

?>



