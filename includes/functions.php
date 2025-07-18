<?php
function log_activity($conn, $user_id, $action, $description) {
    $sql = "INSERT INTO activity_logs (user_id, action, description) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iss", $user_id, $action, $description);
    $stmt->execute();
}
?>