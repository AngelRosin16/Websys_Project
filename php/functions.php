<?php
$action = $_GET['action'] ?? '';
$category = $_GET['category'] ?? '';
$status = $_GET['status'] ?? '';

$conn = new mysqli("localhost", "root", "", "cmu_trackit");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($action == 'lost' || $action == 'found') {
    echo "<h2>Submit a " . ucfirst($action) . " Item</h2>";
    // Future: Show form to submit lost/found item
} else {
    $sql = "SELECT * FROM items WHERE 1=1";
    if ($category != '') {
        $sql .= " AND category='$category'";
    }
    if ($status != '') {
        $sql .= " AND status='$status'";
    }

    $result = $conn->query($sql);

    echo "<h2>Search Results:</h2>";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<div><strong>{$row['item_name']}</strong> - {$row['category']} ({$row['status']})</div>";
        }
    } else {
        echo "No items found.";
    }
}

if ($action == 'register') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $conn->real_escape_string($_POST['username']);
        $email = $conn->real_escape_string($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        $checkUser = $conn->query("SELECT * FROM users WHERE username='$username' OR email='$email'");
        if ($checkUser->num_rows > 0) {
            echo "Username or email already exists.";
        } else {
            $conn->query("INSERT INTO users (username, password_hash, email) VALUES ('$username', '$password', '$email')");
            echo "Registration successful! <a href='login.html'>Login here</a>";
        }
    } else {
        echo "<h2>Registration</h2>";
        echo "<form method='POST' action='functions.php?action=register'>
                <input type='text' name='username' placeholder='Username' required><br>
                <input type='email' name='email' placeholder='Email' required><br>
                <input type='password' name='password' placeholder='Password' required><br>
                <button type='submit'>Register</button>
              </form>";
    }
} elseif ($action == 'login') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        session_start();
        $username = $conn->real_escape_string($_POST['username']);
        $password = $_POST['password'];

        $result = $conn->query("SELECT * FROM users WHERE username='$username'");
        if ($result->num_rows === 1) {
            $user = $result->fetch_assoc();
            if (password_verify($password, $user['password_hash'])) {
                $_SESSION['username'] = $username;
                echo "Login successful! Welcome, $username. <a href='../index.html'>Go to Home</a>";
            } else {
                echo "Incorrect password.";
            }
        } else {
            echo "User not found.";
        }
    } else {
        echo "<h2>Login</h2>";
        echo "<form method='POST' action='functions.php?action=login'>
                <input type='text' name='username' placeholder='Username' required><br>
                <input type='password' name='password' placeholder='Password' required><br>
                <button type='submit'>Login</button>
              </form>";
    }
}

?>
