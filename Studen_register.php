<?php

function e($v) {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

$errors  = [];
$success = false;
$cvName  = "";
$cvPath  = "";

$old = [
    "fullname" => "", "email" => "", "pob" => "", "gender" => "",
    "faculty" => "", "languages" => [], "comment" => ""
];

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["submit"])) {

    $old["fullname"]  = trim($_POST["fullname"] ?? "");
    $old["email"]     = trim($_POST["email"] ?? "");
    $old["pob"]       = trim($_POST["pob"] ?? "");
    $old["gender"]    = $_POST["gender"] ?? "";
    $old["faculty"]   = $_POST["faculty"] ?? "";
    $old["languages"] = $_POST["languages"] ?? [];
    $old["comment"]   = trim($_POST["comment"] ?? "");

    // ---- Validation ----
    if ($old["fullname"] === "")
        $errors[] = "Please fill the Fullname field.";

    if ($old["email"] === "")
        $errors[] = "Please fill the Email field.";
    elseif (!filter_var($old["email"], FILTER_VALIDATE_EMAIL))
        $errors[] = "Email format is not valid.";

    // ---- CV upload validation ----
    $allowed = [
        "pdf"  => ["application/pdf"],
        "doc"  => ["application/msword", "application/octet-stream"],
        "docx" => [
            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",
            "application/zip"
        ],
    ];
    $maxSize = 2 * 1024 * 1024; // 2MB

    if (!isset($_FILES["cv"]) || $_FILES["cv"]["error"] === UPLOAD_ERR_NO_FILE) {
        $errors[] = "Please upload your CV.";
    } elseif ($_FILES["cv"]["error"] !== UPLOAD_ERR_OK) {
        $errors[] = "CV upload failed (error code " . (int)$_FILES["cv"]["error"] . ").";
    } else {
        $tmp  = $_FILES["cv"]["tmp_name"];
        $orig = $_FILES["cv"]["name"];
        $size = $_FILES["cv"]["size"];
        $ext  = strtolower(pathinfo($orig, PATHINFO_EXTENSION));

        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime  = $finfo->file($tmp);

        if (!isset($allowed[$ext]))
            $errors[] = "CV must be a PDF, DOC or DOCX file.";
        elseif (!in_array($mime, $allowed[$ext], true))
            $errors[] = "CV file content does not match its extension.";
        elseif ($size > $maxSize)
            $errors[] = "CV is too large. Maximum size is 2MB.";
    }

    // ---- Save file if everything is valid ----
    if (empty($errors)) {
        $uploadDir = __DIR__ . "/uploads/";
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $newName = uniqid("cv_", true) . "." . $ext;
        if (move_uploaded_file($tmp, $uploadDir . $newName)) {
            $success = true;
            $cvName  = $orig;
            $cvPath  = "uploads/" . $newName;
        } else {
            $errors[] = "Could not save the CV file. Check folder permissions.";
        }
    }
}

function checked($arr, $val) {
    return in_array($val, $arr, true) ? "checked" : "";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Registration</title>
    <style>
        :root {
            --primary: #6c5ce7;
            --primary-dark: #5441d6;
            --accent: #00cec9;
            --danger: #e74c3c;
            --success: #00b894;
            --text: #2d3436;
            --muted: #636e72;
            --border: #dfe6e9;
            --bg-card: #ffffff;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: "Segoe UI", Tahoma, Arial, sans-serif;
            background: linear-gradient(135deg, #6c5ce7 0%, #a29bfe 45%, #00cec9 100%);
            min-height: 100vh;
            padding: 30px 15px;
            color: var(--text);
        }

        .container {
            max-width: 640px;
            margin: 0 auto;
            background: var(--bg-card);
            border-radius: 18px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.25);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(90deg, var(--primary), var(--accent));
            color: #fff;
            padding: 28px 30px;
            text-align: center;
        }
        .header h1 { font-size: 1.7rem; margin-bottom: 4px; }
        .header p  { opacity: .9; font-size: .95rem; }

        form { padding: 28px 30px 32px; }

        fieldset {
            border: 2px solid var(--border);
            border-radius: 14px;
            padding: 22px 20px 8px;
        }
        legend {
            padding: 0 12px;
            font-weight: 700;
            color: var(--primary);
            font-size: 1.05rem;
        }

        .field { margin-bottom: 18px; }

        label.title {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            color: var(--text);
        }
        .req { color: var(--danger); }

        input[type="text"],
        input[type="email"],
        select,
        textarea {
            width: 100%;
            padding: 11px 14px;
            border: 2px solid var(--border);
            border-radius: 10px;
            font-size: .95rem;
            font-family: inherit;
            transition: border-color .2s, box-shadow .2s;
            background: #fafbfc;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(108, 92, 231, .15);
            background: #fff;
        }

        .options { display: flex; flex-wrap: wrap; gap: 10px; }
        .options label {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 14px;
            border: 2px solid var(--border);
            border-radius: 999px;
            cursor: pointer;
            font-size: .92rem;
            transition: all .2s;
            background: #fafbfc;
        }
        .options label:hover { border-color: var(--primary); }
        .options input { accent-color: var(--primary); }

        /* CV upload */
        .upload-box {
            border: 2px dashed var(--primary);
            background: rgba(108, 92, 231, .06);
            border-radius: 12px;
            padding: 18px;
            text-align: center;
            transition: background .2s;
        }
        .upload-box:hover { background: rgba(108, 92, 231, .12); }
        .upload-box input[type="file"] {
            width: 100%;
            font-size: .9rem;
            color: var(--muted);
        }
        .upload-box input[type="file"]::file-selector-button {
            background: var(--primary);
            color: #fff;
            border: none;
            padding: 9px 18px;
            border-radius: 8px;
            margin-right: 12px;
            cursor: pointer;
            font-weight: 600;
            transition: background .2s;
        }
        .upload-box input[type="file"]::file-selector-button:hover {
            background: var(--primary-dark);
        }
        .hint { font-size: .8rem; color: var(--muted); margin-top: 8px; }

        .buttons { display: flex; gap: 12px; margin: 8px 0 18px; }
        .btn {
            flex: 1;
            padding: 13px;
            border: none;
            border-radius: 10px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: transform .15s, box-shadow .2s;
        }
        .btn:hover { transform: translateY(-2px); }
        .btn-primary {
            background: linear-gradient(90deg, var(--primary), var(--accent));
            color: #fff;
            box-shadow: 0 6px 16px rgba(108, 92, 231, .4);
        }
        .btn-reset { background: #eef0f2; color: var(--text); }

        /* Messages */
        .alert-error {
            margin: 24px 30px 0;
            background: #fdecea;
            border-left: 5px solid var(--danger);
            color: #b03a2e;
            padding: 14px 18px;
            border-radius: 10px;
        }
        .alert-error li { margin-left: 18px; }

        .result {
            margin: 24px 30px 0;
            background: #e8faf5;
            border-left: 5px solid var(--success);
            padding: 20px;
            border-radius: 12px;
        }
        .result h2 { color: var(--success); margin-bottom: 12px; font-size: 1.25rem; }
        .result p  { margin-bottom: 6px; }
        .result strong { color: var(--primary-dark); }
        .result a {
            display: inline-block;
            margin-top: 8px;
            padding: 8px 16px;
            background: var(--primary);
            color: #fff;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
        }
        .result a:hover { background: var(--primary-dark); }
    </style>
</head>
<body>

<div class="container">

    <div class="header">
        <h1>🎓 Student Registration Form</h1>
        <p>Fill in your details and upload your CV</p>
    </div>

    <?php if (!empty($errors)): ?>
        <div class="alert-error">
            <strong>Please fix the following:</strong>
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= e($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if ($success): ?>
        <div class="result">
            <h2>✅ Registration Successful</h2>
            <p><strong>Fullname:</strong> <?= e($old["fullname"]) ?></p>
            <p><strong>Email:</strong> <?= e($old["email"]) ?></p>
            <p><strong>Place of Birth:</strong> <?= e($old["pob"]) ?></p>
            <?php if ($old["gender"] !== ""): ?>
                <p><strong>Gender:</strong> <?= e(ucfirst($old["gender"])) ?></p>
            <?php endif; ?>
            <p><strong>Faculty:</strong> <?= e($old["faculty"]) ?></p>
            <p><strong>Languages:</strong>
                <?= !empty($old["languages"]) ? e(implode(", ", $old["languages"])) : "None selected" ?>
            </p>
            <p><strong>Comment:</strong> <?= e($old["comment"]) ?></p>
            <p><strong>CV:</strong> <?= e($cvName) ?></p>
            <a href="<?= e($cvPath) ?>" target="_blank">📄 View CV</a>
        </div>
    <?php endif; ?>

    <!-- enctype is REQUIRED for file upload, and method must be POST -->
    <form method="post" action="" enctype="multipart/form-data">

        <fieldset>
            <legend>Personal Info</legend>

            <div class="field">
                <label class="title" for="fullname">Fullname <span class="req">*</span></label>
                <input type="text" id="fullname" name="fullname" value="<?= e($old["fullname"]) ?>">
            </div>

            <div class="field">
                <label class="title" for="email">Email <span class="req">*</span></label>
                <input type="email" id="email" name="email" value="<?= e($old["email"]) ?>">
            </div>

            <div class="field">
                <label class="title" for="pob">Place of Birth</label>
                <input type="text" id="pob" name="pob" value="<?= e($old["pob"]) ?>">
            </div>

            <div class="field">
                <label class="title">Gender</label>
                <div class="options">
                    <label><input type="radio" name="gender" value="male"   <?= $old["gender"] === "male" ? "checked" : "" ?>> Male</label>
                    <label><input type="radio" name="gender" value="female" <?= $old["gender"] === "female" ? "checked" : "" ?>> Female</label>
                </div>
            </div>

            <div class="field">
                <label class="title" for="faculty">Faculty</label>
                <select name="faculty" id="faculty">
                    <option value="Computer and IT"             <?= $old["faculty"] === "Computer and IT" ? "selected" : "" ?>>Computer and IT</option>
                    <option value="Medicine and Health Science" <?= $old["faculty"] === "Medicine and Health Science" ? "selected" : "" ?>>Medicine and Health Science</option>
                    <option value="Engineering"                 <?= $old["faculty"] === "Engineering" ? "selected" : "" ?>>Engineering</option>
                    <option value="Business"                    <?= $old["faculty"] === "Business" ? "selected" : "" ?>>Business</option>
                </select>
            </div>

            <div class="field">
                <label class="title">Languages</label>
                <div class="options">
                    <label><input type="checkbox" name="languages[]" value="Somali"  <?= checked($old["languages"], "Somali") ?>> Somali</label>
                    <label><input type="checkbox" name="languages[]" value="English" <?= checked($old["languages"], "English") ?>> English</label>
                    <label><input type="checkbox" name="languages[]" value="Arabic"  <?= checked($old["languages"], "Arabic") ?>> Arabic</label>
                    <label><input type="checkbox" name="languages[]" value="Chinese" <?= checked($old["languages"], "Chinese") ?>> Chinese</label>
                </div>
            </div>

            <div class="field">
                <label class="title" for="cv">Upload CV <span class="req">*</span></label>
                <div class="upload-box">
                    <input type="file" id="cv" name="cv" accept=".pdf,.doc,.docx">
                    <p class="hint">Allowed: PDF, DOC, DOCX &middot; Max size: 2MB</p>
                </div>
            </div>

            <div class="field">
                <label class="title" for="comment">Comment</label>
                <textarea name="comment" id="comment" rows="4"><?= e($old["comment"]) ?></textarea>
            </div>

            <div class="buttons">
                <input class="btn btn-primary" type="submit" name="submit" value="Register">
                <a href="" style="flex:1;text-decoration:none">
                    <input class="btn btn-reset" style="width:100%" type="button" value="Clear">
                </a>
            </div>

        </fieldset>
    </form>

</div>

</body>
</html>