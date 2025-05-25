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
?>
