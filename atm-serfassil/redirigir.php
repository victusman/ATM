<?php
function redirigir($url, $delay = 3000) {
    echo '<html><head><script src="/atm-ser/proceso.js"></script></head><body>';
    echo "<script>redirigir('$url', $delay);</script>";
    echo '</body></html>';
    exit;
}
?>
