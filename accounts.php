<?php
    session_start();

    include "admin/config.php";
    
    if (!in_array($_SESSION['type'], array('admin','teacher','hoh'), true)) 
      {header("Location: summary.php"); exit();} 
    else 
      {$house_color = 'darkgray';}
      
    $secure = $conn->prepare('SELECT colour, members FROM houses WHERE house = ?');
    $secure->bind_param('s', $_SESSION['house']);
    $secure->execute();
    $secure->store_result();
    $secure->bind_result($house_color, $members);
    $secure->fetch();
    
    if ($_SESSION['type'] == 'admin')
      {$house_color = 'darkgray';}
    
    if ($_SESSION['type'] == 'hoh') 
      {$title = "Head of House";} 
    else 
      {$title = ucfirst($_SESSION['type']);}
?>

<!DOCTYPE HTML>
<html>
  <head>
    <title>Accounts | <?php echo $title;?></title>
    <meta name="viewport" content="width=device-width, initial-scale=0.7">
  </head>
  <body>
    <div class='header'>
      <a href='summary.php' id='home' title='Home'><svg class="bi bi-house-fill" width="3em" height="3em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 3.293l6 6V13.5a1.5 1.5 0 01-1.5 1.5h-9A1.5 1.5 0 012 13.5V9.293l6-6zm5-.793V6l-2-2V2.5a.5.5 0 01.5-.5h1a.5.5 0 01.5.5z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M7.293 1.5a1 1 0 011.414 0l6.647 6.646a.5.5 0 01-.708.708L8 2.207 1.354 8.854a.5.5 0 11-.708-.708L7.293 1.5z" clip-rule="evenodd"/></svg></a>
      <p id='heading'>Accounts</p>
    </div>
    
    <div class='left'>
      <div class='button' id='create-button'><a id='button-content' href='accounts.php?choice=create-button'>Create</a></div>
      <div class='button' id='view-button'><a id='button-content' href='accounts.php?choice=view-button'>View</a></div>
      <div class='button' id='delete-button'><a id='button-content' href='accounts.php?choice=delete-button'>Delete</a></div>
      <div class='line'></div>
      <div class='welcome'><p id='welcome-text'>Welcome <br><?php echo $title; ?></p></div>
    </div>
    
    <div class='frame'>
    
    <?php
    if (isset($_POST['create-submit'])) {
      $autorefresh = header("Refresh: 2; accounts.php?choice=create-button");
      if (empty($_POST['user-field'])) {
        echo "<p id='warning-message'>Please enter Email - <a href='accounts.php?choice=create-button' style='color: blue;'>Retry</a></p>";
        $autorefresh;
      }
      elseif (empty($_POST['password-field']) || empty($_POST['re-password-field'])) {
        echo "<p id='warning-message'>Please enter passwords - <a href='accounts.php?choice=create-button' style='color: blue;'>Retry</a></p>";
        $autorefresh;
      }
      elseif ($_POST['password-field'] !== $_POST['re-password-field']) {
        echo "<p id='warning-message'>Please enter the same passwords - <a href='accounts.php?choice=create-button' style='color: blue;'>Retry</a></p>";
        $autorefresh;
      }
      elseif (strpos($_POST['user-field'], "eq.edu.au") == false) {
        echo "<p id='warning-message'>Please ensure eq.edu.au email - <a href='accounts.php?choice=create-button' style='color: blue;'>Retry</a></p>";
        $autorefresh;
      }
      elseif ((strpos($_POST['user-field'], " ") == true) || (strpos($_POST['password-field'], " ") == true)) {
        echo "<p id='warning-message'>Please ensure no spaces in email or password - <a href='accounts.php?choice=create-button' style='color: blue;'>Retry</a></p>";
        $autorefresh;
      }
      else {
        if ($_SESSION['type'] !== "admin" && $_POST['account'] == 'admin') {
          echo "<p id='warning-message'>Do not attempt to escalate priviledge</p>";
          $autorefresh;
        } elseif ($_SESSION['type'] == "teacher" && $_POST['account'] == 'hoh') {
          echo "<p id='warning-message'>A $title cannot create a Head of House account</p>";
          $autorefresh;
        } else {
          if ($secure = $conn->prepare('INSERT INTO `accounts` (house, type, email, password) VALUES(?, ?, ?, ?);')) {
            $password = password_hash($_POST['password-field'], PASSWORD_BCRYPT);
            $house = strtolower($_POST['house']);
            $account = htmlentities($_POST['account']);
            $email = $_POST['user-field'];
            $secure->bind_param('ssss', $house, $account, $email, $password);
            $secure->execute();
            $secure->close();
            echo "<p id='warning-message'>Complete - <a href='accounts.php?choice=create-button' style='color: blue;'>Return</a></p>";
            $autorefresh;
          }
        }
      }
    }
    
    if (isset($_POST['delete-submit'])) {
      $autorefresh = header("Refresh: 2; accounts.php?choice=delete-button");
      if (empty($_POST['user-field'])) {
        echo "<p id='warning-message'>Please enter Email - <a href='accounts.php?choice=create-button' style='color: blue;'>Retry</a></p>";
        $autorefresh;
      }
      else if (strpos($_POST['user-field'], "eq.edu.au") == false) {
        echo "<p id='warning-message'>Please ensure eq.edu.au email - <a href='accounts.php?choice=create-button' style='color: blue;'>Retry</a></p>";
        $autorefresh;
      } 
      else if (strpos($_POST['user-field'], " ") == true) {
        echo "<p id='warning-message'>Please ensure no spaces in email or password - <a href='accounts.php?choice=create-button' style='color: blue;'>Retry</a></p>";
        $autorefresh;
      }
      else {
        $secure = $conn->prepare('SELECT email, type, house FROM accounts WHERE email = ? && type = ?');
        $secure->bind_param('ss', $_POST['user-field'], $_POST['account']);
        $secure->execute();
        $secure->store_result();
        $secure->bind_result($delemail, $deltype, $delhouse);
        $secure->fetch();
        $secure->close();
        if ($_POST['user-field'] == $delemail) {
          $secure = $conn->prepare('DELETE FROM `accounts` WHERE email = ?');
          $secure->bind_param('s', $delemail);
          $secure->execute();
          $secure->close();
          echo "<p id='warning-message'>Complete - <a href='accounts.php?choice=create-button' style='color: blue;'>Return</a></p>";
          $autorefresh;
        } else {
          echo "<p id='warning-message'>No " . $_POST['account'] . " account found with " . $_POST['user-field'] . " - <a href='accounts.php?choice=create-button' style='color: blue;'>Retry</a></p>";
        }
      }
    }

    if (isset($_GET['choice'])) {
      if ($_GET['choice'] == 'create-button') {
        echo "<form method='POST' class='create-form' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>
        <div>
          <select name='account' style='background-color: transparent; text-align-last: center; width: 20%; height: 100%; margin: auto;'>";
            if ($_SESSION['type'] == "admin") { echo "<option value='admin'>Admin</option><option value='hoh'>Head of House</option><option value='teacher'>Teacher</option>"; }
            elseif ($_SESSION['type'] == "hoh") { echo "<option value='hoh'>Head of House</option><option value='teacher'>Teacher</option>"; }
            elseif ($_SESSION['type'] == "teacher") { echo "<option value='teacher'>Teacher</option>"; }
          echo "</select>
          <select name='house' style='background-color: transparent; text-align-last: center; width: 20%; height: 100%; margin: auto;'>
            <option>Barnes</option>
            <option>Bilin Bilin</option>
            <option>Francis</option>
            <option>Karle</option>
          </select>
        </div>
        <label for='user-field' class='user-label' id='create-label'>Account Email</label>
          <input type='text' class='user-field' name='user-field' id='user-field' placeholder='Email' maxlength='20' method='POST' style='width: 50%; height: 70%; margin: auto;'>
        <label for='password-field' class='password-label' id='create-label'>Password</label>
          <input type='password' class='password-field' name='password-field' id='password-field' placeholder='Password' maxlength='50' method='POST' style='width: 50%; height: 70%; margin: auto;'>
          <input type='password' class='password-field' name='re-password-field' id='password-field' placeholder='Retype Password' maxlength='50' method='POST' style='width: 50%; height: 70%; margin: auto;'>
          <input type='submit' class='submit-button' name='create-submit' id='create-label' value='Create' method='POST'>
        </form>
        </div>";
      }
      if ($_GET['choice'] == 'view-button')
        {$sql = "SELECT email, type, house FROM accounts ORDER BY 'type';";        
        $result = mysqli_query($conn, $sql);
    
        if (mysqli_num_rows($result) > 0) {
          echo "<table class='viewaccounts'>";
          while($row = mysqli_fetch_assoc($result)) {
          // print_r($row); // debugging the array content
          echo "<tr>
            <td id='studentname'>" . $row["type"] . "</a></td>
            <td id='email'>" . htmlentities($row["email"]) . "</td>
          </tr>";
          }
          echo "</table>";
        }
      }
    
      if ($_GET['choice'] == 'delete-button') {
        echo "<form method='POST' class='delete-form' action='" . htmlspecialchars($_SERVER['PHP_SELF']) . "'>
        <div>
          <select name='account' style='background-color: transparent; text-align-last: center; width: 20%; height: 100%; margin: auto;'>";
            if ($_SESSION['type'] == "admin") { echo "<option value='admin'>Admin</option><option value='hoh'>Head of House</option><option value='teacher'>Teacher</option>"; }
            elseif ($_SESSION['type'] == "hoh") { echo "<option value='hoh'>Head of House</option><option value='teacher'>Teacher</option>"; }
            elseif ($_SESSION['type'] == "teacher") { echo "<option value='teacher'>Teacher</option>"; }
        echo "</select>
        </div>
        <label for='user-field' class='user-label' id='create-label'>Account Email</label>
          <input type='text' class='user-field' name='user-field' id='user-field' placeholder='Email' maxlength='20' method='POST' style='width: 50%; height: 70%; margin: auto;'>
          <input type='submit' class='submit-button' name='delete-submit' id='delete-label' value='Delete' method='POST'>
        </form>
        </div>";
      }
    }
    ?>
    
    </div>
    
    <style>
      @import url('https://fonts.googleapis.com/css?family=Bungee&display=swap');
      @import url('https://fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700,800,900&display=swap');
      
      @keyframes poof { from {visibility: visible;} to {visibility: hidden;} }
      
      html, body { display: grid; margin: 0; padding: 0; height: 100%; width: 100%; }
      
      body { grid-template-rows: 18% 80%; grid-template-columns: 25% auto; }
      
      .header { grid-row: 1; grid-column: 1 / 3; background-color: <?php echo $house_color; ?>; height: 100%; width: 100%; font-family: 'Bungee', regular; display: grid; grid-template-columns: 8% auto 8%; }
      
      #home { grid-column: 1; color: white; margin: auto; } /*#home:focus { border: 2px solid black; }*/
      
      #heading { grid-column: 2; font-size: 350%; color: white; margin: auto; }
      
      .left { grid-row: 2; display: grid; grid-template-rows: 3% repeat(3, 18%) 5% auto 20%; }
      
      .button { box-sizing: border-box; width: 65%; height: 45%; border-radius: 20px; background-color: <?php echo $house_color ?>; margin: auto; }
      
      #button-content { all: unset; cursor: pointer; display: flex; height: 100%; justify-content: center; align-items: center; color: white; font-family: Raleway; font-weight: 700; } #button-content:focus { text-decoration: underline; }
      
      #create-button { grid-row: 2; } #view-button { grid-row: 3; } #delete-button { grid-row: 4; } #admin-change-button { grid-row: 7; background-color: gray; height: 50px; margin: auto; margin-top: 0; }
      
      .line { grid-row: 5; background-color: black; height: 1%; width: 80%; margin: auto; margin-bottom: 0; }
      
      .welcome { grid-row: 6; margin: auto; }
      
      .frame { grid-row: 2; grid-column: 2; display: grid; grid-template-columns: 1fr; grid-template-rows: 1fr; height: 90%; width: 95%; margin: auto; margin-left: 0; border: 2px solid black; overflow: scroll; }
      
      #warning-message { grid-column: 1; grid-row: 1; margin: auto; }
      
      #welcome-text { font-size: 150%; font-weight: 200; font-family: Raleway; text-align: center; }
      
      .create-form, .delete-form { grid-column: 1; display: grid; grid-template-rows: 10% 10% repeat(4, 1fr) 20%; text-align: center; text-align-last: center; padding: 5%; }
      
      #create-label, #delete-label { margin: auto; margin-bottom: 0; font-family: Raleway; }
      
      .submit-button { all: unset; background-color: darkgray; width: 20%; height: 60%; margin: auto; margin-top: 10%; border: 2px solid black; color: white; border-radius: 30px; }
      
      .viewaccounts { grid-column: 1; margin: auto; margin-top: 5%; margin-bottom: 5%; }
      
      .selectaccount { grid-column: 1; margin: auto; margin-top: 5%;}
      
      #submitaccount { grid-column: 1; grid-row: 2; }
      
      @media (min-width: 481px) and (max-width: 767px) {
        #heading { font-size: 170%; }
        #welcome-text { font-size: 120%; }
        .button { width: 80%; height: 55%; }
        .left { grid-template-rows: 15% repeat(3, 12%) 5% auto 18%; }
      }
      
      @media (min-width: 767px) and (max-width: 1090px) {
        #heading { font-size: 270%; }
        #welcome-text { font-size: 150%; }
        .button { width: 80%; height: 70% }
        .left { grid-template-rows: 12% repeat(3, 13%) 5% auto 18%; }
      }
    </style>
  </body>
</html>