<?php
// function fibonacciWithDigits($digits) {
//     $fibonacci = [1, 1];
//     $index = 2;

//     while (true) {
//         $fibonacci[$index] = bcadd($fibonacci[$index - 1], $fibonacci[$index - 2]);
//         if (strlen($fibonacci[$index]) >= $digits) {
//             return $index + 1; // Add 1 to get the 1-based index
//         }
//         $index++;
//     }
// }
function addBigNumbers($num1, $num2) {
    $len1 = strlen($num1);
    $len2 = strlen($num2);

    $maxLength = max($len1, $len2);
    $carry = 0;
    $result = '';

    for ($i = 0; $i < $maxLength; $i++) {
        $digit1 = $i < $len1 ? (int)$num1[$len1 - 1 - $i] : 0;
        $digit2 = $i < $len2 ? (int)$num2[$len2 - 1 - $i] : 0;

        $sum = $digit1 + $digit2 + $carry;
        $carry = (int)($sum / 10);
        $result .= (string)($sum % 10);
    }

    if ($carry > 0) {
        $result .= (string)$carry;
    }

    return strrev($result);
}

function fibonacciWithDigits($digits) {
    $fibonacci = ['1', '1'];
    $index = 2;

    while (strlen($fibonacci[$index - 1]) < $digits) {
        $fibonacci[$index] = addBigNumbers($fibonacci[$index - 1], $fibonacci[$index - 2]);
        $index++;
    }

    return $index;
}
$index = fibonacciWithDigits(1000);
echo $index;
?>
