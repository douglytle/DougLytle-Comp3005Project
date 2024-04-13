<?php
    session_start();
    if (! isset($_SESSION["user_id"])) {
        header("Location:login.php");
    }
    if ($_SESSION["account_type"] != "trainer") {
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
        <a href="trainer_schedule.php" class="selected">Scheduling (Trainer)</a>
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
    <?php
        $stmt = $conn->prepare("SELECT day FROM trainers_availability WHERE id = ?");
        $stmt->execute(array($_SESSION["user_id"]));
        $counter = 0;
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $day[$counter] = $result["day"];
            $counter++;
        }

    ?>
    <fieldset>
        <legend>You Are Currently Available on These Days</legend>
        <?php
            for ($i = 0; $i < $counter; $i++) {
                echo $day[$i];
                echo "<br>";
            }
        ?>
    </fieldset>
    <form method="POST" action="">
        <fieldset>
            <legend>Change Your Availability</legend>
            <label>Monday</label>
            <input type="checkbox" name="monday" value="monday"><br>
            <label>Tuesday</label>
            <input type="checkbox" name="tuesday" value="tuesday"><br>
            <label>Wednesday</label>
            <input type="checkbox" name="wednesday" value="wednesday"><br>
            <label>Thursday</label>
            <input type="checkbox" name="thursday" value="thursday"><br>
            <label>Friday</label>
            <input type="checkbox" name="friday" value="friday"><br>
            <label>Saturday</label>
            <input type="checkbox" name="saturday" value="saturday"><br>
            <label>Sunday</label>
            <input type="checkbox" name="sunday" value="sunday"><br>
            <input type="submit">
        </fieldset>
    </form>
    <?php
        $stmt = $conn->prepare("INSERT INTO trainers_availability (id, day) VALUES (?, ?) ON CONFLICT DO NOTHING");
        $stmt2 = $conn->prepare("DELETE FROM trainers_availability WHERE id = ? AND day = ?");
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["monday"])) {
                $stmt->execute(array($_SESSION["user_id"], "monday"));
            } else {
                $stmt2->execute(array($_SESSION["user_id"], "monday"));
            }
            if (isset($_POST["tuesday"])) {
                $stmt->execute(array($_SESSION["user_id"], "tuesday"));
            } else {
                $stmt2->execute(array($_SESSION["user_id"], "tuesday"));
            }
            if (isset($_POST["wednesday"])) {
                $stmt->execute(array($_SESSION["user_id"], "wednesday"));
            } else {
                $stmt2->execute(array($_SESSION["user_id"], "wednesday"));
            }
            if (isset($_POST["thursday"])) {
                $stmt->execute(array($_SESSION["user_id"], "thursday"));
            } else {
                $stmt2->execute(array($_SESSION["user_id"], "thursday"));
            }
            if (isset($_POST["friday"])) {
                $stmt->execute(array($_SESSION["user_id"], "friday"));
            } else {
                $stmt2->execute(array($_SESSION["user_id"], "friday"));
            }
            if (isset($_POST["saturday"])) {
                $stmt->execute(array($_SESSION["user_id"], "saturday"));
            } else {
                $stmt2->execute(array($_SESSION["user_id"], "saturday"));
            }
            if (isset($_POST["sunday"])) {
                $stmt->execute(array($_SESSION["user_id"], "sunday"));
            } else {
                $stmt2->execute(array($_SESSION["user_id"], "sunday"));
            }
            echo "Refresh to see changes";
        }
        
    ?>

</body>
</html>