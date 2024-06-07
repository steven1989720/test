<?php
function recurringCycleLength($d) {
    $remainders = [];
    $numerator = 1;
    $position = 0;

    while (!isset($remainders[$numerator]) && $numerator != 0) {
        $remainders[$numerator] = $position;
        $numerator *= 10;
        $numerator %= $d;
        $position++;
    }

    return $numerator == 0 ? 0 : $position - $remainders[$numerator];
}

$maxCycleLength = 0;
$result = 0;

for ($d = 2; $d < 1000; $d++) {
    $cycleLength = recurringCycleLength($d);
    if ($cycleLength > $maxCycleLength) {
        $maxCycleLength = $cycleLength;
        $result = $d;
    }
}

echo $result;

?>