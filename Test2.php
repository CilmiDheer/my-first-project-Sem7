$students = [
    ["Cumar", 20, "Computer Science"],
    ["Ahmed", 21, "IT"],
    ["Hassan", 22, "Software Engineering"],
    ["Ali", 19, "Database"]
];

echo "<table border='1' cellpadding='10'>";

echo "<tr>";
echo "<th>Name</th>";
echo "<th>Age</th>";
echo "<th>Course</th>";
echo "</tr>";

foreach ($students as $student) {
    echo "<tr>";
    echo "<td>" . $student[0] . "</td>";
    echo "<td>" . $student[1] . "</td>";
    echo "<td>" . $student[2] . "</td>";
    echo "</tr>";
}

echo "</table>";

?>