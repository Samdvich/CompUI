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
    
    if ($_SESSION['type'] !== "admin")
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
    <meta name="viewport" content="width=500, initial-scale=1">
  </head>
  <body>
    <div class='header'>
      <a href='../summary.php' id='home'><svg class="bi bi-house-fill" width="3em" height="3em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M8 3.293l6 6V13.5a1.5 1.5 0 01-1.5 1.5h-9A1.5 1.5 0 012 13.5V9.293l6-6zm5-.793V6l-2-2V2.5a.5.5 0 01.5-.5h1a.5.5 0 01.5.5z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M7.293 1.5a1 1 0 011.414 0l6.647 6.646a.5.5 0 01-.708.708L8 2.207 1.354 8.854a.5.5 0 11-.708-.708L7.293 1.5z" clip-rule="evenodd"/></svg></a>
      <p id='heading'><?php echo $_SESSION['house']; ?></p>
      <div class='import_csv'>
        <p id='csv_text'>Upload CSV</p>
        <a id='csv_icon' href='upload'>
          <svg class="bi bi-file-arrow-up" width="1.3em" height="1.3em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4 1h8a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V3a2 2 0 012-2zm0 1a1 1 0 00-1 1v10a1 1 0 001 1h8a1 1 0 001-1V3a1 1 0 00-1-1H4z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M4.646 7.854a.5.5 0 00.708 0L8 5.207l2.646 2.647a.5.5 0 00.708-.708l-3-3a.5.5 0 00-.708 0l-3 3a.5.5 0 000 .708z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M8 12a.5.5 0 00.5-.5v-6a.5.5 0 00-1 0v6a.5.5 0 00.5.5z" clip-rule="evenodd"/></svg>
        </a>
      </div>
    </div>
    
    <div class='left'>
      <div class='button' id='create-button'><a id='button-content' href='accounts.php?choice=create-button'>Create</a></div>
      <div class='button' id='view-button'><a id='button-content' href='accounts.php?choice=view-button'>View</a></div>
      <div class='button' id='delete-button'><a id='button-content' href='accounts.php?choice=delete-button'>Delete</a></div>
      <div class='line'></div>
      <div class='welcome'><p>Welcome <br><?php echo $_SESSION['type']; ?></p></div>
      <div class='button' id='admin-change-button'><a id='button-content' href='accounts.php?choice=admin-change-button'>Change</a></div>
    </div>
    
    <div class='frame'></div>
    
    <?php
    if (isset($_GET['choice']))
    {if ($_GET['choice'] == 'create-button')
    {echo
    "<div class='frame'>
    <p id='warning-message'>You selected create! ^-^</p>
    </div>
    
    <style>
      .frame { grid-row: 2; grid-column: 2; height: 90%; width: 90%; border: 5px solid black; border-radius: 80px; margin: auto; }
    
      #warning-message { display: flex; height: 100%; justify-content: center; align-items: center; font-family: Raleway; font-weight: 700; }
    </style>";}
    if ($_GET['choice'] == 'view-button')
    {echo
    "<div class='frame'>
    <p id='warning-message'>You selected view! ^-^</p>
    </div>
    
    <style>
      .frame { grid-row: 2; grid-column: 2; height: 90%; width: 90%; border: 5px solid black; border-radius: 80px; margin: auto; }
    
      #warning-message { display: flex; height: 100%; justify-content: center; align-items: center; font-family: Raleway; font-weight: 700; }
    </style>";}
    if ($_GET['choice'] == 'delete-button')
    {echo
    "<div class='frame'>
    <p id='warning-message'>You selected delete! ^-^</p>
    </div>
    
    <style>
      .frame { grid-row: 2; grid-column: 2; height: 90%; width: 90%; border: 5px solid black; border-radius: 80px; margin: auto; }
    
      #warning-message { display: flex; height: 100%; justify-content: center; align-items: center; font-family: Raleway; font-weight: 700; }
    </style>";}
    if ($_GET['choice'] == 'admin-change-button')
    {echo
    "<div class='frame'>
    <p id='warning-message'>You selected change ~ for ". $_SESSION['type'] ."s! ^-^</p>
    </div>
    
    <style>
      .frame { grid-row: 2; grid-column: 2; height: 90%; width: 90%; border: 5px solid black; border-radius: 80px; margin: auto; }
    
      #warning-message { display: flex; height: 100%; justify-content: center; align-items: center; font-family: Raleway; font-weight: 700; }
    </style>";}}
    ?>
    
    <style>
      @import url('https://fonts.googleapis.com/css?family=Bungee&display=swap');
      @import url('https://fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700,800,900&display=swap');
      
      @keyframes poof { from {visibility: visible;} to {visibility: hidden;} }
      
      html, body { display: grid; margin: 0; padding: 0; height: 100%; width: 100%; }
      
      body { grid-template-rows: 18% 80%; grid-template-columns: 20% auto; }
      
      .header { grid-row: 1; grid-column: 1 / 3; background-color: <?php echo $house_color; ?>; height: 100%; width: 100%; font-family: 'Bungee', regular; display: grid; grid-template-columns: 30% auto 30%; }
      
      #home { grid-column: 1; color: white; margin: auto; margin-left: 3%; }
      
      #heading { grid-column: 2; font-size: 350%; color: white; margin: auto; line-height: 0; }
      
      .import_csv { grid-row: 1; grid-column: 3; height: 50px; width: 35%; background-color: white; border-radius: 20px; margin: auto; margin-right: 10%; display: grid; grid-template-columns: 75% 25%; }
      
      #csv_text { grid-column: 1; margin: auto; font-family: Raleway; padding: 10%; padding-right: 1%; }
      
      #csv_icon { grid-column: 2; padding: 10%; margin: auto; padding-top: 15%; }
      
      .left { grid-row: 2; display: grid; grid-template-rows: 3% repeat(3, 18%) 5% auto 18%; }
      
      .button { box-sizing: border-box; width: 65%; height: 45%; border-radius: 20px; background-color: <?php echo $house_color ?>; margin: auto; }
      
      #button-content { display: flex; height: 100%; justify-content: center; align-items: center; color: white; font-family: Raleway; font-weight: 700; }
      
      #create-button { grid-row: 2; } #view-button { grid-row: 3; } #delete-button { grid-row: 4; } #admin-change-button { grid-row: 7; margin-top: 0; }
      
      .line { grid-row: 5; background-color: black; height: 1%; width: 80%; margin: auto; margin-bottom: 0; }
      
      .welcome { grid-row: 6; font-size: 150%; font-weight: 200; font-family: Raleway; text-align: center; margin: auto; margin-bottom: 0;}
      
      a { all: unset; cursor: pointer; }
      
      @media (min-width: 481px) and (max-width: 767px) {
        #csv_text { display: none; }
        .import_csv { grid-template-columns: 0; }
        #heading { font-size: 170%; }
        .welcome { font-size: 150%; margin-top: 5%; }
        .button { width: 80%; height: 55%; }
        .left { grid-template-rows: 15% repeat(3, 12%) 5% 18%; }
        #button-content { font-size: 110%; }
      }
      
      @media (min-width: 767px) and (max-width: 1090px) {
        #csv_text { display: none; }
        .import_csv { grid-template-columns: 0; }
        #heading { font-size: 270%; }
        .welcome { font-size: 170%; margin-top: 5%; }
        .button { width: 80%; height: 70% }
        .left { grid-template-rows: 12% repeat(3, 13%) 5% 18%; }
        #button-content { font-size: 140%; }
      }
    </style>
  </body>
</html>