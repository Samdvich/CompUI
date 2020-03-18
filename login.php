<?php
    $servername = "localhost";
    $username = "spage65";
    $password = "Password1";
    $DB_Name = "spage65_CompUI"; # Using 2020 PHP Default BCRYPT Hash = password_hash($password, PASSWORD_BCRYPT)

    // Create connection
    $conn = new mysqli($servername, $username, $password, $DB_Name);

    // Check connection
      if ($conn->connect_error) {
        die("nope: <br> error: " . $conn->connect_error);
    }
    else echo "yup";
?>

<!DOCTYPE HTML>
<html>
    <head>
        <title></title>
    </head>

    <body>
        <object class='track' data="track.svg" type="image/svg+xml"></object>

        <form class='user-field'>
            <input id='user' type='text' placeholder="Username" maxlength='20'>
        </form>
        <form class='password-field'>
            <input id='password' type='password' placeholder="Password" maxlength='40'>
        </form>

        <div class='user'>User</div>
        <div class='password'>Password</div>

        <style>
            @import url('https://fonts.googleapis.com/css?family=Bungee&display=swap');

            @keyframes boing {
                from {margin-top: 0;}
                50% {margin-top: 2%;}
                to {margin-top: 0;}
            }

            html {
                display: grid;
                height: 100%;
                width: 100%;
                background-color: #B9D07F;
            }

            body {
                display: grid;
                grid-template-columns: auto 30% auto;
                grid-template-rows: 33.333% 33.333% 33.333%;
                margin: 0;
                padding: 0;
            }

            .track {
                display: grid;
                grid-column: 2;
                grid-row: 1 / 4;
                margin: auto;
            }

            .user , .password {
                display: grid;
                grid-column: 2;
                margin: auto;
                margin-top: 0;
                font-family: 'Bungee', regular;
                color: white;
                font-size: 350%;
            }

            .user {
                grid-row: 2;
            }

            .password {
                grid-row: 3;
            }

            .user-field:hover ~ .user , .password-field:hover ~ .password {
                animation-name: boing;
                animation-duration: 2s;
                animation-iteration-count: infinite;
            }

            input {
                background-color: transparent;
                outline: none;
                border: none;
                font-size: 155%;
                font-weight: 700;
                padding: 0;
            }

            .user-field , .password-field {
                display: grid;
                grid-column: 2;
                height: 20%;
                margin: auto;
                background-color: transparent;
                border-radius: 20px;
                background-color: darkgray;
                opacity: 50%;
                padding: 0;
                padding-left: 5%;
                padding-right: 5%;
            }

            .user-field {
                grid-row: 2;
                width: 70%;
            }

            .password-field {
                grid-row: 3;
                width: 90%;
            }

            #user , #password {
                width: 100%;
                cursor: text;
            }
        </style>
    </body>
</html>
