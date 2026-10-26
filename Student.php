
<?php
$errMsg = [];
$success = false;
$data = [];

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $fullname = trim($_POST["fullname"] ?? "");
    $email    = trim($_POST["email"] ?? "");
    $pob      = trim($_POST["pob"] ?? "");
    $gender   = $_POST["gender"] ?? "";
    $faculty  = $_POST["faculty"] ?? "";
    $languages = $_POST["languages"] ?? [];
    $comment  = trim($_POST["comment"] ?? "");

    // Validation
    if ($fullname === "") {
        $errMsg[] = "Fullname is required.";
    }

    if ($email === "") {
        $errMsg[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errMsg[] = "Please enter a valid email address.";
    }

    // Prevent invalid values
    $allowedGenders = ["Male", "Female"];
    $allowedFaculties = [
        "Computer and IT",
        "Medicine and Health Science",
        "Engineering",
        "Business"
    ];
    $allowedLanguages = ["Somali", "English", "Arabic", "Chinese"];

    if (!in_array($gender, $allowedGenders, true)) {
        $gender = "";
    }

    if (!in_array($faculty, $allowedFaculties, true)) {
        $faculty = "";
    }

    if (!is_array($languages)) {
        $languages = [];
    }

    $languages = array_values(
        array_intersect($languages, $allowedLanguages)
    );

    if (empty($errMsg)) {
        $success = true;

        $data = [
            "fullname" => $fullname,
            "email" => $email,
            "pob" => $pob,
            "gender" => $gender,
            "faculty" => $faculty,
            "languages" => $languages,
            "comment" => $comment
        ];
    }
}

function e($value) {
    return htmlspecialchars(
        (string)$value,
        ENT_QUOTES,
        "UTF-8"
    );
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Student Registration</title>

<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background: linear-gradient(135deg, #e0eafc, #cfdef3);
    min-height: 100vh;
    padding: 35px 15px;
    color: #243247;
}

.container {
    max-width: 760px;
    margin: auto;
    background: white;
    border-radius: 18px;
    overflow: hidden;
    box-shadow: 0 12px 35px rgba(0,0,0,0.12);
}

.header {
    background: linear-gradient(135deg, #123c69, #2563eb);
    color: white;
    padding: 30px 25px;
    text-align: center;
}

.header h1 {
    font-size: 28px;
    margin-bottom: 8px;
}

.header p {
    color: #e0ecff;
    font-size: 14px;
}

.form-content {
    padding: 30px;
}

.section-title {
    color: #174ea6;
    font-size: 19px;
    margin-bottom: 22px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e7eef9;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.full-width {
    grid-column: 1 / -1;
}

label {
    display: block;
    margin-bottom: 8px;
    font-weight: bold;
    font-size: 14px;
}

input[type="text"],
input[type="email"],
select,
textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ccd6e3;
    border-radius: 8px;
    font-size: 14px;
    outline: none;
    background: #fbfdff;
}

input:focus,
select:focus,
textarea:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 3px rgba(37,99,235,0.12);
}

textarea {
    resize: vertical;
}

.options {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: center;
}

.options label {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    font-weight: normal;
    margin: 0;
    cursor: pointer;
}

input[type="radio"],
input[type="checkbox"] {
    accent-color: #2563eb;
}

.buttons {
    display: flex;
    gap: 12px;
    margin-top: 10px;
}

button {
    border: none;
    padding: 13px 22px;
    border-radius: 8px;
    font-size: 15px;
    font-weight: bold;
    cursor: pointer;
    transition: 0.2s;
}

.register-btn {
    background: #2563eb;
    color: white;
    flex: 1;
}

.register-btn:hover {
    background: #174ea6;
}

.clear-btn {
    background: #edf1f7;
    color: #334155;
}

.clear-btn:hover {
    background: #dbe3ee;
}

.message {
    margin-bottom: 22px;
    padding: 14px;
    border-radius: 8px;
    line-height: 1.8;
}

.error {
    color: #a61b1b;
    background: #fff0f0;
    border: 1px solid #fecaca;
}

.success {
    color: #166534;
    background: #ecfdf3;
    border: 1px solid #bbf7d0;
}

.result {
    margin-top: 25px;
    border: 1px solid #dce6f5;
    border-radius: 12px;
    overflow: hidden;
}

.result-header {
    background: #eff6ff;
    color: #174ea6;
    padding: 18px;
    font-size: 20px;
    font-weight: bold;
}

.result-body {
    padding: 20px;
}

.result-row {
    display: grid;
    grid-template-columns: 150px 1fr;
    gap: 12px;
    padding: 12px 0;
    border-bottom: 1px solid #edf0f5;
    overflow-wrap: anywhere;
}

.result-row:last-child {
    border-bottom: none;
}

.result-label {
    font-weight: bold;
    color: #52647a;
}

.footer {
    text-align: center;
    padding: 18px;
    color: #718096;
    font-size: 12px;
    background: #f8fafc;
}

@media (max-width: 600px) {
    .form-grid {
        grid-template-columns: 1fr;
        gap: 0;
    }

    .full-width {
        grid-column: auto;
    }

    .form-content {
        padding: 22px 18px;
    }

    .header h1 {
        font-size: 23px;
    }

    .result-row {
        grid-template-columns: 1fr;
        gap: 5px;
    }
}
</style>
</head>

<body>

<div class="container">

    <div class="header">
        <h1>🎓 Student Registration</h1>
        <p>Enter your information to register as a student</p>
    </div>

    <div class="form-content">

        <?php if (!empty($errMsg)): ?>
            <div class="message error">
                <strong>⚠ Please correct the following:</strong>
                <ul>
                    <?php foreach ($errMsg as $error): ?>
                        <li><?= e($error) ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="message success">
                ✅ Student registered successfully!
            </div>
        <?php endif; ?>

        <form method="post" action="">

            <h2 class="section-title">Personal Information</h2>

            <div class="form-grid">

                <div class="form-group">
                    <label for="fullname">Fullname *</label>
                    <input
                        type="text"
                        id="fullname"
                        name="fullname"
                        placeholder="Enter your full name"
                        value="<?= e($_POST["fullname"] ?? "") ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="email">Email Address *</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="example@email.com"
                        value="<?= e($_POST["email"] ?? "") ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="pob">Place of Birth</label>
                    <input
                        type="text"
                        id="pob"
                        name="pob"
                        placeholder="Enter place of birth"
                        value="<?= e($_POST["pob"] ?? "") ?>"
                    >
                </div>

                <div class="form-group">
                    <label>Gender</label>
                    <div class="options">
                        <label>
                            <input type="radio" name="gender"
                                value="Male"
                                <?= (($_POST["gender"] ?? "") === "Male") ? "checked" : "" ?>>
                            Male
                        </label>

                        <label>
                            <input type="radio" name="gender"
                                value="Female"
                                <?= (($_POST["gender"] ?? "") === "Female") ? "checked" : "" ?>>
                            Female
                        </label>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="faculty">Faculty</label>
                    <select id="faculty" name="faculty">
                        <option value="">-- Select Faculty --</option>

                        <?php
                        $faculties = [
                            "Computer and IT",
                            "Medicine and Health Science",
                            "Engineering",
                            "Business"
                        ];

                        foreach ($faculties as $f):
                        ?>
                            <option value="<?= e($f) ?>"
                                <?= (($_POST["faculty"] ?? "") === $f) ? "selected" : "" ?>>
                                <?= e($f) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group full-width">
                    <label>Languages</label>

                    <div class="options">
                        <?php
                        $allLanguages = [
                            "Somali", "English", "Arabic", "Chinese"
                        ];

                        $selectedLanguages = $_POST["languages"] ?? [];

                        if (!is_array($selectedLanguages)) {
                            $selectedLanguages = [];
                        }

                        foreach ($allLanguages as $language):
                        ?>
                            <label>
                                <input
                                    type="checkbox"
                                    name="languages[]"
                                    value="<?= e($language) ?>"
                                    <?= in_array($language, $selectedLanguages, true) ? "checked" : "" ?>
                                >
                                <?= e($language) ?>
                            </label>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="form-group full-width">
                    <label for="comment">Comment</label>
                    <textarea
                        id="comment"
                        name="comment"
                        rows="4"
                        placeholder="Write your comment here..."
                    ><?= e($_POST["comment"] ?? "") ?></textarea>
                </div>

            </div>

            <div class="buttons">
                <button type="submit" class="register-btn">
                    ✓ Register Student
                </button>

                <button type="reset" class="clear-btn">
                    Clear
                </button>
            </div>

        </form>

        <?php if ($success): ?>

            <div class="result">

                <div class="result-header">
                    📋 Student Information
                </div>

                <div class="result-body">

                    <div class="result-row">
                        <span class="result-label">Fullname</span>
                        <span><?= e($data["fullname"]) ?></span>
                    </div>

                    <div class="result-row">
                        <span class="result-label">Email</span>
                        <span><?= e($data["email"]) ?></span>
                    </div>

                    <div class="result-row">
                        <span class="result-label">Place of Birth</span>
                        <span><?= e($data["pob"] ?: "Not provided") ?></span>
                    </div>

                    <div class="result-row">
                        <span class="result-label">Gender</span>
                        <span><?= e($data["gender"] ?: "Not selected") ?></span>
                    </div>

                    <div class="result-row">
                        <span class="result-label">Faculty</span>
                        <span><?= e($data["faculty"] ?: "Not selected") ?></span>
                    </div>

                    <div class="result-row">
                        <span class="result-label">Languages</span>
                        <span>
                            <?= e($data["languages"] ? implode(", ", $data["languages"]) : "None selected") ?>
                        </span>
                    </div>

                    <div class="result-row">
                        <span class="result-label">Comment</span>
                        <span><?= e($data["comment"] ?: "No comment") ?></span>
                    </div>

                </div>
            </div>

        <?php endif; ?>

    </div>

    <div class="footer">
        Student Registration System | PHP Project
    </div>

</div>

</body>
</html>
