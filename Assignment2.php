<?php

echo "<h1 style='text-align:center; color:#0b5ed7;'>PHP ASSIGNMENT</h1>";

/* =============
   QUESTION 1
   One Dimensional Array
   =============*/

echo "<div style='background:#e8f4ff; padding:15px; margin:20px 0;'>";
echo "<h2 style='color:#0b5ed7;'>Question 1</h2>";

$array1 = array(5, -7, 12, 10, -7, 11, -6, 12, 1, -7, 2, 9);

echo "<b>1 & 2. All Elements:</b><br>";

foreach ($array1 as $value) {
    echo $value . " ";
}

$total = 0;
$evenTotal = 0;
$oddTotal = 0;

$min = $array1[0];
$max = $array1[0];

$minPositions = array();
$maxPositions = array();

foreach ($array1 as $index => $value) {

    $total += $value;

    if ($value % 2 == 0) {
        $evenTotal += $value;
    } else {
        $oddTotal += $value;
    }

    if ($value < $min) {
        $min = $value;
        $minPositions = array($index);
    } elseif ($value == $min) {
        $minPositions[] = $index;
    }

    if ($value > $max) {
        $max = $value;
        $maxPositions = array($index);
    } elseif ($value == $max) {
        $maxPositions[] = $index;
    }
}

echo "<p><b>3. Total of all elements:</b> $total</p>";
echo "<p><b>4. Total of even elements:</b> $evenTotal</p>";
echo "<p><b>5. Total of odd elements:</b> $oddTotal</p>";
echo "<p><b>6. Minimum:</b> $min | Positions: ";

foreach ($minPositions as $position) {
    echo $position . " ";
}

echo "</p>";

echo "<p><b>7. Maximum:</b> $max | Positions: ";

foreach ($maxPositions as $position) {
    echo $position . " ";
}

echo "</p>";
echo "</div>";



/* ===================
   QUESTION 2
   Associative Two Dimensional Array
   ===============================*/

echo "<div style='background:#fff3cd; padding:15px; margin:20px 0;'>";
echo "<h2 style='color:#856404;'>Question 2</h2>";

$array2 = array(
    "Light" => array(
        "Red" => "Light Red",
        "Green" => "Light Green",
        "Blue" => "Light Blue"
    ),

    "Normal" => array(
        "Red" => "Normal Red",
        "Green" => "Normal Green",
        "Blue" => "Normal Blue"
    ),

    "Dark" => array(
        "Red" => "Dark Red",
        "Green" => "Dark Green",
        "Blue" => "Dark Blue"
    )
);

echo "<table border='1' cellpadding='10' cellspacing='0'
style='border-collapse:collapse; width:80%; text-align:center;'>";

echo "<tr style='background:#ffc107;'>
<th></th>
<th>Red</th>
<th>Green</th>
<th>Blue</th>
</tr>";

foreach ($array2 as $rowName => $row) {

    echo "<tr>";

    echo "<th style='background:#ffe69c;'>$rowName</th>";

    foreach ($row as $value) {
        echo "<td>$value</td>";
    }

    echo "</tr>";
}

echo "</table>";
echo "</div>";



/* =====================
   QUESTION 3
   Square Two Dimensional Array
   =========================== */

echo "<div style='background:#e2f0d9; padding:15px; margin:20px 0;'>";
echo "<h2 style='color:#198754;'>Question 3</h2>";

$array3 = array(
    array(2, -6, 8),
    array(-6, 1, 6),
    array(7, 8, -6)
);


/* 1 & 2. Print all elements */

echo "<h3>1 & 2. Array Elements</h3>";

echo "<table border='1' cellpadding='12' cellspacing='0'
style='border-collapse:collapse; text-align:center;'>";

foreach ($array3 as $row) {

    echo "<tr>";

    foreach ($row as $value) {
        echo "<td>$value</td>";
    }

    echo "</tr>";
}

echo "</table>";


/* 3. Total Odd Elements */

$oddTotal3 = 0;
$evenTotal3 = 0;
$allTotal3 = 0;

foreach ($array3 as $row) {

    foreach ($row as $value) {

        $allTotal3 += $value;

        if ($value % 2 == 0) {
            $evenTotal3 += $value;
        } else {
            $oddTotal3 += $value;
        }
    }
}

echo "<p><b>3. Total of odd elements:</b> $oddTotal3</p>";

/* 4. Total Even Elements */

echo "<p><b>4. Total of even elements:</b> $evenTotal3</p>";


/* 5. Total of Each Row */

echo "<h3>5. Total of Each Row</h3>";

foreach ($array3 as $index => $row) {

    $rowTotal = 0;

    foreach ($row as $value) {
        $rowTotal += $value;
    }

    echo "Row " . ($index + 1) . " Total = $rowTotal<br>";
}


/* 6. Total of Each Column */

echo "<h3>6. Total of Each Column</h3>";

for ($col = 0; $col < 3; $col++) {

    $columnTotal = 0;

    for ($row = 0; $row < 3; $row++) {
        $columnTotal += $array3[$row][$col];
    }

    echo "Column " . ($col + 1) . " Total = $columnTotal<br>";
}


/* 7. Total of Each Diagonal */

$mainDiagonal = 0;
$secondDiagonal = 0;

for ($i = 0; $i < 3; $i++) {

    $mainDiagonal += $array3[$i][$i];

    $secondDiagonal += $array3[$i][2 - $i];
}

echo "<h3>7. Diagonal Totals</h3>";

echo "Main Diagonal Total = $mainDiagonal<br>";
echo "Second Diagonal Total = $secondDiagonal<br>";


/* 8. Total of All Elements */

echo "<h3>8. Total of All Elements</h3>";

echo "<table border='1' cellpadding='12' cellspacing='0'
style='border-collapse:collapse; width:50%; text-align:center;'>";

echo "<tr style='background:#198754; color:white;'>
<th>Description</th>
<th>Total</th>
</tr>";

echo "<tr>
<td>Total of all elements</td>
<td><b>$allTotal3</b></td>
</tr>";

echo "</table>";


/* 9 & 10. Minimum and Maximum */

$min3 = $array3[0][0];
$max3 = $array3[0][0];

$minPositions3 = array();
$maxPositions3 = array();

for ($i = 0; $i < 3; $i++) {

    for ($j = 0; $j < 3; $j++) {

        $value = $array3[$i][$j];

        if ($value < $min3) {

            $min3 = $value;
            $minPositions3 = array(array($i, $j));

        } elseif ($value == $min3) {

            $minPositions3[] = array($i, $j);
        }


        if ($value > $max3) {

            $max3 = $value;
            $maxPositions3 = array(array($i, $j));

        } elseif ($value == $max3) {

            $maxPositions3[] = array($i, $j);
        }
    }
}


echo "<h3>9. Minimum Element</h3>";

echo "Minimum = <b>$min3</b><br>";
echo "Positions: ";

foreach ($minPositions3 as $position) {

    echo "(Row " . ($position[0] + 1) .
         ", Column " . ($position[1] + 1) . ") ";
}


echo "<h3>10. Maximum Element</h3>";

echo "Maximum = <b>$max3</b><br>";
echo "Positions: ";

foreach ($maxPositions3 as $position) {

    echo "(Row " . ($position[0] + 1) .
         ", Column " . ($position[1] + 1) . ") ";
}

echo "</div>";



/* ====================
   QUESTION 4
   Associative Two Dimensional Array
   ================================= */

echo "<div style='background:#f8d7da; padding:15px; margin:20px 0;'>";
echo "<h2 style='color:#842029;'>Question 4</h2>";

$array4 = array(

    "CA221" => array(
        "Name" => "Mohamed Ahmed Ali",
        "Phone" => "0648440403",
        "Address" => "Laba Dhagax, Wardhiigley"
    ),

    "CA223" => array(
        "Name" => "Ahmed Abdi Jama",
        "Phone" => "0647223201",
        "Address" => "Taleex, Hodan"
    ),

    "CA224" => array(
        "Name" => "Amina Nur Adan",
        "Phone" => "0646990276",
        "Address" => "Macmacaanka, Dharkeynley"
    )
);


echo "<table border='1' cellpadding='10' cellspacing='0'
style='border-collapse:collapse; width:90%; text-align:center;'>";

echo "<tr style='background:#dc3545; color:white;'>
<th>Student ID</th>
<th>Name</th>
<th>Phone</th>
<th>Address</th>
</tr>";

foreach ($array4 as $studentID => $student) {

    echo "<tr>";

    echo "<td><b>$studentID</b></td>";
    echo "<td>" . $student["Name"] . "</td>";
    echo "<td>" . $student["Phone"] . "</td>";
    echo "<td>" . $student["Address"] . "</td>";

    echo "</tr>";
}

echo "</table>";
echo "</div>";



/* =====================
   QUESTION 5
   Student Transcript
   ================== */

echo "<div style='background:#d1ecf1; padding:15px; margin:20px 0;'>";
echo "<h2 style='color:#0c5460;'>Question 5</h2>";

$transcript = array(

    "Semester 1" => array(

        "subject1" => array(
            "CW1" => 9,
            "MidTerm" => 26,
            "CW2" => 10,
            "Final" => 40,
            "Total" => 85,
            "Status" => "Pass"
        ),

        "subject2" => array(
            "CW1" => 9,
            "MidTerm" => 26,
            "CW2" => 10,
            "Final" => 40,
            "Total" => 85,
            "Status" => "Pass"
        ),

        "subject3" => array(
            "CW1" => 9,
            "MidTerm" => 26,
            "CW2" => 10,
            "Final" => 40,
            "Total" => 85,
            "Status" => "Pass"
        )
    ),


    "Semester 2" => array(

        "subject1" => array(
            "CW1" => 9,
            "MidTerm" => 26,
            "CW2" => 10,
            "Final" => 0,
            "Total" => 45,
            "Status" => "Fail"
        ),

        "subject2" => array(
            "CW1" => 9,
            "MidTerm" => 26,
            "CW2" => 10,
            "Final" => 40,
            "Total" => 85,
            "Status" => "Pass"
        ),

        "subject3" => array(
            "CW1" => 9,
            "MidTerm" => 26,
            "CW2" => 10,
            "Final" => 40,
            "Total" => 85,
            "Status" => "Pass"
        )
    )
);


echo "<table border='1' cellpadding='10' cellspacing='0'
style='border-collapse:collapse; width:100%; text-align:center;'>";

echo "<tr style='background:#17a2b8; color:white;'>
<th>Semester</th>
<th>Course</th>
<th>CW1</th>
<th>MidTerm</th>
<th>CW2</th>
<th>Final</th>
<th>Total</th>
<th>Status</th>
</tr>";


foreach ($transcript as $semester => $courses) {

    foreach ($courses as $course => $marks) {

        echo "<tr>";

        echo "<td>$semester</td>";
        echo "<td>$course</td>";
        echo "<td>" . $marks["CW1"] . "</td>";
        echo "<td>" . $marks["MidTerm"] . "</td>";
        echo "<td>" . $marks["CW2"] . "</td>";
        echo "<td>" . $marks["Final"] . "</td>";
        echo "<td>" . $marks["Total"] . "</td>";

        if ($marks["Status"] == "Pass") {
            echo "<td style='color:green; font-weight:bold;'>Pass</td>";
        } else {
            echo "<td style='color:red; font-weight:bold;'>Fail</td>";
        }

        echo "</tr>";
    }
}

echo "</table>";

echo "</div>";

?>