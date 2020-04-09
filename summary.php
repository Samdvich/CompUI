<?php
    session_start();

    $servername = "localhost";
    $username = "spage65";
    $password = "Password1";
    $DB_Name = "spage65_CompUI"; # Using 2020 PHP Default BCRYPT Hash = password_hash($password, PASSWORD_BCRYPT)

    // Create connection
    $conn = new mysqli($servername, $username, $password, $DB_Name);
     
    // Check connection
      if ($conn->connect_error) {
        die("Not Connected: <br>" . $conn->connect_error);
    }
    else
    $database_variable = "Connected";
    
    if (!isset($_SESSION['name'])) {
        header("Location: index.php");
    }
?>

<!DOCTYPE HTML>
<html lang='en'>
    <head>
        <title>CompUI Panel</title>
    </head>
    <body>
        <div class='header'>Karle House</div>
        
        <div class='info'>
          <span id='points'>
            0
            <br>
            Points
          </span>
          <span id='members'>
            0
            <br>
            Members
            </span>
          <span id='attendance'>
            83%
            <br>
            Attendance
            </span>
          <span id='rank'>
            #0
            <br>
            Rank
            </span>
        </div>
        
        <form class='logout-form'>
          <a id='logout' href='logout.php'>Logout <?php echo $_SESSION['name'] . " of " . $_SESSION['house']; ?></a>
        </form>
        
        <style>
          @import url('https://fonts.googleapis.com/css?family=Bungee&display=swap');
          
          html, body {
            display: grid;
            margin: 0;
            padding: 0;
            height: 100%;
            width: 100%;
          }
          
          body {
            grid-template-rows: 18% 6% 30%;
            grid-template-columns: 0; /* Add columns later? */
          }
          
          .header {
            grid-row: 1;
            grid-column: 1 / 4;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: navy;
            height: 100%;
            width: 100%;
            font-family: 'Bungee', regular;
            font-size: 350%;
            color: white;
          }
          
          .info {
            display: grid;
            grid-row: 3;
            grid-column: 1 / 4;
            background-color: #EAEAEA;
            grid-template-columns: 25% 25% 25% 25%;
            font-family: Roboto;
            font-weight: 300;
            font-size: 80%;
          }
        
          span {
            all: unset;
            display: grid;
            text-align: center;
            align-items: center;
            font-size: 350%;
          }
          
          .logout-form {
            grid-row: 4;
            grid-column: 1 / 4;
          }
        </style>
    </body>
</html>