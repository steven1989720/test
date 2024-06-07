<?php

function PowModSim($Value, $Exponent, $Modulus)
{
    // Check if simulation is even necessary.
    if (function_exists("bcpowmod"))
        return (bcpowmod($Value, $Exponent, $Modulus));

    // Loop until the exponent is reduced to zero.
    $result = 1;

    for ($i = 0 ; $i < $Exponent ; $i ++){
        $result *= $Value;
        $result %= $Modulus;
    }
    return $result;
}
// Function to find the last ten digits of the sum of powers
function lastTenDigitsOfPowerSeries() {
    $sum = 0;
    $mod = 10000000000; // 10^10
    for ($i = 1; $i <= 1000; $i++) {
        // Calculate pow(i, i) modulo 10^10 and add it to the sum
        $sum += PowModSim($i, $i, $mod);
        $sum %= $mod; // Keep the sum within the range of 10^10
    }
    return $sum;
}

// Get the last ten digits of the sum
$lastTenDigits = lastTenDigitsOfPowerSeries();

echo $lastTenDigits;
?>