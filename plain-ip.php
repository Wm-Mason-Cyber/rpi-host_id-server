<?php
    // Set content type to plain text
    header('Content-Type: text/plain');

    // Execute a command to get the primary IP address (using the same logic as the dashboard)
    $ipAddress = shell_exec("hostname -I | awk '{print $1}'");
    echo trim($ipAddress);
?>
