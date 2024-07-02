<?php
session_start();
session_regenerate_id();
// Reset all session variables
function resetSessions() {
    $_SESSION = array(); // Clear all session variables
    session_destroy(); // Destroy the session
    return true;
}

// Reset all session variables if the button is clicked
if (isset($_POST['resetSessions'])) {
    if (resetSessions()) {
        $message = "All session variables have been reset successfully.";
    } else {
        $message = "No session variables found to reset.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Sessions</title>
</head>
<body>
    <h2>Reset Sessions</h2>
    <form method="post">
        <p>Click the button below to reset all session variables:</p>
        <button type="submit" name="resetSessions">Reset Sessions</button>
    </form>
    <?php if (isset($message)) : ?>
        <p><?php echo $message; ?></p>
    <?php endif; ?>
</body>
</html>
