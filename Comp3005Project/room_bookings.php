<?php
    session_start();
    if (! isset($_SESSION["user_id"])) {
        header("Location:login.php");
    }
    if ($_SESSION["account_type"] != "admin") {
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
        <a href="search_member.php">Member Search</a>
        <a href="room_bookings.php" class="selected">Manage Room Bookings</a>
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
        //first, get a list of all rooms which have at least one booking.
        $stmt = $conn->prepare("SELECT DISTINCT room_number FROM fitness_classes ORDER BY room_number ASC");
        $stmt->execute();
        $counter = 0;
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $room_number[$counter] = $result["room_number"];
            $counter++;
        }

        //then, for each of those rooms get a list of the days on which they are booked.
        $stmt2 = $conn->prepare("SELECT day FROM fitness_classes WHERE room_number = ?");
        for ($i = 0; $i < $counter; $i++) {
            $stmt2->execute(array($room_number[$i]));
            echo "<fieldset><legend>Room Number $room_number[$i] is Booked On These Days</legend>";
            while ($result = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                echo $result["day"];
                echo "<br>";
            }
            echo "</fieldset>";
        }
        
        
    ?>

</body>
</html>