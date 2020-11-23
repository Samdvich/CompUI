<?php
  session_start();

  include "admin/config.php";
    
  if (!isset($_SESSION['name'])) // Authenticate
    {header("Location: index.php"); exit;}
    
    $quick_change = "<form class='house-form' method='POST'>
    <input type='submit' name='house-change' value='bilin bilin'>
    <input type='submit' name='house-change' value='barnes'>
    <input type='submit' name='house-change' value='francis'>
    <input type='submit' name='house-change' value='karle'>";
            
    if ($_SESSION['type'] == "temp")
        {echo $quick_change . "<input type='submit' name='house-change' value='temporary'> </form>";}
      elseif ($_SESSION['type'] == "admin")
        {echo "</form>
        <nav role='navigation'>
        <ul class='navmenu'>
        <li><a href='admin/competitions.php'>Events</a></li>
        <li><a href='teacher/csv-upload.php'>Students CSV</a></li>
        <li><a href='accounts.php'>Accounts</a></li>
        </ul>
        </nav>";}
      elseif ($_SESSION['type'] == "teacher" || "hoh")
        {echo "</form>
        <nav role='navigation'>
        <ul class='navmenu'>
        <li><a href='admin/competitions.php'>Events</a></li>
        <li></li>
        <li><a href='accounts.php'>Accounts</a></li>
        </ul>
        </nav>";}
      elseif ($_SESSION['type'] == "student")
        {echo "</form>";}
    else
      {session_unset(); session_destroy(); header("Location: login.php"); exit;}

  if (isset($_POST['house-change']))
    {unset($_SESSION['house']); /* Instead of resetting the entire session, only reset important variables */ $sql = "UPDATE accounts SET house='" . $_POST['house-change'] . "' WHERE email='" . $_SESSION['email'] . "'"; $_SESSION['house'] = $_POST['house-change'];
    
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
    {$sql = "UPDATE accounts SET house='temporary' WHERE email=". $_SESSION['email'] .""; $conn->query($sql);}
  
  if ($result = $conn->query("SELECT SUM(`". $_SESSION['house'] ."`) AS total FROM competitions"))
    while ($row = $result->fetch_assoc())
      {$points = $row['total'];}
    else
      {$points = 0;}
  
  if ($_SESSION['type'] == "admin")
    {$result = $conn->query("SELECT SUM(`bilin Bilin`) AS bbpoints, SUM(`Barnes`) AS bpoints, SUM(`Francis`) AS fpoints, SUM(`Karle`) AS kpoints
FROM `competitions`;");
    $housepoints = $result->fetch_assoc();}
  
  $events = 0;
  if ($result = $conn->query("SELECT COUNT(`". $_SESSION['house'] ."`) AS count FROM competitions WHERE (`". $_SESSION['house'] ."`) >0"))
    {while ($row = $result->fetch_assoc())
      {$events = $row['count'];}}
  if ($events == 0)
    {$events = "N/A";}
      
  if ($result = $conn->query("SELECT COUNT('house') AS members FROM accounts WHERE house = '". $_SESSION['house'] ."';"))
    {while ($row = $result->fetch_assoc())
      {$members = $row['members'];}}
  else
  {$points = 0;}
      
  if (!in_array($_SESSION['house'], array('bilin bilin', 'barnes', 'francis', 'karle'), true))
    {$house_color = 'darkgray'; $points = "N/A"; $events = "N/A";}
?>

<!DOCTYPE html>
<html lang='en'>
  <head>
    <title>CompUI Panel</title>
    <meta name="viewport" content="width=device-width, initial-scale=0.4">
  </head>
  <body>
    <div class='header'>
      <a href='logout.php' id='logout' title='Logout'><svg class="bi bi-box-arrow-in-left" width="3em" height="3em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M7.854 11.354a.5.5 0 000-.708L5.207 8l2.647-2.646a.5.5 0 10-.708-.708l-3 3a.5.5 0 000 .708l3 3a.5.5 0 00.708 0z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M15 8a.5.5 0 00-.5-.5h-9a.5.5 0 000 1h9A.5.5 0 0015 8z" clip-rule="evenodd"/><path fill-rule="evenodd" d="M2.5 14.5A1.5 1.5 0 011 13V3a1.5 1.5 0 011.5-1.5h8A1.5 1.5 0 0112 3v1.5a.5.5 0 01-1 0V3a.5.5 0 00-.5-.5h-8A.5.5 0 002 3v10a.5.5 0 00.5.5h8a.5.5 0 00.5-.5v-1.5a.5.5 0 011 0V13a1.5 1.5 0 01-1.5 1.5h-8z" clip-rule="evenodd"/></svg></a>
      <p id='heading'><?php echo $_SESSION['house']; ?></p>
    </div>
    
    <main class='info'>
    <?php
    if ($_SESSION['type'] !== "admin") {
      echo "<span id='infoone'>"; if (!empty($points)) { echo $points; } else { echo "N/A"; } echo "<br>Points</span>";
      echo "<span id='infotwo'>"; echo $members; echo "<br>Members</span>";
      echo "<span id='infothree'>N/A<br>Attendance</span>";
      echo "<span id='infofour'>"; echo $events; echo "<br>Events</span>";
    } else {
      echo "<span id='infoone'>"; if ($housepoints['bbpoints'] == 0) { echo "N/A"; } else { echo $housepoints['bbpoints']; } echo "<br>Bilin Bilin Points</span>";
      echo "<span id='infotwo'>"; if ($housepoints['bpoints'] == 0) { echo "N/A"; } else { echo $housepoints['bpoints']; } echo "<br>Barnes Points</span>";
      echo "<span id='infothree'>"; if ($housepoints['fpoints'] == 0) { echo "N/A"; } else { echo $housepoints['fpoints']; } echo "<br>Francis Points</span>";
      echo "<span id='infofour'>"; if ($housepoints['kpoints'] == 0) { echo "N/A"; } else { echo $housepoints['kpoints']; } echo "<br>Karle Points</span>";
    }
    ?>
    </main>
        
    <style>
      @import url('https://fonts.googleapis.com/css?family=Bungee&display=swap');
      @import url('https://fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700,800,900&display=swap');

      html, body { display: grid; margin: 0; padding: 0; height: 100%; width: 100%; }
          
      body { grid-template-rows: 18% 6% 30% 30% 6%; }
          
      .header { grid-row: 1; grid-column: 1 / 4; background-color: <?php echo $house_color; ?>; height: 100%; width: 100%; font-family: 'Bungee', regular; display: grid; grid-template-columns: 8% auto 8%; }
      
      #logout { grid-column: 1; color: white; margin: auto; }
      
      #heading { grid-column: 2; font-size: 350%; color: white; margin: auto; }
          
      .info { display: grid; grid-row: 3; grid-column: 1 / 4; background-color: #EAEAEA; grid-template-columns: repeat(4, 1fr); font-family: 'Raleway'; font-weight: 300; font-size: 80%; }
        
      span { all: unset; display: grid; text-align: center; align-items: center; font-size: 350%; }
          
      .house-form { grid-row: 5; grid-column: 1 / 4; text-align: center; }
          
      .response { grid-row: 5; grid-column: 1 / 4; padding-top: 20px; text-align: center; }
      
      nav[role='navigation'] { grid-column: 1 / 4; grid-row: 4; margin: auto; width: 100%; }
      
      .navmenu { all: unset; display: grid; margin: auto; text-align: center; grid-template-columns: 1fr 1fr 1fr; width: 100%; height: 100%; list-style-type: none; }
          
      .navmenu a { all: unset; font-family: 'Bungee', regular; font-size: 200%; cursor: pointer; color: gray; padding: 10%; border: 2px solid black; border-radius: 50px; }
          
      .navmenu a:hover { color: <?php echo $house_color; ?>;} .navmenu a:focus { text-decoration: underline; }
    </style>
  </body>
</html>