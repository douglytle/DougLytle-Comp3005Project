<?php
    session_start();
    if (! isset($_SESSION["user_id"])) {
        header("Location:login.php");
    }
    if ($_SESSION["account_type"] != "member") {
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
        <a href="profile.php" class="selected">Profile</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="member_schedule.php">Scheduling</a>
        <a href="trainer_schedule.php">Scheduling (Trainer)</a>
        <a href="search_member.php">Member Search</a>
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
            <legend>Update Your Personal Information</legend>
            <label>First Name: </label>
            <input type="text" name="fname"><br>
            <label>Last Name: </label>
            <input type="text" name="lname">
        </fieldset>
        <fieldset>
            <legend>Update Your Fitness Statistics</legend>
            <label>Current Weight: </label>
            <input type="number" name="current_weight"><br>
            <label>Goal Weight: </label>
            <input type="number" name="goal_weight"><br>
            <label>Goal Time: </label>
            <input type="date" name="goal_date"><br>
        </fieldset>
        <fieldset>
            <input type="submit">
        </fieldset>
    </form>
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $stmt = $conn->prepare("UPDATE members SET fname = ?, lname = ? WHERE id = ?");
            $stmt->execute(array($_POST["fname"], $_POST["lname"], $_SESSION["user_id"]));

            $stmt = $conn->prepare("UPDATE members_health SET current_weight = ?, goal_weight = ?, goal_date = ? WHERE id = ?");
            $stmt->execute(array($_POST["current_weight"], $_POST["goal_weight"], $_POST["goal_date"], $_SESSION["user_id"]));
        }
    ?>


</body>
</html>