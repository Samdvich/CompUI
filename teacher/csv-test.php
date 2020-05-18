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
    
  if (!isset($_SESSION['name'])) // Authenticate
    {header("Location: index.php");}

  if (isset($_POST['house-change']))
    {unset($_SESSION['house']); /* Instead of resetting the entire session, only reset important variables */ $sql = "UPDATE accounts SET house='" . $_POST['house-change'] . "' WHERE userID=" . $_SESSION['id'] . ""; $_SESSION['house'] = $_POST['house-change'];
    
  if ($conn->query($sql) === TRUE)
    {echo "<p class='response'>Record updated successfully</p>";}
  else
    {echo "<p class='response'> Error updating record:</p>" . $conn->error;}}
    
  $secure = $conn->prepare('SELECT colour, members FROM houses WHERE house = ?');
  $secure->bind_param('s', $_SESSION['house']);
  $secure->execute();
  $secure->store_result();
  $secure->bind_result($house_color, $members);
  $secure->fetch();
        
  if ($_SESSION['type'] == "temp")
    {$sql = "UPDATE accounts SET house='temporary' WHERE userID=". $_SESSION['id'] .""; $conn->query($sql);}
  
  if ($result = $conn->query("SELECT SUM(`". $_SESSION['house'] ."`) AS total FROM competitions"))
    while ($row = $result->fetch_assoc())
      {$points = $row['total'];}
    else
      {$points = 0;}
      
  if ($result = $conn->query("SELECT COUNT(`". $_SESSION['house'] ."`) AS count FROM competitions WHERE (`". $_SESSION['house'] ."`) >0"))
    {while ($row = $result->fetch_assoc())
      {$events = $row['count'];}}
  else
  {$events = 0;}
      
  if ($result = $conn->query("SELECT COUNT('house') AS members FROM accounts WHERE house = '". $_SESSION['house'] ."';"))
    {while ($row = $result->fetch_assoc())
      {$members = $row['members'];}}
      
  if ($_SESSION['house'] == 'temporary')
    {$house_color = 'lightgray'; $points = "N/A"; $events = "N/A";}
    
  /* File (CSV) Upload Form */
  if (isset($_POST['submit'])) {
    echo "uh, hi?";
  } else {
    echo "plz upload dood";
  }
?>

<!DOCTYPE HTML>
<html lang='en'>
  <head>
    <title>CompUI Panel</title>
  </head>
  <body>
    <div class='header'>
    <div class='header'>
      <a href='summary.php' id='home'><svg class="bi bi-house-fill" width="3em" height="3em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 3.293l6 6V13.5a1.5 1.5 0 01-1.5 1.5h-9A1.5 1.5 0 012 13.5V9.293l6-6zm5-.793V6l-2-2V2.5a.5.5 0 01.5-.5h1a.5.5 0 01.5.5z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M7.293 1.5a1 1 0 011.414 0l6.647 6.646a.5.5 0 01-.708.708L8 2.207 1.354 8.854a.5.5 0 11-.708-.708L7.293 1.5z" clip-rule="evenodd"/></svg></a>
      <p id='heading'><?php echo $_SESSION['house']; ?></p>
    </div>
    
    <form method='POST' enctype='multipart/form-data'>
      <input type='file' name='CSVfile' id='CSVfile'>
      <input type='submit' value='Upload' name='submit'>
    </form>
        
    <style>
      @import url('https://fonts.googleapis.com/css?family=Bungee&display=swap');
          
      html, body { display: grid; margin: 0; padding: 0; height: 100%; width: 100%; }
          
      body { grid-template-rows: 18% 6% 30% 30% 6%; }
          
      .header { grid-row: 1; grid-column: 1 / 4; background-color: <?php echo $house_color; ?>; height: 100%; width: 100%; font-family: 'Bungee', regular; display: grid; grid-template-columns: 8% auto 8%; }
      
      #home { grid-column: 1; color: white; margin: auto; }
      
      #heading { grid-column: 2; font-size: 350%; color: white; margin: auto; }
      
      #CSVfile { all: unset; }
    </style>
  </body>
</html>