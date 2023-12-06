<?php

function generateProductCode($prefix = "GC", $length = 7) {
    $productCode = $prefix;

    for ($i = strlen($prefix); $i < $length; $i++) {
        // Generate a random number between 1 and 7
        $randomNumber = rand(1, 7);
        
        // Append the random number to the product code
        $productCode .= $randomNumber;
    }

    return $productCode;
}

function generateAdminCode($prefixA = "GCA", $length = 7) {
    $adminCode = $prefixA;

    for ($i = strlen($prefixA); $i < $length; $i++) {
        $randomNumber = rand(1,7);

        $adminCode .= $randomNumber;
    }

    return $adminCode;
}

function generateDateTimeCode($format = "YmdHis") {
    return date($format);
}
?>