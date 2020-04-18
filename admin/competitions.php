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
    
    if ($_SESSION['type'] !== "admin") {
        header("Location: ../summary.php");
    }
    
    $secure = $conn->prepare('SELECT colour FROM houses WHERE house = ?');
    $secure->bind_param('s', $_SESSION['house']);
    $secure->execute();
    $secure->store_result();
    $secure->bind_result($house_color);
    $secure->fetch();
?>

<!DOCTYPE HTML>
<html>
  <head>
    <title>Admin | Competitions</title>
  </head>
  <body>
    <div class='header'>Competitions</div>
    <div class='overflow-table'>
        <?php
        $sql = "SELECT event FROM competitions";
        $result = mysqli_query($conn, $sql);
        echo '<table class="competition-table">';
        
        if (mysqli_num_rows($result) > 0) {
            // output data of each row
            while($row = mysqli_fetch_assoc($result)) {
                $eventname = $row["event"];
                $secure = $conn->prepare("SELECT `bilin bilin`, `barnes`, `francis`, `karle` FROM competitions WHERE event = ?");
                $secure->bind_param('s', $eventname);
                $secure->execute();
                $secure->store_result();
                $secure->bind_result($bilin_bilin, $barnes, $francis, $karle);
                $secure->fetch();
                echo '<tr>
                  <td id="event"><a href=../event.php?name=' . urlencode($eventname) . '>' . $eventname . '</a></td>
                  <td id="spacer"></td>
                  <td id="bilin-bilin">' . $bilin_bilin . '</td>
                  <td id="barnes">' . $barnes . '</td>
                  <td id="francis">' . $francis . '</td>
                  <td id="karle">' . $karle . '</td>
                </tr>';
            }
        }
        echo '</table>';
        ?>
    </div>
    <div class='vl'></div>
    <div class='new-comp'>
        <?php
        $issue = "no issues";
        
        if ((isset($_POST['new-event-button'])) && (!empty($_POST['new-event-name']))) {
            $new_event_name = ucwords($_POST['new-event-name']);
            if ($secure = $conn->prepare("INSERT INTO competitions(event) VALUES(?)")) {
                $secure->bind_param('s', $new_event_name);
                $secure->execute();
                $secure->close();
                header('Location:competitions.php'); // Refresh to show live update
                exit(); // Stop it from refreshing and inserting the data again
            }
        } else {
            $issue = "new event field empty";
        }
        
        if ((isset($_POST['delete-event-button'])) && (!empty($_POST['delete-event-name']))) {
            $delete_event_name = ucwords($_POST['delete-event-name']);
            if ($secure = $conn->prepare("DELETE FROM competitions WHERE event = ?")) {
                $secure->bind_param('s', $delete_event_name);
                $secure->execute();
                $secure->close();
                header('Location:competitions.php'); // Refresh to show live update
                exit(); // Stop it from refreshing and inserting the data again
            }
        } else {
            $issue = "delete event field empty";
        }
        ?>
        <form id='event-form' method='POST' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>'>
            <label id='event-label'>Create Event</label>
            <input name='new-event-name' id='event-name-field' type='text' autocapitalize="word">
            <input name='new-event-button' id='event-button' type='submit' value='create'>
            <br>
        </form>
        <form id='event-form' method='POST' action='<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>'>
            <label id='event-label'>Delete Event</label>
            <input name='delete-event-name' id='event-name-field' type='text' autocapitalize="word">
            <input name='delete-event-button' id='event-button' type='submit' value='delete'>
            <label name='delete-issue' id='issue-label'><?php echo $issue; ?></label>
        </form>
    </div>
    
    <style>
      @import url('https://fonts.googleapis.com/css?family=Bungee&display=swap');
      @import url('https://fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700,800,900&display=swap');
      
      @keyframes poof {
        from {visibility: visible;}
        to {visibility: hidden;}
      }
      
      html, body {
        display: grid;
        margin: 0;
        padding: 0;
        height: 100%;
        width: 100%;
      }
      
      body {
        grid-template-rows: 18% 80%;
        grid-template-columns: 30% 50% 20%;
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
      
      .overflow-table {
        grid-row: 2;
        grid-column: 2;
        width: 100%;
        height: 75%;
        overflow: auto;
        margin: auto;
        white-space: nowrap;
      }
      
      .competition-table {
        width: 100%;
        font-family: 'Bungee', regular;
        font-size: 200%;
      }
      
      .vl {
        grid-row: 2;
        grid-column: 3;
        border-left: 2px solid black;
        height: 80%;
        margin: auto;
        margin-left: 10%;
      }
      
      #spacer {
        width: 50%;
      }
      
      .new-comp {
        box-sizing: border-box;
        padding: 5%;
        grid-row: 2;
        grid-column: 1;
        height: 80%;
        width: 80%;
        background-color: <?php echo $house_color ?>;
        margin: auto;
      }
      
      #event {
        font-family: 'Raleway';
        font-weight: 200;
      }
      
      #event-label {
        display: grid;
        color: white;
        font-family: 'Raleway';
        font-size: 120%;
        margin-left: 5%;
      }
        
      #event-name-field {
        display: grid;
        border-radius: 50px;
        border: 0 solid transparent;
        width: 90%;
        height: 45px;
        font-size: 150%;
        padding-left: 5%;
        padding-right: 5%;
        font-family: 'Raleway';
        text-transform: capitalize;
        margin-top: 4%;
      }
      
      #event-button {
        all: unset;
        display: grid;
        font-family: Bungee;
        font-size: 155%;
        color: white;
        margin: auto;
        margin-top: 5%;
      }
      
      #event-button:hover {
        color: whitesmoke;
      }
      
      #event-create-button:active {
        font-size: 140%;
      }
      
      #issue-label {
        visibility: hidden;
        display: grid;
        text-align: center;
        color: white;
        margin-top: 5%;
        font-family: 'Raleway';
      }
      
      #bilin-bilin {
        color: darkgreen;
      }
      
      #barnes {
        color: red;
      }
      
      #francis {
        color: orange;
      }
      
      #karle {
        color: navy;
      }
      
      a {
        all: unset;
        cursor: pointer;
      }
    </style>
  </body>
</html>