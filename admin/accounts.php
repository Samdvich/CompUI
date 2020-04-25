<?php
    session_start();

    $servername = "localhost";
    $username = "spage65";
    $password = "Password1";
    $DB_Name = "spage65_CompUI"; # Using 2020 PHP Default BCRYPT Hash = password_hash($password, PASSWORD_BCRYPT)

    $conn = new mysqli($servername, $username, $password, $DB_Name); // Create connection
     
    if ($conn->connect_error) // Check connection
      {die("Not Connected: <br>" . $conn->connect_error);}
    else
      {$database_variable = "Connected";}
    
    if ($_SESSION['type'] !== "admin") // Authenticate
      {header("Location: ../summary.php");}
      
    $secure = $conn->prepare('SELECT colour, members FROM houses WHERE house = ?');
    $secure->bind_param('s', $_SESSION['house']);
    $secure->execute();
    $secure->store_result();
    $secure->bind_result($house_color, $members);
    $secure->fetch();
?>

<!DOCTYPE HTML>
<html>
  <head>
    <title>Accounts | Admin</title>
  </head>
  <body>
    <div class='header'>
      <a href='../summary.php' id='home'><svg class="bi bi-house-fill" width="3em" height="3em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 3.293l6 6V13.5a1.5 1.5 0 01-1.5 1.5h-9A1.5 1.5 0 012 13.5V9.293l6-6zm5-.793V6l-2-2V2.5a.5.5 0 01.5-.5h1a.5.5 0 01.5.5z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M7.293 1.5a1 1 0 011.414 0l6.647 6.646a.5.5 0 01-.708.708L8 2.207 1.354 8.854a.5.5 0 11-.708-.708L7.293 1.5z" clip-rule="evenodd"/></svg></a>
      <p id='heading'><?php echo $_SESSION['house']; ?></p>
    </div>
    
    <div class='new-comp'>
        <!-- ... -->
    </div>
    
    <style>
      @import url('https://fonts.googleapis.com/css?family=Bungee&display=swap');
      @import url('https://fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700,800,900&display=swap');
      
      @keyframes poof { from {visibility: visible;} to {visibility: hidden;} }
      
      html, body { display: grid; margin: 0; padding: 0; height: 100%; width: 100%; }
      
      body { grid-template-rows: 18% 80%; grid-template-columns: 30% 50% 20%; }
      
      .header { grid-row: 1; grid-column: 1 / 4; background-color: <?php echo $house_color; ?>; height: 100%; width: 100%; font-family: 'Bungee', regular; display: grid; grid-template-columns: 8% auto 8%; }
      
      #home { grid-column: 1; color: white; margin: auto; }
      
      #heading { grid-column: 2; font-size: 350%; color: white; margin: auto; }
      
      .competition-table { width: 100%; font-family: 'Bungee', regular; font-size: 200%; }
      
      .new-comp { box-sizing: border-box; padding: 5%; grid-row: 2; grid-column: 1; height: 80%; width: 80%; background-color: <?php echo $house_color ?>; margin: auto; }
      
      #event { font-family: 'Raleway'; font-weight: 200; }
      
      #event-label { display: grid; color: white; font-family: 'Raleway'; font-size: 120%; margin-left: 5%; }
        
      #event-name-field { display: grid; border-radius: 50px; border: 0 solid transparent; width: 90%; height: 45px; font-size: 150%; padding-left: 5%; padding-right: 5%; font-family: 'Raleway'; text-transform: capitalize; margin-top: 4%; }
      
      #event-button { all: unset; display: grid; font-family: Bungee; font-size: 155%; color: white; margin: auto; margin-top: 5%; }
      
      #event-button:hover { color: whitesmoke; }
      
      #event-create-button:active { font-size: 140%; }
      
      a { all: unset; cursor: pointer; }
    </style>
  </body>
</html>