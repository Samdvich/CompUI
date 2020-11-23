<?php
    include "admin/config.php";
    
    if ($conn->connect_error) # Testing Connection
    {$database_status = '<svg class="bi bi-bar-chart" width="2em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 11H2v3h2v-3zm5-4H7v7h2V7zm5-5h-2v12h2V2zm-2-1a1 1 0 00-1 1v12a1 1 0 001 1h2a1 1 0 001-1V2a1 1 0 00-1-1h-2zM6 7a1 1 0 011-1h2a1 1 0 011 1v7a1 1 0 01-1 1H7a1 1 0 01-1-1V7zm-5 4a1 1 0 011-1h2a1 1 0 011 1v3a1 1 0 01-1 1H2a1 1 0 01-1-1v-3z" clip-rule="evenodd"/></svg>';
    $ffs_variable = "Service Outage";}
    else
    {$database_status = '<svg class="bi bi-bar-chart-fill" width="2em" height="2em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><rect width="4" height="5" x="1" y="10" rx="1"/><rect width="4" height="9" x="6" y="6" rx="1"/><rect width="4" height="14" x="11" y="1" rx="1"/></svg>';}
?>

<!DOCTYPE HTML>
<html lang='en'>
  <head>
    <title>CompUI Login</title>
  </head>

    <?php
      if ($secure = $conn->prepare('SELECT house, type, password FROM accounts WHERE email = ?')) // prevent SQL injection
        {$secure->bind_param('s', $_POST['user-field']); /* s = string, i = int, b = blob */ $secure->execute(); /* run the query */ $secure->store_result();
        if (isset($_POST['user-field']))
          {if ($secure->num_rows > 0) /* if there is a result */
          {$secure->bind_result($house, $type, $password); /* bind to variables */ $secure->fetch();
            if (password_verify($_POST['password-field'], $password))
                {session_start(); $_SESSION['loggedin'] = TRUE; $_SESSION['name'] = $_POST['user-field']; $_SESSION['house'] = $house; $_SESSION['type'] = $type; $_SESSION['email'] = $_POST['user-field']; header('Location: index.php'); exit();}
            else {$ffs_variable = 'Incorrect Password';}}
          else {$ffs_variable = 'Unrecognised Email';}}
        else {$ffs_variable = 'Press Enter to Login';}}
    ?>
    
    <div class='database_status'><?php echo $database_status; ?></div>
    <div class='ffs_variable'><?php echo $ffs_variable; ?></div>
        
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
      
      @keyframes fade { from {opacity: 100%;} to {opacity: 0;} }
            
      html { display: grid; height: 100%; width: 100%; background-color: #B9D07F; }
            
      body { display: grid; grid-template-columns: 30% 40% 30%; grid-template-rows: auto 50% 20%; margin: 0; padding: 0; }
            
      input { background-color: transparent; outline: none; border: none; font-size: 140%; font-weight: 700; padding: 0; }
            
      .login-form { display: grid; grid-column: 2; grid-row: 2; grid-template-rows: 40% 10% 40%; grid-template-columns: auto; }
            
      .login-submit { display: none; }
      
      .user-label, .password-label { display: grid; grid-column: 1; margin: auto; margin-top: 0; font-family: 'Bungee'; color: white; font-size: 350%;}
            
      .user-label { grid-row: 1; }
            
      .password-label { grid-row: 3; }
            
      .user-field:focus+label, .password-field:focus+label { animation-name: boing; animation-duration: 2s; animation-iteration-count: infinite;}
                
      .user-field, .password-field { display: grid; grid-column: 1; height: 35%; width: 100%; margin: auto; margin-bottom: 0; background-color: transparent; border-radius: 20px; background-color: darkgray; cursor: text; opacity: 50%; padding: 0; padding-left: 5%; padding-right: 5%; }
            
      .user-field {grid-row: 1; width: 70%;}
            
      .password-field {grid-row: 3; width: 90%;}
      
      .database_status { grid-row: 1; grid-column: 1; margin: 0; padding: 3%; color: gray; }
      
      .ffs_variable { grid-row: 3; grid-column: 2; margin: auto; margin-top: 0; font-family: Roboto; font-weight: 600; color: gray; font-size: 120% ; animation-name: fade; animation-delay: 10s ; animation-duration: 5s; animation-iteration-count: 1; animation-fill-mode: forwards; }
    </style>
  </body>
</html>