<?php

// Redirect helper
function redirect($page) {
    header("Location: " . URLROOT . "/" . $page); 
}
?>