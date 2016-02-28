<?php
  /**
   * Update data in access_numbers table
   * Author: Cesare Gerbino 
  */
  
  date_default_timezone_set('Europe/Rome');
  $today = (string)date("Y-m-d");

  //## Update data in access_numbers table ...
  $access_counter = 0;
  $db_data_sessions = new SQLite3($base_path.'/DataSessionsDB');
  $insert = "INSERT INTO access_numbers (Date, Counter) VALUES (:Date, :Counter)";
  try {
       $stmt = $db_data_sessions->prepare($insert);

       //## Bind parameters to statement variables
       $stmt->bindParam(':Date', $today);
       $stmt->bindParam(':Counter', $access_counter);
             
       $results = $stmt->execute();
  }               
  catch(PDOException $e) {
        print "Something went wrong or Connection to database failed! ".$e->getMessage();
  }
  $db_data_sessions = null;
?>