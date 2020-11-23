<?php
    session_start();

    include "../admin/config.php";
    
    if (!in_array($_SESSION['type'], array("admin", "teacher", "hoh"), true)) // Authenticate user
      {header("Location: ../index.php"); exit();}
    elseif (!isset($_GET['name'])) {header("Location: ../admin/competitions.php");}
    
    $secure = $conn->prepare('SELECT colour, members FROM houses WHERE house = ?');
    $secure->bind_param('s', $_SESSION['house']);
    $secure->execute();
    $secure->store_result();
    $secure->bind_result($house_color, $members);
    $secure->fetch();
    $secure->close();
    
    if (!in_array($_SESSION['house'], array('bilin bilin', 'barnes', 'francis', 'karle'), true))
      {$house_color = 'darkgray';}
      
    $eventname = $_GET['name'];
    $_SESSION['eventname'] = $eventname;
    
    $secure = $conn->prepare('SELECT eventID, visible FROM competitions WHERE event_name = ?');
    $secure->bind_param('s', $eventname);
    $secure->execute();
    $secure->store_result();
    $secure->bind_result($eventID, $visible);
    $secure->fetch();
    $secure->close();
?>

<!DOCTYPE HTML>
<html>
  <head>
    <title><?php echo $eventname; ?></title> <!-- "Using urldecode() on an element in $_GET could have unexpected and dangerous results" -->
  </head>
  <body>
    <div class='header'>
      <a href='../admin/competitions.php' id='home'><svg class="bi bi-caret-left" width="3em" height="3em" viewBox="0 0 16 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 12.796L4.519 8 10 3.204v9.592zm-.659.753l-5.48-4.796a1 1 0 010-1.506l5.48-4.796A1 1 0 0111 3.204v9.592a1 1 0 01-1.659.753z" clip-rule="evenodd"/></svg></a>
      <p id='heading'><?php echo $_GET['name']; ?></p>
    </div>
    
    <div class='left'>
    <?php
    $sql = "SELECT * FROM competition_results WHERE eventID = " . $eventID . " ORDER BY time ASC"; // WHERE eventID = (SELECT eventID FROM competitions WHERE event_name = " . $eventname . ");";
    $result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
      $count = 0;
      echo "
      <div class='overflow-table'>
      <table class='student-table'>
      <form method='GET'>";
      while($row = mysqli_fetch_assoc($result)) {
        // print_r($row); // debugging the array content 
        $eventID = $row["eventID"];
        $secure = $conn->prepare("SELECT `eventID`, `student_name`, `house`, `time` FROM competition_results WHERE eventID = (SELECT eventID FROM competitions WHERE event_name = ?)");
        $secure->bind_param('s', $eventname); 
        $secure->execute(); 
        $secure->store_result(); 
        $secure->bind_result($eventID, $student_name, $house, $time); 
        $secure->fetch();
        $count++;
        echo "<tr><td id='studentname'>" . $count . " " . "<input id='table_sname' name='student' value='". $row["student_name"] ."'  ";
         if ((mysqli_num_rows($result) == 0) || $visible == "FALSE") { echo "type='button'>"; } else { echo "formaction='../head-of-house/delete.php?student=". urlencode($row['student_name']) ."' type='submit'>"; } 
        echo "</td>";
        echo "<td id='house'>" . $row["house"] . "</td>
          <td id='time'>" . $row["time"] . "</td>
          </tr>";
        if (isset($_POST['finalise'])) {
          if ($count == 1) {
            $points = 8;
          }
          elseif ($count == 2) {
            $points = 6;
          }
          elseif ($count == 3) {
            $points = 4; 
          }
          elseif ($count == 4) {
            $points = 2;
          }
          elseif ($count > 4) {
            $points = 1;
          }
          $sql = 'UPDATE competitions SET `'.$row['house'].'` = `'.$row['house'].'` + '.$points.', `visible` = "FALSE" WHERE eventID = '.$eventID.';'; // WHERE eventID = (SELECT eventID FROM competitions WHERE event_name = " . $eventname . ");";
          mysqli_query($conn, $sql);
          echo "<meta http-equiv='refresh' content='1;../admin/competitions.php'>";
        }
      } 
      echo "</form></table></div>
      <form method='POST' class='finaliseform'>
      <input id='finalise' name='finalise' value='Finalise'"; 
      if ((mysqli_num_rows($result) == 0) || $visible == "FALSE") { echo "type='button'>"; } else { echo "type='submit'>"; }
      echo "</form>";
    }
    ?>
    </div>
    
    <div class='vl'></div>
    <form class='event_details' method='POST' action='<?php echo "../head-of-house/event.php?name=" . urlencode($_GET['name']) ?>'>
     <label for='studentname' id='snamelabel'>Student Name</label>
     <input type='text' name='studentname' id='sname' autocapitalize='word' required>
     <label for='studenthouse' id='hnamelabel'>House</label>
     <select name='studenthouse' id='shouse'>
       <option value='bilin bilin'>Bilin Bilin</option>
       <option value='francis'>Francis</option>
       <option value='barnes'>Barnes</option>
       <option value='karle'>Karle</option>
     </select>
     <label for='studenttime' id='stimelabel'>Time (M:S)</label>
     <input type='time' name='studenttime' id='stime' required>
     <input <?php if ($visible == "FALSE") { echo "type='button'"; } else { echo "type='submit'"; } ?> id='submitupdate' name='submitupdate' value='Update'>
    </form>
    
    <?php
      $issue = "no issues";

      if (isset($_POST['submitupdate']))
        {$secure = $conn->prepare('INSERT INTO competition_results(eventID, student_name, house, time) VALUES((SELECT eventID FROM competitions WHERE event_name = "' . $_GET['name'] . '"), ?, ?, ?)');
        $secure->bind_param('sss', htmlentities($_POST['studentname']), $_POST['studenthouse'], $_POST['studenttime']); $secure->execute(); $secure->close(); echo "<meta http-equiv='refresh' content='1;'>"; /* Refresh to show live update */ }
    ?>
    
    <style>
      @import url('https://fonts.googleapis.com/css?family=Bungee&display=swap');
      @import url('https://fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700,800,900&display=swap');
      
      @keyframes poof { from {visibility: visible;} to {visibility: hidden;} }
      
      html, body { display: grid; margin: 0; padding: 0; height: 100%; width: 100%; }
      
      body { grid-template-rows: 18% 80%; grid-template-columns: 70% 30%; }
      
      .header { grid-row: 1; grid-column: 1 / 3; background-color: <?php echo $house_color; ?>; height: 100%; width: 100%; font-family: 'Bungee', regular; display: grid; grid-template-columns: 8% auto 8%; }
      
      #home { grid-column: 1; color: white; margin: auto; }
      
      #heading { grid-column: 2; font-size: 350%; color: white; margin: auto; }
      
      .left { display: grid; grid-template-rows: 80% 20%; }
      
      .overflow-table { grid-row: 1; grid-column: 1; margin-top: 7%; overflow: scroll; height: 78%; white-space: nowrap; padding-left: 5%; padding-right: 5%; display: grid; grid-template-rows: 85% 10% 5%; }
      
      #table_sname { all: unset; <?php if ((mysqli_num_rows($result) == 0) || $visible == "FALSE") { echo "cursor: default;"; } else { echo "cursor: url('../icons/del.png'), not-allowed;"; } ?> }
      
      .student-table { font-family: 'Raleway', regular; font-size: 200%; }
      
      #house, #time { width: 10%; } #studentname { width: 80%; }
      
      .vl { grid-row: 2; grid-column: 2; border-left: 2px solid black; height: 80%; margin: auto; margin-left: 0; }
      
      .event_details { grid-row: 2; grid-column: 2; padding-left: 10%; padding-right: 10%; padding-top: 20%; padding-bottom: 15%; display: grid; grid-template-rows: 1fr 1fr 1fr 13%; grid-template-columns: 1; }
      
      #sname, #shouse, #stime { height: 40%; margin-top: 30px; border: 2px solid black; border-radius: 10px; padding-left: 2%; padding-right: 0; } #sname { padding-right: 0; width: 80%; }
      
      #shouse { width: 100px; } #stime { width: 80px; }
      
      #snamelabel, #hnamelabel, #stimelabel { margin-top: 0; font-family: 'Raleway', regular; }
      
      #house {width: 30%; }
      
      #snamelabel { grid-row: 1; grid-column: 1; } #sname { grid-row: 1; grid-column: 1; }
      
      #hnamelabel { grid-row: 2; grid-column: 1; } #shouse { grid-row: 2; grid-column: 1; }
      
      #stimelabel { grid-row: 3; grid-column: 1; } #stime { grid-row: 3; grid-column: 1; }
      
      #submitupdate { grid-row: 4; grid-column: 1; border: 2px solid black; border-radius: 20px; width: 100px; height: 50px; margin: auto; font-family: 'Raleway', regular; } #submitupdate[type='submit']:hover { font-weight: 500; cursor: pointer; background-color: #D7D7D7; }
      
      #submitupdate[type='button'] { background-color: lightgray; border-color: darkgray; color: darkgray; cursor: not-allowed; }
      
      .finaliseform { grid-row: 2; display: grid; }
      
      #finalise { border: 2px solid black; border-radius: 20px; width: 100px; height: 50px; margin: auto; font-family: 'Raleway', regular; } #finalise[type='submit']:hover { font-weight: 500; cursor: pointer; background-color: #D7D7D7; }
      
      #finalise[type='button'] { background-color: lightgray; border-color: darkgray; color: darkgray; cursor: not-allowed; }
      
      <?php if($_SESSION['type'] == "teacher") { echo ".finaliseform, #finalise, #finalise[type='button'] { display: none; }"; } ?>
      
    </style>
  </body>
</html>