<?php
//  die("rice");
 function generateCustomerCode($prefixC = "GCC", $length = 7)
{
    $Customer_no = $prefixC;

    for ($i = strlen($prefixC); $i < $length; $i++) {
        $randomNumber = rand(1, 7);

        $Customer_no .= $randomNumber;
    }

    return $Customer_no;
}

function generateOrderID($prefixO = "GL", $length = 7) {
    
    $orderID = $prefixO;

    for ($i = strlen($prefixO); $i < $length; $i++) {
        $randomNumber = rand(1, 7);

        $orderID .= $randomNumber;
    }

    return $orderID;
}

function generateReferenceID($prefixR = "GR", $length = 7) {
    
    $referenceID = $prefixR;

    for ($i = strlen($prefixR); $i < $length; $i++) {
        $randomNumber = rand(1, 7);

        $referenceID .= $randomNumber;
    }

    return $referenceID;
}
 
function generateDateTimeCode($format = "YmdHis") {
    return date($format);
}

?>