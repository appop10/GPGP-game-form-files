<?php

/*
    To Do: 
    - Add session variable check to see if user is logged in
*/

// check session variable to see if user is logged in
// if not, redirect to login page
// session variable: member_role 
// member_role = member or admin

// session_start();

// if ($_SESSION['member_role']) {
// } else {
//     header("Location: loginPage.php");
// }

// Pull event names from database
$readyStmt = true;

try {
    require "dbConnect.php";
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = "SELECT event_id, event_name FROM gpgp_event";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->setFetchMode(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $readyStmt = false;
}

// Form submission
$formSubmitted = false;
$confirmMsg = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $formSubmitted = true;

    $eventName = $_POST['eventName'];
    $gameName = $_POST['gameName'];
    $gameDate = $_POST['gameDate'];
    $gameJudge = $_POST['gameJudge'];
    $gameSession = $_POST['gameSession'];
    $numberOfPlayers = $_POST['numberOfPlayers'];

    if (isset($_POST['gameAltTime'])) {
        $gameAltTime = $_POST['gameAltTime'];
    } else {
        $gameAltTime = '';
    }

    if (isset($_POST['gameNotes'])) {
        $gameNotes = $_POST['gameNotes'];
    } else {
        $gameNotes = '';
    }

    if (isset($_POST['gameRules'])) {
        $gameRules = $_POST['gameRules'];
    } else {
        $gameRules = '';
    }

    // try {
    //     require "dbConnect.php";
    //     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    //     $sql = "INSERT INTO gpgp_games (game_event, game_name, game_date, game_judge, game_session, game_alt_start_time, game_number_of_players, game_notes, game_rules) VALUES (:gameEvent, :gameName, :gameDate, :gameJudge, :gameSession, :gameAltTime, :numberOfPlayers, :gameNotes, :gameRules)";

    //     $stmt = $conn->prepare("$sql");
    //     $stmt->bindParam(':gameEvent', $eventName);
    //     $stmt->bindParam(':gameName', $gameName);
    //     $stmt->bindParam(':gameDate', $gameDate);
    //     $stmt->bindParam(':gameJudge', $gameJudge);
    //     $stmt->bindParam(':gameSession', $gameSession);
    //     $stmt->bindParam(':gameAltTime', $gameAltTime);
    //     $stmt->bindParam(':numberOfPlayers', $numberOfPlayers);
    //     $stmt->bindParam(':gameNotes', $gameNotes);
    //     $stmt->bindParam(':gameRules', $gameRules);

    //     $stmt->execute();
    //     $conn = null;
    //     $confirmMsg = "Game added successfully";
    // } catch (PDOException $e) {
    //     $confirmMsg = "Opps, something went wrong";
    // }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Great Plains Games Players add game information form">
    <title>Add Game Information - Great Plains Game Players</title>

    <link rel="icon" type="image/x-icon" href="images/GPGP_Logo_Transparent_white.png">
    <link rel="stylesheet" href="stylesheets/game-form-stylesheet.css">
    <link rel="stylesheet" href="https://use.typekit.net/lqa6jva.css">
    <!-- <script src="js-files/main.js"></script> -->
    <script src="js-files/gameForm.js"></script>
</head>
<body>
<!-- <body onload="pageLoad()"> -->
    <nav>
        <a href="index.html" class="GPGP-large">GPGP</a>

        <div class="menu-links">
            <ul>
                <li><a href="#">About</a></li>
                <li><a href="#">Events</a></li>
                <li><a href="#">Links</a></li>
            </ul>

            <a href="#" class="login-button">Login</a>
        </div>

        <div class="hamburger-menu">
            <span id="ham-bar-1"></span>
            <span id="ham-bar-2"></span>
            <span id="ham-bar-3"></span>
        </div>
    </nav>

    <?php
    if ($formSubmitted) {
    ?>
        <div class="confirm-message-container">
            <h2 class="confirm-message">Event Added Successfully!</h2>
            
            <div class="form-row">
            <a href="gpgp-event-info.html" class="button close-button">View Event Page</a>
            <button class="clear-button close-button">Close Confirmation</button>
            </div>
        </div>
    <?php
    }
    ?>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post" class="game-input-form">
            <h1>Game Information</h1>

            <h2>Required Fields</h2>
            <p class="input-container">
                <label for="eventName" class="yellow-label">Event Name: </label>
                <select name="eventName" id="eventName" class="input-field" required>
                    <option value="">Please select an event</option>
                    <?php 
                        if ($readyStmt == false) {
                            echo "<option value=''>No Events Found</option>";
                        } else {
                            while ($row = $stmt->fetch()) {
                                echo "<option value='" . $row['event_name'] . "' id='gameID-" . $row['event_id'] . "'>" . $row['event_name'] . "</option>";
                            }
                        }
                    ?>
                </select>
            </p>

            <p class="input-container">
                <label for="gameName" class="yellow-label">Game Name: </label>
                <input type="text" name="gameName" id="gameName" placeholder="Game Name" class="input-field" required>
            </p>

            <p class="input-container">
                <label for="gameDate" class="yellow-label">Game Date: </label>
                <select name="gameDate" id="gameDate" class="input-field" required>
                    <option value="">Please select an event</option>
                    <?php 
                        if ($readyStmt == false) {
                            echo "<option value=''>No Dates Found</option>";
                        }
                    ?>
                </select>
            </p>

            <p>
            <p class="yellow-label session-title">Game Session: </p>

            <div class="form-row">
                <p class="session-option">
                    <input type="radio" name="gameSession" id="gameSession1" value="1" class="radio-button" required>
                    <label for="gameSession1">9:00 AM - 1:00 PM</label>
                </p>

                <p class="session-option">
                    <input type="radio" name="gameSession" id="gameSession2" value="2" class="radio-button">
                    <label for="gameSession2">Lunch</label>
                </p>

                <p class="session-option">
                    <input type="radio" name="gameSession" id="gameSession3" value="3" class="radio-button">
                    <label for="gameSession3">2:00 PM - 6:00 PM</label>
                </p>

                <p class="session-option">
                    <input type="radio" name="gameSession" id="gameSession4" value="4" class="radio-button">
                    <label for="gameSession4">Dinner</label>
                </p>

                <p class="session-option">
                    <input type="radio" name="gameSession" id="gameSession5" value="5" class="radio-button">
                    <label for="gameSession5">7:00 PM - </label>
                </p>
            </div>
            </p>

            <h2>Optional Fields</h2>
            <p class="input-container">
                    <label for="gameJudge" class="yellow-label">Game Judge: </label>
                    <input type="text" name="gameJudge" id="gameJudge" placeholder="Game Judge" class="input-field">
                </p>

                <p class="input-container">
                    <label for="numberOfPlayers" class="yellow-label">Number of Players: </label>
                    <input type="number" name="numberOfPlayers" id="numberOfPlayers" step="1" min="1" class="input-field">
                </p>

            <p class="input-container">
                <label for="gameAltTime" class="yellow-label">Alternate Start Time (optional): </label>
                <input type="time" name="gameAltTime" id="gameAltTime" class="input-field alt-time-field">
            </p>

            <p class="textarea-container">
                <label for="gameNotes" class="yellow-label">Games Rules:</label>
                <textarea name="gameNotes" id="gameNotes" cols="30" rows="10" maxlength="200" placeholder="Max 200 characters" class="textarea"></textarea>
            </p>

            <p class="textarea-container">
                <label for="gameRules" class="yellow-label">Games Notes:</label>
                <textarea name="gameRules" id="gameRules" cols="30" rows="10" maxlength="750" placeholder="Max 750 characters" class="textarea"></textarea>
            </p>

            <p class="form-row">
                <input type="submit" name="submit" id="submit" value="Add Game" class="button submit-button">
                <input type="reset" name="reset" id="reset" value="Clear Form" class="clear-button">
            </p>
        </form>

    <footer>
        <ul>
            <li><a href="#">Code of Conduct</a></li>
            <li><a href="#">Privacy Policy</a></li>
        </ul>

        <p>&copy;2024 All Rights Reserved</p>
    </footer>
</body>

</html>