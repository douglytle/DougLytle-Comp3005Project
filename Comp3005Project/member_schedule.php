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
        <a href="profile.php">Profile</a>
        <a href="dashboard.php">Dashboard</a>
        <a href="member_schedule.php" class="selected">Scheduling</a>
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
    <?php
        $stmt = $conn->prepare("SELECT * FROM fitness_classes ORDER BY room_number ASC");
        $stmt->execute();
        $counter = 0;
        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $class_date[$counter] = $result["day"];
            $room_number[$counter] = $result["room_number"];
            $class_id[$counter] = $result["id"];
            $counter++;
        }
    ?>
    <form method="POST" action="">
        <fieldset>
            <legend>Register For Available Group Fitness Classes</legend>
            <?php
                for ($i = 0; $i < $counter; $i++) {
                    echo "<input type=\"radio\" name=\"register\" value=\"$class_id[$i]\">";
                    echo $class_date[$i];
                    echo " In ";
                    echo "room number ";
                    echo $room_number[$i];
                    echo "<br>";
                }
            ?>
            <input type="submit">
        </fieldset>
    </form>
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $stmt = $conn->prepare("INSERT INTO takes (class_id, member_id) VALUES (?, ?) ON CONFLICT DO NOTHING");
            $stmt->execute(array($_POST["register"], $_SESSION["user_id"]));

            $stmt = $conn->prepare("INSERT INTO bills (member_id, amount) VALUES (?, ?)");
            $stmt->execute(array($_SESSION["user_id"], 49.95));
        }
    ?>
</body>
</html>