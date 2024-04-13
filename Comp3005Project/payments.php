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
        <a href="class_schedules.php">Class Schedules</a>
        <a href="payments.php" class="selected">Payment Processing</a>
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
    <fieldset>
        <legend>Outstanding Bills</legend>
        <?php
            $stmt = $conn->prepare("SELECT * FROM bills ORDER BY id ASC");
            $stmt->execute();
            $counter = 0;
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $bill_id[$counter] = $result["id"];
                $member_id[$counter] = $result["member_id"];
                $amount[$counter] = $result["amount"];
                $counter++;
            }
            for ($i = 0; $i < $counter; $i++) {
                echo "<form method=\"POST\" action=\"\">";
                echo "<fieldset><legend>Bill with ID: $bill_id[$i]</legend>";
                echo "For member with ID: $member_id[$i]<br>";
                echo "Of the amount: $amount[$i]<br>";
                echo "<input type=\"submit\" value=\"Mark as Paid?\">";
                echo "<input type=\"hidden\" name=\"bill_id\" value=$bill_id[$i]>";
                echo "</fieldset>";
                echo "</form>";
            }
        ?>
    </fieldset>
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $stmt = $conn->prepare("DELETE FROM bills WHERE id = ?");
            $stmt->execute(array($_POST["bill_id"]));

            echo "Bill Marked as Paid Successfully. Refresh to see Results.";
        }
    ?>
</body>
</html>