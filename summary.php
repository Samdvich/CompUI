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

        if (isset($_POST['house-change'])) {
            unset($_SESSION['house']); // Istead of resetting the entire session, only reset important variables
            $sql = "UPDATE accounts SET house='" . $_POST['house-change'] . "' WHERE userID=" . $_SESSION['id'] . "";
            $_SESSION['house'] = $_POST['house-change']; // Redeclare the unset variables
            
            if ($conn->query($sql) === TRUE) {
                echo "<p class='response'>Record updated successfully</p>";
            } else {
            echo "<p class='response'> Error updating record:</p>" . $conn->error;
            }
        }
                
        if ($_SESSION['house'] == "bilin bilin") {
            $house_color = "darkgreen";
        }
        elseif ($_SESSION['house'] == "barnes") {
            $house_color = "red";
        }
        elseif ($_SESSION['house'] == "francis") {
            $house_color = "orange";
        }
        elseif ($_SESSION['house'] == "karle") {
            $house_color = "navy";
        }
?>

<!DOCTYPE HTML>
<html lang='en'>
    <head>
        <title>CompUI Panel</title>
    </head>
    <body>
        <div class='header'><?php echo $_SESSION['house'] ?></div>
        
        <div class='info'>
          <span id='points'>
              0
              <br>
              Points
          </span>
          <span id='wins'>
              0
              <br>
              Wins
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
          <a id='logout' href='logout.php'><?php echo "Logout " . $_SESSION['type'] . " " . $_SESSION['name'] . " of " . $_SESSION['house'] . "." ?></a>
        </form>
        
        <?php if ($_SESSION['type'] == "admin") {
            echo "<form class='house-form' method='POST'>
            <input type='submit' name='house-change' value='bilin bilin'>
            <input type='submit' name='house-change' value='barnes'>
            <input type='submit' name='house-change' value='francis'>
            <input type='submit' name='house-change' value='karle'>
            </form>";
        }
        ?>
        
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
            background-color: <?php echo $house_color; ?>;
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
          
          .house-form {
            grid-row: 5;
            grid-column: 1 / 4;
            text-align: center;
          }
          
          .response {
            grid-row: 5;
            grid-column: 1 / 4;
            padding-top: 20px;
            text-align: center;
          }
        </style>
    </body>
</html>