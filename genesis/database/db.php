<?php

$ini = parse_ini_file( __DIR__ . '/dbconfig.ini');

//var_dump($ini);
//exit;

try {
    $db = new PDO (  "mysql:host=" . $ini['server'] . 
                     ";port=" . $ini['port'] . 
                     ";dbname=" . $ini['dbname'], 
                     $ini['username'], 
                     $ini['password']
                  );

    //echo "mysql:host=". $ini['server']. ";port=". $ini['port']. ";dbname=" . $ini['dbname']. $ini['username']. $ini['password'];
    //exit;
    
    echo "<div><p style='background-color: green;'>Connection passed...</p></div>";

    $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch (PDOException $e) {

    echo "<div style='border:1px solid black; message: " . $db->getMessage(). "</div>";
}

//var_dump($db);

