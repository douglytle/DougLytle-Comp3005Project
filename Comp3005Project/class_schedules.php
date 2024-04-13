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
        <a href="room_bookings.php">Manage Room Bookings</a>
        <a href="equipment_maintenance.php">Equipment Maintenance</a>
        <a href="class_schedules.php" class="selected">Class Schedules</a>
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
    <fieldset>
        <legend>Class Schedule</legend>
        <?php
            for ($i = 0; $i < $counter; $i++) {
                echo "<form method=\"POST\" action=\"\">";
                echo "<fieldset><legend>Class with ID: $class_id[$i]</legend>";
                echo $class_date[$i];
                echo " In room number ";
                echo $room_number[$i];
                echo " <input type=\"submit\" value=\"Cancel this Class?\">";
                echo "<input type=\"hidden\" name=\"cancel\" value=$class_id[$i]>";
                echo "</fieldset>";
                echo "</form>";
            }
        ?>
    </fieldset>
    <form method="POST" action="">
        <fieldset>
            <legend>Create a New Fitness Class?</legend>
            <label>Room Number: </label>
            <input type="number" name="room_number"><br>
            <label>Day of the Week: </label>
            <input type="text" name="day"><br>
            <label>Teacher: </label><br>
            <?php
                $stmt = $conn->prepare("SELECT id FROM trainers ORDER BY id ASC");
                $stmt->execute();
                $counter = 0;
                while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $id = $result["id"];
                    echo "<input type=\"radio\" name=\"trainer\" value=$id>";
                    echo "<label>Trainer with ID: $id</label>";
                    echo " (Availability: ";

                    $stmt2 = $conn->prepare("SELECT day FROM trainers_availability WHERE id = ?");
                    $stmt2->execute(array($id));
                    $counter2 = 0;
                    while ($result2 = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                        $day[$counter2] = $result2["day"];
                        $counter2++;
                    }
                    for ($i = 0; $i < $counter2; $i++) {
                        echo $day[$i];
                        if ($i < $counter2 - 1) {
                            echo ", ";
                        }
                    }
                    echo ")";
                    $counter++;
                }
            ?>
            <br><input type="submit" value="Create Class">
        </fieldset>
    </form>
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["cancel"])) {
                //reclaim availability
                $stmt = $conn->prepare("SELECT teaches.trainer_id, fitness_classes.day FROM teaches JOIN fitness_classes ON teaches.class_id = fitness_classes.id WHERE fitness_classes.id = ?");
                $stmt->execute(array($_POST["cancel"]));
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $day = $result["day"];
                $trainer_id = $result["trainer_id"];

                $stmt = $conn->prepare("INSERT INTO trainers_availability (id, day) VALUES (?, ?)");
                $stmt->execute(array($trainer_id, $day));

                //delete class
                $stmt = $conn->prepare("DELETE FROM teaches WHERE class_id = ?");
                $stmt->execute(array($_POST["cancel"]));

                $stmt = $conn->prepare("DELETE FROM takes WHERE class_id = ?");
                $stmt->execute(array($_POST["cancel"]));

                $stmt = $conn->prepare("DELETE FROM fitness_classes WHERE id = ?");
                $stmt->execute(array($_POST["cancel"]));

                

                echo "Class Cancelled Successfully. Refresh to see changes.";
            } else {
                $stmt = $conn->prepare("INSERT INTO fitness_classes (day, room_number) VALUES (?, ?)");
                $stmt->execute(array($_POST["day"], $_POST["room_number"]));

                $stmt = $conn->prepare("SELECT MAX(id) FROM fitness_classes");
                $stmt->execute();
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                $id_to_add = $result["max"];

                $stmt = $conn->prepare("INSERT INTO teaches (trainer_id, class_id) VALUES (?, ?)");
                $stmt->execute(array($_POST["trainer"], $id_to_add));

                $stmt = $conn->prepare("DELETE FROM trainers_availability WHERE id = ? AND day = ?");
                $stmt->execute(array($_POST["trainer"], $_POST["day"]));

                echo "Class Created Successfully. Refresh to see changes.";
            }
        }
    ?>

</body>
</html>