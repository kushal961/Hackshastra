<?php
// Start the session
session_start();

// Destroy the session
session_destroy();

// Redirect to login or index page
header("Location: index.html");
exit();
