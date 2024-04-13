<?php
   session_start();
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
        <a href="login.php" class="selected">Login</a>
        <a href="register.php">Register</a>
        <a href="profile.php">Profile</a>
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
            <legend>Log In to Your Account</legend>
            <label>Email Address: </label>
            <input type="email" name="email"><br>
            <label>Password: </label>
            <input type="text" name="password"><br><br>
            <label>Account Type: </label><br>
            <label>Member</label>
            <input type="radio" name="type" value="member"><br>
            <label>Trainer</label>
            <input type="radio" name="type" value="trainer"><br>
            <label>Administrator</label>
            <input type="radio" name="type" value="admin"><br>
            <input type="submit">
        </fieldset>
    </form> 
    <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if ($_POST["type"] == "member") {
                $stmt = $conn->prepare("SELECT id, email, password FROM members WHERE email = ? AND password = ?");
                $stmt->execute(array($_POST["email"], $_POST["password"]));
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if (isset($result["id"])) {
                    $_SESSION["user_id"] = $result["id"];
                    $_SESSION["account_type"] = "member";
                    $_SESSION["email"] = $result["email"];

                    echo "You are now logged in as: ";
                    echo $result["email"];
                    echo " Account type is: ";
                    echo $_SESSION["account_type"];
                }
            }
            if ($_POST["type"] == "trainer") {
                $stmt = $conn->prepare("SELECT id, email, password FROM trainers WHERE email = ? AND password = ?");
                $stmt->execute(array($_POST["email"], $_POST["password"]));
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if (isset($result["id"])) {
                    $_SESSION["user_id"] = $result["id"];
                    $_SESSION["account_type"] = "trainer";
                    $_SESSION["email"] = $result["email"];

                    echo "You are now logged in as: ";
                    echo $result["email"];
                    echo " Account type is: ";
                    echo $_SESSION["account_type"];
                }
            }
            if ($_POST["type"] == "admin") {
                $stmt = $conn->prepare("SELECT id, email, password FROM administrators WHERE email = ? AND password = ?");
                $stmt->execute(array($_POST["email"], $_POST["password"]));
                $result = $stmt->fetch(PDO::FETCH_ASSOC);
                if (isset($result["id"])) {
                    $_SESSION["user_id"] = $result["id"];
                    $_SESSION["account_type"] = "admin";
                    $_SESSION["email"] = $result["email"];

                    echo "You are now logged in as: ";
                    echo $result["email"];
                    echo " Account type is: ";
                    echo $_SESSION["account_type"];
                }
            }
            
        }
    ?>

</body>
</html>