<?php

function generateProductCode($prefix = "GC", $length = 13) {
    $productCode = $prefix;

    for ($i = strlen($prefix); $i < $length; $i++) {
        // Generate a random number between 1 and 13
        $randomNumber = rand(1, 13);
        
        // Append the random number to the product code
        $productCode .= $randomNumber;
    }

    return $productCode;
}

function generateAdminCode($prefixA = "GCA", $length = 11) {
    $adminCode = $prefixA;

    for ($i = strlen($prefixA); $i < $length; $i++) {
        $randomNumber = rand(1,11);

        $adminCode .= $randomNumber;
    }

    return $adminCode;
}

function generateDateTimeCode($format = "YmdHis") {
    return date($format);
}
?>