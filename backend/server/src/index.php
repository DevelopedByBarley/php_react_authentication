<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");

$method = $_SERVER["REQUEST_METHOD"];
$parsed = parse_url($_SERVER["REQUEST_URI"]);
$path = $parsed["path"];
$url = "/backend/server/src/index.php";


// Connect to the database
require "../../db/connection.php";


$routes = [
  "GET" => [
    "$url" => "homeHandler"
  ],
  "POST" => [
    "$url/register" => "registrationHandler",
    "$url/login" => "loginHandler",
  ]
];


$handlerFunction = $routes[$method][$path] ?? "notFoundHandler";

$handlerFunction();


function registrationHandler()
{
  $_POST = json_decode(file_get_contents('php://input'), true);
  $email = $_POST["email"];
  $hashed = password_hash($_POST["password"], PASSWORD_DEFAULT);
  

  
  $pdo = createConnection();


  $statement = $pdo -> prepare("SELECT * FROM `users` WHERE email = ?");
  $statement -> execute([$email]);
  $isEmailExist = $statement->fetch(PDO::FETCH_ASSOC);

  if($isEmailExist) {
    echo "Email is alredy exist!";
    return; 
  }  
 


  $statement = $pdo->prepare(
    "INSERT INTO `users` (`id`, `email`, `password`, `createdAt`) VALUES (NULL, ?, ?, ?)"
  );
  $statement->execute([
    $email,
    $hashed,
    time()
  ]);

  echo "Registered!";
}


function loginHandler()
{
  echo "LOGIN";
}








function homeHandler()
{
  echo "Home";
}



function notFoundHandler()
{

  echo "Route not found";
}
