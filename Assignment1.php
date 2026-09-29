<?php

// QUESTION 1


echo "<div style='background:#e3f2fd; padding:15px; margin:10px; border:2px solid #2196f3; border-radius:10px;'>";
echo "<h2 style='color:#1565c0;'>QUESTION 1</h2>";

$a = 25;
$b = 10;
$c = 18;

if ($a >= $b && $a >= $c) {
    $greatest = $a;
} elseif ($b >= $a && $b >= $c) {
    $greatest = $b;
} else {
    $greatest = $c;
}

if ($a <= $b && $a <= $c) {
    $smallest = $a;
} elseif ($b <= $a && $b <= $c) {
    $smallest = $b;
} else {
    $smallest = $c;
}

echo "Number 1 = $a<br>";
echo "Number 2 = $b<br>";
echo "Number 3 = $c<br>";
echo "Greatest = $greatest<br>";
echo "Smallest = $smallest";

echo "</div>";


// QUESTION 2


echo "<div style='background:#e8f5e9; padding:15px; margin:10px; border:2px solid #4caf50; border-radius:10px;'>";
echo "<h2 style='color:#2e7d32;'>QUESTION 2</h2>";

$num = 15;

if ($num % 3 == 0 && $num % 5 == 0) {
    echo "$num is divisible by both 3 and 5.<br>";
} elseif ($num % 3 == 0) {
    echo "$num is divisible by 3.<br>";
} elseif ($num % 5 == 0) {
    echo "$num is divisible by 5.<br>";
} else {
    echo "$num is divisible by none of them.<br>";
}

echo "</div>";


// QUESTION 3


echo "<div style='background:#fff3e0; padding:15px; margin:10px; border:2px solid #ff9800; border-radius:10px;'>";
echo "<h2 style='color:#e65100;'>QUESTION 3</h2>";

echo "<b>Odd Numbers from 2 to 20:</b><br>";

for ($i = 2; $i <= 20; $i++) {
    if ($i % 2 != 0) {
        echo "$i<br>";
    }
}

echo "<br><b>Even Numbers from 35 to 7:</b><br>";

for ($i = 35; $i >= 7; $i--) {
    if ($i % 2 == 0) {
        echo "$i<br>";
    }
}

echo "</div>";


// QUESTION 4

echo "<div style='background:#fce4ec; padding:15px; margin:10px; border:2px solid #e91e63; border-radius:10px;'>";
echo "<h2 style='color:#ad1457;'>QUESTION 4</h2>";

echo "Numbers divisible by 2 and 5:<br>";

for ($i = 50; $i >= 2; $i--) {

    if ($i % 2 == 0 && $i % 5 == 0) {
        echo "$i<br>";
    }

}

echo "</div>";


// QUESTION 5

echo "<div style='background:#f3e5f5; padding:15px; margin:10px; border:2px solid #9c27b0; border-radius:10px;'>";
echo "<h2 style='color:#6a1b9a;'>QUESTION 5</h2>";

$num = 12345;
$reverse = 0;

while ($num > 0) {

    $digit = $num % 10;

    $reverse = ($reverse * 10) + $digit;

    $num = (int)($num / 10);
}

echo "Reverse = $reverse<br>";

echo "</div>";



// QUESTION 6

echo "<div style='background:#fffde7; padding:15px; margin:10px; border:2px solid #fbc02d; border-radius:10px;'>";
echo "<h2 style='color:#f57f17;'>QUESTION 6</h2>";

$a = 8;
$b = 12;

if ($a > $b) {
    $lcm = $a;
} else {
    $lcm = $b;
}

while (true) {

    if ($lcm % $a == 0 && $lcm % $b == 0) {
        break;
    }

    $lcm++;
}

echo "Number 1 = $a<br>";
echo "Number 2 = $b<br>";
echo "LCM = $lcm<br>";

echo "</div>";


// QUESTION 7


echo "<div style='background:#e0f7fa; padding:15px; margin:10px; border:2px solid #00acc1; border-radius:10px;'>";
echo "<h2 style='color:#00838f;'>QUESTION 7</h2>";

$a = 18;
$b = 24;

$hcf = 1;

for ($i = 1; $i <= $a && $i <= $b; $i++) {

    if ($a % $i == 0 && $b % $i == 0) {
        $hcf = $i;
    }

}

echo "Number 1 = $a<br>";
echo "Number 2 = $b<br>";
echo "HCF = $hcf<br>";

echo "</div>";



// QUESTION 8
// MULTIPLICATION TABLE

echo "<div style='background:#ede7f6; padding:20px; margin:10px; border:3px solid #673ab7; border-radius:10px;'>";
echo "<h2 style='color:#4527a0;'>QUESTION 8 - MULTIPLICATION TABLE</h2>";

echo "<table border='1' cellpadding='10' cellspacing='0'
style='border-collapse:collapse; text-align:center; width:100%;'>";

// Header
echo "<tr style='background:#673ab7; color:white;'>";

echo "<th>×</th>";

for ($i = 1; $i <= 12; $i++) {
    echo "<th>$i</th>";
}

echo "</tr>";

// Table rows
for ($i = 1; $i <= 12; $i++) {

    // Different color for row header
    echo "<tr>";

    echo "<th style='background:#9575cd; color:white;'>$i</th>";

    for ($j = 1; $j <= 12; $j++) {

        // Alternating table colors
        if (($i + $j) % 2 == 0) {
            echo "<td style='background:#ede7f6; color:#311b92; font-weight:bold;'>";
        } else {
            echo "<td style='background:#d1c4e9; color:#4527a0; font-weight:bold;'>";
        }

        echo ($i * $j);

        echo "</td>";
    }

    echo "</tr>";
}

echo "</table>";

echo "</div>";


// QUESTION 9


echo "<div style='background:#ffebee; padding:15px; margin:10px; border:2px solid #f44336; border-radius:10px;'>";
echo "<h2 style='color:#c62828;'>QUESTION 9</h2>";

$num = 17;
$count = 0;

for ($i = 1; $i <= $num; $i++) {

    if ($num % $i == 0) {
        $count++;
    }

}

if ($count == 2) {
    echo "$num is a Prime number.<br>";
} else {
    echo "$num is a Non-Prime number.<br>";
}

echo "</div>";



// QUESTION 10


echo "<div style='background:#e8eaf6; padding:15px; margin:10px; border:2px solid #3f51b5; border-radius:10px;'>";
echo "<h2 style='color:#283593;'>QUESTION 10</h2>";

echo "Prime Numbers from 10 to 50:<br><br>";

for ($num = 10; $num <= 50; $num++) {

    $count = 0;

    for ($i = 1; $i <= $num; $i++) {

        if ($num % $i == 0) {
            $count++;
        }

    }

    if ($count == 2) {
        echo "$num<br>";
    }

}

echo "</div>";

?>