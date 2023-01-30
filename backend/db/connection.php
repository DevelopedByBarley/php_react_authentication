<?php


function createConnection()
{
  $serverName = "localhost";
  $dbName = "countries";
  $userName = "Barley";
  $password = "Csak1enter";

  return new PDO(
    'mysql:host=' . $serverName . ';dbname=' . $dbName,
    $userName,
    $password
  );
}
