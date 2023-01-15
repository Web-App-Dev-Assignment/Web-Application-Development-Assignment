<!DOCTYPE HTML>  
<html>
<head>
  <title>ShootingGame</title>
  <meta charset="UTF-8">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
  <link rel="stylesheet" href="../css/stylesheet.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<style>
  body{
    margin:0;
  }
</style>
</head>
<body style="max-width: none;">
  <!--<div class= "fixed">
    <span>Score:</span>
    <span >0</span>
  </div>-->

  <div class="fixed inset-0 flex items-center justify-center">
    <div class="bg-white max-w-md w-full">
      <h1 id="score">0</h1>
      <p>Scores</p>
    <div>
        <button id="startGame">Start</button>
        <button id="backMain" onclick="document.location='index.php'">Back to the main Page</button>
      </div>
    </div>
  </div>
  <canvas></canvas>
</body>
</html>

<script src="../javascript/function.js"></script>
<script src="../javascript/onlinestatus.js"></script>
<script src="../javascript/ShootingGame.js"></script>