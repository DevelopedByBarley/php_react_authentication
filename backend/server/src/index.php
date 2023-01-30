<?php
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
     "$url/registration" => "registrationHandler",
     "$url/login" => "loginHandler",
    ]
  ];


  $handlerFunction = $routes[$method][$path] ?? "notFoundHandler";

  $handlerFunction();

  
  function registrationHandler() {
    $_POST = json_decode(file_get_contents('php://input'), true);
    $hashed = password_hash($_POST["password"], PASSWORD_DEFAULT);

    $pdo = createConnection();
    $statement = $pdo -> prepare(
      "INSERT INTO `users` (`id`, `email`, `password`, `createdAt`) VALUES (NULL, ?, ?, ?)"
    );
    $statement -> execute([
      $_POST["email"],
      $hashed,
      time()
    ]);
  }


  function loginHandler() {
    echo "LOGIN";
  }
  
  
  
  
  
  
  
  
  function homeHandler() {
    echo "Home";
  }



  function notFoundHandler() {

    echo "Route not found";
  }
