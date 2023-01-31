<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");


require dirname(__DIR__, 2) . "/vendor/autoload.php";


use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

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


  $statement = $pdo->prepare("SELECT * FROM `users` WHERE email = ?");
  $statement->execute([$email]);
  $isEmailExist = $statement->fetch(PDO::FETCH_ASSOC);

  if ($isEmailExist) {
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

  $_POST = json_decode(file_get_contents('php://input'), true);


  $pdo = createConnection();
  $statement = $pdo->prepare("SELECT * FROM `users` WHERE email = ?");

  $statement->execute([
    $_POST["email"]
  ]);

  $user = $statement->fetch(PDO::FETCH_ASSOC); // -> ha a fetch nem sikerül, false értékkel tér vissza!

  if (!$user) {
    echo "Invalid email or password";
    return;
  };



  $isVeryfied = password_verify($_POST["password"],  $user["password"]);

  if (!$isVeryfied) {
    echo "Invalid email or password";
    return;
  }

  $key = $_ENV['SECRET_KEY'];

  $payload = [
    "sub" => $user["id"],
  ];


  $jwt = JWT::encode($payload, $key, 'HS256');

  echo $jwt;
}



function getTokenFromHeaderOrSendErrorReponse()
{
  $headers = getallheaders();
  $isFound = preg_match(
    '/Bearer\s(\S+)/',
    $headers['Authorization'] ?? '',
    $matches
  );
  if (!$isFound) {
    http_response_code(401);
    echo json_encode(['error' => 'unauthorized']);
    exit;
  }
  return $matches[1];
}






function homeHandler()
{

  $userId = decodeToken();


  if (!$userId) {
    echo "Something went wrong!";
    return;
  }

  $pdo = createConnection();
  $statement = $pdo->prepare("SELECT * FROM `countries`");
  $statement->execute();
  $countries = $statement->fetchAll(PDO::FETCH_ASSOC);

  echo json_encode($countries);
}

function decodeToken()
{
  $token = getTokenFromHeaderOrSendErrorReponse();
  if ($token && $token !== "") {
    $decodedToken = JWT::decode($token ?? "", new Key($_ENV["SECRET_KEY"], 'HS256'));
    $userId = $decodedToken->sub;
    return $userId;
  }
}



function notFoundHandler()
{

  echo "Route not found";
}
