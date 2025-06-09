
<?php
session_start();

function is_rate_limited($key, $limit = 3, $window = 60) {
    if (!isset($_SESSION['attempts'][$key])) {
        $_SESSION['attempts'][$key] = [];
    }

    $now = time();
    $_SESSION['attempts'][$key] = array_filter($_SESSION['attempts'][$key], fn($t) => $t > $now - $window);

    if (count($_SESSION['attempts'][$key]) >= $limit) {
        return true;
    }

    $_SESSION['attempts'][$key][] = $now;
    return false;
}
?>
