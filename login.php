<?php
  $servername = "localhost";
  $username = "spage65";
  $password = "Password1";
  $DB_Name = "spage65_CompUI"; # Using 2020 PHP Default BCRYPT Hash = password_hash($password, PASSWORD_BCRYPT)
  
  unset($ffs_variable);
  
  $conn = new mysqli($servername, $username, $password, $DB_Name); # Connection
     
  if ($conn->connect_error) # Testing Connection
    {die("Not Connected: <br>" . $conn->connect_error);}
  else
    {$database_variable = "Connected";}
?>

<!DOCTYPE HTML>
<html lang='en'>
  <head>
    <title>CompUI Login</title>
  </head>

    <?php
      $ffs_variable = "";
      
      if ($secure = $conn->prepare('SELECT userID, house, type, password FROM accounts WHERE email = ?')) // prevent SQL injection
        {$secure->bind_param('s', $_POST['user-field']); /* s = string, i = int, b = blob */ $secure->execute(); /* run the query */ $secure->store_result();
        if ($secure->num_rows > 0) /* if there is a result */
          {$secure->bind_result($id, $house, $type, $password); /* bind to variables */ $secure->fetch();
          if (password_verify($_POST['password-field'], $password))
            {session_start(); $_SESSION['loggedin'] = TRUE; $_SESSION['name'] = $_POST['user-field']; $_SESSION['id'] = $id; $_SESSION['house'] = $house; $_SESSION['type'] = $type; header('Location: index.php');}
          else
            {$ffs_variable = 'Error: Incorrect password';}}
        else
          {$ffs_variable = 'Error: Unknown email';}}
      else
        {$ffs_variable = 'Error: Service outage';}
    ?>
        
    <p class='error-info'>
      Database Status: <?php echo $database_variable; ?><br><?php echo $ffs_variable; ?>
    </p>
        
    <form method='POST' class='login-form' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>'>
      <input type='text' class='user-field' name='user-field' id='user-field' placeholder="Email" maxlength='20' method='POST'>
        <label for='user-field' class='user-label'>User</label>
      <input type='password' class='password-field' name='password-field' id='password-field' placeholder="Password" maxlength='50' method='POST'>
        <label for='password-field' class='password-label'>Password</label>
      <input type='submit' class='login-submit' name='login-submit' method='POST'>
    </form>
        
    <style>
      @import url('https://fonts.googleapis.com/css?family=Bungee&display=swap');
            
      @keyframes boing { from {margin-top: 0;} 50% {margin-top: 2%;} to {margin-top: 0;} }
            
      html { display: grid; height: 100%; width: 100%; background-color: #B9D07F; }
            
      body { display: grid; grid-template-columns: 30% 40% 30%; grid-template-rows: auto 50% 20%; margin: 0; padding: 0; }
            
      input { background-color: transparent; outline: none; border: none; font-size: 140%; font-weight: 700; padding: 0; }
            
      .login-form { display: grid; grid-column: 2; grid-row: 2; grid-template-rows: 40% 10% 40%; grid-template-columns: auto; }
            
      .login-submit { display: none; }
            
      .error-info { display: grid; grid-column: 1; grid-row: 1; margin: 0; }
            
      .user-label, .password-label { display: grid; grid-column: 1; margin: auto; margin-top: 0; font-family: 'Bungee'; color: white; font-size: 350%;}
            
      .user-label { grid-row: 1; }
            
      .password-label { grid-row: 3; }
            
      .user-field:focus+label, .password-field:focus+label { animation-name: boing; animation-duration: 2s; animation-iteration-count: infinite;}
                
      .user-field, .password-field { display: grid; grid-column: 1; height: 35%; width: 100%; margin: auto; margin-bottom: 0; background-color: transparent; border-radius: 20px; background-color: darkgray; cursor: text; opacity: 50%; padding: 0; padding-left: 5%; padding-right: 5%; }
            
      .user-field {grid-row: 1; width: 70%;}
            
      .password-field {grid-row: 3; width: 90%;}
    </style>
  </body>
</html>