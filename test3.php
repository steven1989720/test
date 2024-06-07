<?php
function powerOfTwo($exponent) {
    $result = [1]; // Start with 2^0 = 1

    for ($i = 0; $i < $exponent; $i++) {
        $carry = 0;
        for ($j = count($result) - 1; $j >= 0; $j--) {
            $product = $result[$j] * 2 + $carry;
            $result[$j] = $product % 10;
            $carry = floor($product / 10);
        }
        if ($carry > 0) {
            array_unshift($result, $carry);
        }
    }

    return implode("", $result);
}

// Calculate 2^1000
$number = powerOfTwo(1000);

// Initialize sum
$sum = 0;

// Iterate over each digit and sum them up
for ($i = 0; $i < strlen($number); $i++) {
    $sum += intval($number[$i]);
}

echo $sum;

?>
