<?php
    session_start();
    if (! isset($_SESSION["user_id"])) {
        header("Location:login.php");
    }
    if ($_SESSION["account_type"] == "member") {
        header("Location:logout.php");
    }
    $conn = new PDO('pgsql:host=localhost;dbname=Comp3005Project', 'postgres', 'postgres');
?>

<!DOCTYPE html>
<html lang="en">

<head>
   <meta charset="utf-8">
   <title>Register for Health and Fitness Club</title>
   <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <h1>Health and Fitness Club</h1>
    </header>
    <nav>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
        <a href="profile.php">Profile</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="member_schedule.php">Scheduling</a>
        <a href="trainer_schedule.php">Scheduling (Trainer)</a>
        <a href="search_member.php" class="selected">Member Search</a>
        <a href="room_bookings.php">Manage Room Bookings</a>
        <a href="equipment_maintenance.php">Equipment Maintenance</a>
        <a href="class_schedules.php">Class Schedules</a>
        <a href="payments.php">Payment Processing</a>
        <a href="logout.php">Log out</a>
    </nav>
    <?php
        if (isset($_SESSION["email"])) {
            echo "<fieldset>";
            echo "You are currently logged in as: ";
            echo $_SESSION["email"];
            echo "<br>";
            echo "Account type is: ";
            echo $_SESSION["account_type"];
            echo "</fieldset>";
        }
    ?>
    <form method="POST" action="">
        <fieldset>
            <legend>Member Search By Name</legend>
            <label>First Name: </label>
            <input type="text" name="fname"><br>
            <label>Last Name: </label>
            <input type="text" name="lname"><br>
            <input type="submit" value="Search">
        </fieldset>
    </form>
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $stmt = $conn->prepare("SELECT id FROM members WHERE fname = ? AND lname = ?");
            $stmt->execute(array($_POST["fname"], $_POST["lname"]));
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            if (isset($result["id"])) {
                $search_id = $result["id"];


                $stmt = $conn->prepare("SELECT * FROM members WHERE id = ?");
                $stmt->execute(array($search_id));
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                $fname = $result["fname"];
                $lname = $result["lname"];
                $email = $result["email"];

                $stmt = $conn->prepare("SELECT * FROM members_health WHERE id = ?");
                $stmt->execute(array($search_id));
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                $current_weight = $result["current_weight"];
                $goal_weight = $result["goal_weight"];
                $goal_date = $result["goal_date"];

                $stmt = $conn->prepare("SELECT * FROM workout_history WHERE id = ?");
                $stmt->execute(array($search_id));
                $counter1 = 0;
                while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $workout_date[$counter1] = $result["workout_date"];
                    $counter1++;
                }

                $stmt = $conn->prepare("SELECT day, room_number FROM takes JOIN fitness_classes ON takes.class_id = fitness_classes.id WHERE takes.member_id = ? ORDER BY room_number ASC");
                $stmt->execute(array($search_id));
                $counter2 = 0;
                while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $class_date[$counter2] = $result["day"];
                    $room_number[$counter2] = $result["room_number"];
                    $counter2++;
                }
                echo "<fieldset><legend>Results</legend>";

                echo "<fieldset><legend>Personal Information</legend>";
                echo "Name: ";
                echo $fname;
                echo " ";
                echo $lname;
                echo "<br>";
                echo "Email Address: ";
                echo $email;
                echo "</fieldset>";

                echo "<fieldset><legend>Fitness Statistics</legend>";
                echo "Current Weight: ";
                echo $current_weight;
                echo " lbs";
                echo "<br>";
                echo "Goal Weight: ";
                echo $goal_weight;
                echo " lbs";
                echo "<br>";
                echo "Goal Date: ";
                echo $goal_date;
                echo "</fieldset>";


                echo "<fieldset><legend>Workout History</legend>";
                for ($i = 0; $i < $counter1; $i++) {
                    echo $workout_date[$i];
                    echo "<br>";
                }
                echo "</fieldset>";


                echo "<fieldset><legend>Classes Registered For</legend>";
                for ($i = 0; $i < $counter2; $i++) {
                    echo $class_date[$i];
                    echo " In ";
                    echo "room number ";
                    echo $room_number[$i];
                    echo "<br>";
                }
                echo "</fieldset>";
                echo "</fieldset>";
            } else {
                echo "<fieldset><legend>Results</legend>There were no results for that search</fieldset>";
            }
        }
    ?>
</body>
</html>