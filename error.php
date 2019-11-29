<?php

// set error status to 403 if called directly
if (isset($_SERVER['REDIRECT_STATUS'])) {
  if (isset($_SERVER['REDIRECT_STATUS']) == 200 && http_response_code() == 200) {
    http_response_code(403);
  }
} else {
  http_response_code(403);
}

$status = http_response_code();
$codes = array(
  400 => array('400 Bad Request', 'Your browser sent a request that this server could not understand.'),
  401 => array('401 Unauthorized', 'This server could not verify that you are authorized to access the document requested.'),
  403 => array('403 Forbidden', 'The server has refused to fulfill your request.'),
  404 => array('404 Not Found', 'The document/file requested was not found on this server.'),
  405 => array('405 Method Not Allowed', 'The method specified in the Request-Line is not allowed for the specified resource.'),
  408 => array('408 Request Timeout', 'Your browser failed to send a request in the time allowed by the server.'),
  500 => array('500 Internal Server Error', 'The request was unsuccessful due to an unexpected condition encountered by the server.'),
  502 => array('502 Bad Gateway', 'The server received an invalid response from the upstream server while trying to fulfill the request.'),
  504 => array('504 Gateway Timeout', 'The upstream server failed to send a request in the time allowed by the server.'),
);

$title = $codes[$status][0];
$message = $codes[$status][1];

if ($title == false || strlen($status) != 3) {
  $message = 'Please supply a valid status code.';
}

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title><?php echo $title; ?></title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />
  <style>
    body {
      background-color: #e4e4e4;
      color: #3C4858;
      margin: 0;
      text-align: left;
      display: block;
    }
    body, h1, h2, h3, h4, h5, h6 {
      font-family: "Roboto", "Helvetica", "Arial", sans-serif;
      font-weight: 300;
      line-height: 1.5em;
    }
    h1 {
      font-size: 3.3125rem;
      line-height: 1.15em;
    }
    h1, h2, h3, h4, h5, h6 {
      margin-top: 20px;
      margin-bottom: 10px;
    }
    p {
      font-size: 14px;
      margin: 0 0 10px;
    }
    .page-header {
      height: 100vh;
      background-position: center center;
      background-size: cover;
      margin: 0;
      padding: 0;
      border: 0;
      display: flex;
      align-items: center;
    }
    .container {
      width: 100%;
      padding-right: 15px;
      padding-left: 15px;
      margin-right: auto;
      margin-left: auto;
    }
    .text-center {
      text-align: center !important;
    }
    .text-muted {
      color: #6c757d !important;
    }
  </style>
</head>
<body>
  <div class="page-header">
    <div class="container text-center">
    	<h1 class="text-muted"><?php echo $title; ?></h1>
      <p class="text-muted"><?php echo $message; ?></p>
    </div>
  </div>
</body>
</html>