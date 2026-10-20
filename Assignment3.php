```php
<?php
session_start();

$errors = [];
$student = null;

function e($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, 'UTF-8');
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fields = [
        "name" => "Student Name",
        "pob" => "Place of Birth",
        "dob" => "Date of Birth",
        "address" => "Address",
        "gender" => "Gender",
        "telephone" => "Telephone",
        "email" => "Email",
        "parent_name" => "Parent Name",
        "parent_telephone" => "Parent Telephone",
        "relationship" => "Relationship",
        "student_id" => "Student ID",
        "faculty" => "Faculty",
        "department" => "Department",
        "class" => "Class",
        "registration_date" => "Registration Date"
    ];

    $data = [];

    foreach ($fields as $key => $label) {
        $data[$key] = trim($_POST[$key] ?? "");

        if ($data[$key] === "") {
            $errors[$key] = "$label is required.";
        }
    }

    // Validate student name
    if (
        $data["name"] !== "" &&
        !preg_match('/^[\p{L}\s]+$/u', $data["name"])
    ) {
        $errors["name"] = "Name must contain only letters and spaces.";
    }

    // Validate parent name
    if (
        $data["parent_name"] !== "" &&
        !preg_match('/^[\p{L}\s]+$/u', $data["parent_name"])
    ) {
        $errors["parent_name"] = "Parent name must contain only letters and spaces.";
    }

    // Validate email
    if (
        $data["email"] !== "" &&
        !filter_var($data["email"], FILTER_VALIDATE_EMAIL)
    ) {
        $errors["email"] = "Please enter a valid email address.";
    }

    // Validate gender
    if (
        $data["gender"] !== "" &&
        !in_array($data["gender"], ["Male", "Female"], true)
    ) {
        $errors["gender"] = "Please select a valid gender.";
    }

    // Validate relationship
    if (
        $data["relationship"] !== "" &&
        !in_array($data["relationship"], ["Father", "Mother", "Guardian", "Other"], true)
    ) {
        $errors["relationship"] = "Please select a valid relationship.";
    }

    // Validate dates
    if ($data["dob"] !== "") {
        $dob = DateTime::createFromFormat('!Y-m-d', $data["dob"]);

        if (!$dob || $dob->format('Y-m-d') !== $data["dob"]
            || $data["dob"] > date('Y-m-d')) {
            $errors["dob"] = "Please enter a valid date of birth.";
        }
    }

    if ($data["registration_date"] !== "") {
        $regDate = DateTime::createFromFormat('!Y-m-d', $data["registration_date"]);

        if (!$regDate || $regDate->format('Y-m-d') !== $data["registration_date"]) {
            $errors["registration_date"] = "Please enter a valid registration date.";
        }
    }

    // Validate uploaded image
    $imagePath = "";

    if (
        !isset($_FILES["image"]) ||
        $_FILES["image"]["error"] === UPLOAD_ERR_NO_FILE
    ) {
        $errors["image"] = "Please upload a student image.";
    } elseif ($_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
        $errors["image"] = "Image upload failed. Please try again.";
    } elseif ($_FILES["image"]["size"] > 2 * 1024 * 1024) {
        $errors["image"] = "Image must not exceed 2 MB.";
    } else {
        $tmp = $_FILES["image"]["tmp_name"];
        $imageInfo = @getimagesize($tmp);
        $mime = $imageInfo["mime"] ?? "";

        $allowed = [
            "image/jpeg" => "jpg",
            "image/png"  => "png",
            "image/gif"  => "gif"
        ];

        if (!isset($allowed[$mime])) {
            $errors["image"] = "Only JPG, JPEG, PNG, and GIF images are allowed.";
        }
    }

    // Save image and student data if valid
    if (empty($errors)) {

        $uploadDir = __DIR__ . DIRECTORY_SEPARATOR . "uploads"
                   . DIRECTORY_SEPARATOR;

        if (!is_dir($uploadDir) &&
            !mkdir($uploadDir, 0755, true)) {
            $errors["image"] = "Could not create the uploads folder.";
        }

        if (empty($errors) && !is_writable($uploadDir)) {
            $errors["image"] = "The uploads folder is not writable.";
        }

        if (empty($errors)) {
            $filename = bin2hex(random_bytes(16)) . "." . $allowed[$mime];
            $destination = $uploadDir . $filename;

            if (!move_uploaded_file($tmp, $destination)) {
                $errors["image"] = "Could not save the uploaded image.";
            } else {
                $imagePath = "uploads/" . $filename;

                $student = $data;
                $student["image"] = $imagePath;

                $_SESSION["registered_student"] = $student;

                // Show the result on the same page
                header("Location: " . strtok($_SERVER["REQUEST_URI"], '?') . "?success=1");
                exit;
            }
        }
    }
}

// Load registered student after redirect
if (isset($_GET["success"]) && isset($_SESSION["registered_student"])) {
    $student = $_SESSION["registered_student"];
}

// Preserve form values if validation fails
function old($key) {
    return e($_POST[$key] ?? "");
}

function errorMsg($key, $errors) {
    if (!isset($errors[$key])) {
        return "";
    }

    return '<div class="text-danger small mt-1">'
         . e($errors[$key]) . '</div>';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Student Registration System</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            background: #f1f5f9;
            font-family: Arial, sans-serif;
            color: #1e293b;
        }

        .hero {
            background: linear-gradient(135deg, #064e3b, #10b981);
            color: white;
            padding: 35px 15px;
            text-align: center;
            border-radius: 0 0 28px 28px;
        }

        .container-main {
            max-width: 1050px;
            margin: 30px auto;
        }

        .card-custom {
            background: white;
            padding: 28px;
            margin-bottom: 22px;
            border-radius: 18px;
            box-shadow: 0 5px 22px rgba(0, 0, 0, .07);
        }

        .section-title {
            color: #047857;
            font-weight: bold;
            border-bottom: 2px solid #d1fae5;
            padding-bottom: 12px;
            margin-bottom: 22px;
        }

        .form-label {
            font-weight: 600;
        }

        .form-control, .form-select {
            padding: 11px;
            border-radius: 9px;
        }

        .form-control:focus, .form-select:focus {
            border-color: #10b981;
            box-shadow: 0 0 0 .2rem rgba(16,185,129,.15);
        }

        .btn-register {
            background: #059669;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 10px;
            font-weight: bold;
        }

        .btn-register:hover {
            background: #047857;
            color: white;
        }

        .required {
            color: #dc2626;
        }

        .student-photo {
            width: 125px;
            height: 125px;
            object-fit: cover;
            border-radius: 50%;
            border: 4px solid #10b981;
        }

        .table th {
            width: 35%;
            background: #047857;
            color: white;
        }

        .table td, .table th {
            padding: 12px;
            vertical-align: middle;
        }

        .result-title {
            background: #d1fae5;
            color: #065f46;
            padding: 12px;
            border-radius: 8px;
            margin-top: 25px;
            font-weight: bold;
        }

        @media print {
            .no-print, #registrationForm, .hero {
                display: none !important;
            }

            body {
                background: white;
            }

            .card-custom {
                box-shadow: none;
            }
        }
    </style>
</head>

<body>

<div class="hero">
    <h1>🎓 Student Registration System</h1>
    <p class="mb-0">Student Information Management</p>
</div>

<div class="container container-main">

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger" role="alert">
        <strong>⚠️ Please correct the following errors:</strong>
        <ul class="mb-0 mt-2">
            <?php foreach ($errors as $message): ?>
                <li><?= e($message) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if ($student === null): ?>

<form method="POST" enctype="multipart/form-data" id="registrationForm">

    <!-- PERSONAL INFORMATION -->
    <div class="card-custom">
        <h4 class="section-title">👤 Personal Information</h4>

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Full Name <span class="required">*</span></label>
                <input type="text" name="name" id="name"
                       class="form-control"
                       value="<?= old('name') ?>"
                       pattern="[\p{L}\s]+"
                       title="Letters and spaces only"
                       required>
                <?= errorMsg("name", $errors) ?>
            </div>

            <div class="col-md-6">
                <label class="form-label">Place of Birth (POB) <span class="required">*</span></label>
                <input type="text" name="pob" class="form-control"
                       value="<?= old('pob') ?>" required>
                <?= errorMsg("pob", $errors) ?>
            </div>

            <div class="col-md-6">
                <label class="form-label">Date of Birth <span class="required">*</span></label>
                <input type="date" name="dob" class="form-control"
                       max="<?= date('Y-m-d') ?>"
                       value="<?= old('dob') ?>" required>
                <?= errorMsg("dob", $errors) ?>
            </div>

            <div class="col-md-6">
                <label class="form-label">Gender <span class="required">*</span></label>
                <select name="gender" class="form-select" required>
                    <option value="">Select Gender</option>
                    <option value="Male" <?= old('gender') === "Male" ? "selected" : "" ?>>Male</option>
                    <option value="Female" <?= old('gender') === "Female" ? "selected" : "" ?>>Female</option>
                </select>
                <?= errorMsg("gender", $errors) ?>
            </div>

            <div class="col-md-6">
                <label class="form-label">Address <span class="required">*</span></label>
                <input type="text" name="address" class="form-control"
                       value="<?= old('address') ?>" required>
                <?= errorMsg("address", $errors) ?>
            </div>

            <div class="col-md-6">
                <label class="form-label">Telephone <span class="required">*</span></label>
                <input type="tel" name="telephone" class="form-control"
                       value="<?= old('telephone') ?>" required>
                <?= errorMsg("telephone", $errors) ?>
            </div>

            <div class="col-md-6">
                <label class="form-label">Email <span class="required">*</span></label>
                <input type="email" name="email" class="form-control"
                       value="<?= old('email') ?>" required>
                <?= errorMsg("email", $errors) ?>
            </div>

            <div class="col-md-6">
                <label class="form-label">Student Image <span class="required">*</span></label>
                <input type="file" name="image" id="image"
                       class="form-control"
                       accept=".jpg,.jpeg,.png,.gif,image/jpeg,image/png,image/gif"
                       required>
                <small class="text-muted">JPG, JPEG, PNG or GIF — Maximum 2 MB</small>
                <?= errorMsg("image", $errors) ?>
            </div>

        </div>
    </div>

    <!-- PARENT INFORMATION -->
    <div class="card-custom">
        <h4 class="section-title">👨‍👩‍👦 Parent Information</h4>

        <div class="row g-3">

            <div class="col-md-4">
                <label class="form-label">Parent Name <span class="required">*</span></label>
                <input type="text" name="parent_name" class="form-control"
                       value="<?= old('parent_name') ?>" required>
                <?= errorMsg("parent_name", $errors) ?>
            </div>

            <div class="col-md-4">
                <label class="form-label">Parent Telephone <span class="required">*</span></label>
                <input type="tel" name="parent_telephone" class="form-control"
                       value="<?= old('parent_telephone') ?>" required>
                <?= errorMsg("parent_telephone", $errors) ?>
            </div>

            <div class="col-md-4">
                <label class="form-label">Relationship <span class="required">*</span></label>
                <select name="relationship" class="form-select" required>
                    <option value="">Select Relationship</option>
                    <?php foreach (["Father", "Mother", "Guardian", "Other"] as $rel): ?>
                        <option value="<?= e($rel) ?>"
                            <?= old('relationship') === $rel ? "selected" : "" ?>>
                            <?= e($rel) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?= errorMsg("relationship", $errors) ?>
            </div>

        </div>
    </div>

    <!-- ACADEMIC INFORMATION -->
    <div class="card-custom">
        <h4 class="section-title">🎓 Academic Information</h4>

        <div class="row g-3">

            <div class="col-md-6">
                <label class="form-label">Student ID <span class="required">*</span></label>
                <input type="text" name="student_id" class="form-control"
                       value="<?= old('student_id') ?>" required>
                <?= errorMsg("student_id", $errors) ?>
            </div>

            <div class="col-md-6">
                <label class="form-label">Faculty <span class="required">*</span></label>
                <input type="text" name="faculty" class="form-control"
                       value="<?= old('faculty') ?>" required>
                <?= errorMsg("faculty", $errors) ?>
            </div>

            <div class="col-md-6">
                <label class="form-label">Department <span class="required">*</span></label>
                <input type="text" name="department" class="form-control"
                       value="<?= old('department') ?>" required>
                <?= errorMsg("department", $errors) ?>
            </div>

            <div class="col-md-6">
                <label class="form-label">Class <span class="required">*</span></label>
                <input type="text" name="class" class="form-control"
                       value="<?= old('class') ?>" required>
                <?= errorMsg("class", $errors) ?>
            </div>

            <div class="col-md-6">
                <label class="form-label">Registration Date <span class="required">*</span></label>
                <input type="date" name="registration_date" class="form-control"
                       value="<?= old('registration_date') ?: date('Y-m-d') ?>"
                       required>
                <?= errorMsg("registration_date", $errors) ?>
            </div>

        </div>
    </div>

    <div class="text-center mb-5">
        <button type="submit" class="btn btn-register">
            ✅ Register Student
        </button>

        <button type="reset" class="btn btn-secondary ms-2">
            Clear Form
        </button>
    </div>

</form>

<?php else: ?>

    <!-- SUCCESSFUL REGISTRATION -->
    <div class="card-custom">

        <div class="text-center">
            <div class="alert alert-success">
                <h4>✅ Registration Successful!</h4>
                <p class="mb-0">Student information has been submitted successfully.</p>
            </div>

            <img src="<?= e($student['image']) ?>"
                 class="student-photo"
                 alt="Student Image">

            <h3 class="mt-3"><?= e($student["name"]) ?></h3>

            <span class="badge bg-success fs-6">
                Student ID: <?= e($student["student_id"]) ?>
            </span>
        </div>

        <h5 class="result-title">👤 Personal Information</h5>

        <div class="table-responsive mt-3">
            <table class="table table-bordered table-striped">
                <tr><th>Full Name</th><td><?= e($student["name"]) ?></td></tr>
                <tr><th>Place of Birth</th><td><?= e($student["pob"]) ?></td></tr>
                <tr><th>Date of Birth</th><td><?= e($student["dob"]) ?></td></tr>
                <tr><th>Gender</th><td><?= e($student["gender"]) ?></td></tr>
                <tr><th>Address</th><td><?= e($student["address"]) ?></td></tr>
                <tr><th>Telephone</th><td><?= e($student["telephone"]) ?></td></tr>
                <tr><th>Email</th><td><?= e($student["email"]) ?></td></tr>
            </table>
        </div>

        <h5 class="result-title">👨‍👩‍👦 Parent Information</h5>

        <div class="table-responsive mt-3">
            <table class="table table-bordered table-striped">
                <tr><th>Parent Name</th><td><?= e($student["parent_name"]) ?></td></tr>
                <tr><th>Parent Telephone</th><td><?= e($student["parent_telephone"]) ?></td></tr>
                <tr><th>Relationship</th><td><?= e($student["relationship"]) ?></td></tr>
            </table>
        </div>

        <h5 class="result-title">🎓 Academic Information</h5>

        <div class="table-responsive mt-3">
            <table class="table table-bordered table-striped">
                <tr><th>Student ID</th><td><?= e($student["student_id"]) ?></td></tr>
                <tr><th>Faculty</th><td><?= e($student["faculty"]) ?></td></tr>
                <tr><th>Department</th><td><?= e($student["department"]) ?></td></tr>
                <tr><th>Class</th><td><?= e($student["class"]) ?></td></tr>
                <tr><th>Registration Date</th><td><?= e($student["registration_date"]) ?></td></tr>
            </table>
        </div>

        <div class="text-center mt-4 no-print">
            <button onclick="window.print()" class="btn btn-primary">
                🖨️ Print Information
            </button>

            <a href="<?= e(strtok($_SERVER["REQUEST_URI"], '?')) ?>"
               class="btn btn-success">
                ➕ Register Another Student
            </a>
        </div>

    </div>

<?php endif; ?>

</div>

<script>
document.getElementById("registrationForm")?.addEventListener("submit", function(event) {
    const name = document.getElementById("name").value.trim();
    const image = document.getElementById("image");

    if (!/^[\p{L}\s]+$/u.test(name)) {
        event.preventDefault();
        alert("Student name must contain letters and spaces only.");
        document.getElementById("name").focus();
        return;
    }

    if (image.files.length > 0) {
        const file = image.files[0];
        const allowedTypes = ["image/jpeg", "image/png", "image/gif"];

        if (!allowedTypes.includes(file.type)) {
            event.preventDefault();
            alert("Please upload JPG, JPEG, PNG, or GIF image.");
            image.focus();
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            event.preventDefault();
            alert("Image must not exceed 2 MB.");
            image.focus();
        }
    }
});
</script>

</body>
</html>
```