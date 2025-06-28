<?php
require_once "pdo.php";
require_once "util.php"; 

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please log in to add a profile.";
    header('Location: login.php');
    return;
}

if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) &&
    isset($_POST['headline']) && isset($_POST['summary'])) {

    if (validateProfile(
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['email'],
            $_POST['headline'],
            $_POST['summary']
        ) === false) {
        header("Location: add.php");
        return;
    }

    if (validatePos() === false) {
        header("Location: add.php");
        return;
    }

    if (validateFields("edu_year", "edu_school", "Education") === false) {
        header("Location: add.php");
        return;
    }

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare('INSERT INTO Profile
            (user_id, first_name, last_name, email, headline, summary)
            VALUES (:uid, :fn, :ln, :em, :he, :su)');
        $stmt->execute(array(
            ':uid' => $_SESSION['user_id'],
            ':fn' => $_POST['first_name'],
            ':ln' => $_POST['last_name'],
            ':em' => $_POST['email'],
            ':he' => $_POST['headline'],
            ':su' => $_POST['summary']
        ));
        $profile_id = $pdo->lastInsertId();

        insertPosition($pdo, $profile_id); // Changed to insertPosition based on user's util.php

        insertEducation($pdo, $profile_id); // Changed to insertEducation based on user's util.php

        $pdo->commit();

        $_SESSION['success'] = "Profile added";
        header("Location: index.php");
        return;

    } catch (PDOException $e) {
        $pdo->rollBack();
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        error_log("Error adding profile: " . $e->getMessage());
        header("Location: add.php");
        return;
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dr. Charles Severance's Resume Registry</title>
    <?php require_once "head.php"; ?>
</head>
<body>
<div class="container">
    <h1>Adding Profile for <?= htmlentities($_SESSION['name']); ?></h1>
    <?php flashMessages(); ?>
    <form method="post">
        <p>
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" id="first_name" size="60" class="form-control" style="width: auto;">
        </p>
        <p>
            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" id="last_name" size="60" class="form-control" style="width: auto;">
        </p>
        <p>
            <label for="email">Email:</label>
            <input type="text" name="email" id="email" size="30" class="form-control" style="width: auto;">
        </p>
        <p>
            <label for="headline">Headline:</label>
            <input type="text" name="headline" id="headline" size="80" class="form-control" style="width: auto;">
        </p>
        <p>
            <label for="summary">Summary:</label>
            <textarea name="summary" id="summary" rows="8" cols="80" class="form-control"></textarea>
        </p>

        <p>
            Education: <input type="submit" id="addEdu" value="+">
        </p>
        <div id="edu_fields">
        </div>

        <p>
            Position: <input type="submit" id="addPos" value="+">
        </p>
        <div id="position_fields">
        </div>

        <p>
            <input type="submit" value="Add" class="btn btn-primary">
            <input type="button" value="Cancel" onclick="location.href='index.php'; return false;" class="btn btn-secondary">
        </p>
    </form>
</div>
<script>
    countPos = 0;
    countEdu = 0;

    function addPositionField() {
        if (countPos >= 9) {
            alert("Maximum of nine position entries exceeded");
            return;
        }
        countPos++;
        $('#position_fields').append(
            '<div id="position' + countPos + '">' +
            '<p>Year: <input type="text" name="year' + countPos + '" value="" class="form-control" style="width: auto; display: inline-block;"></p>' +
            '<textarea name="desc' + countPos + '" rows="8" cols="80" class="form-control"></textarea>' +
            '<input type="button" value="-" onclick="$(\'#position' + countPos + '\').remove(); countPos--; return false;" class="btn btn-danger btn-sm">' +
            '</div>'
        );
    }

    function addEducationField() {
        if (countEdu >= 9) {
            alert("Maximum of nine education entries exceeded");
            return;
        }
        countEdu++;
        $('#edu_fields').append(
            '<div id="edu' + countEdu + '">' +
            '<p>Year: <input type="text" name="edu_year' + countEdu + '" value="" class="form-control" style="width: auto; display: inline-block;">' +
            '<input type="button" value="-" onclick="$(\'#edu' + countEdu + '\').remove(); countEdu--; return false;" class="btn btn-danger btn-sm">' +
            '</p>' +
            '<p>School: <input type="text" size="80" name="edu_school' + countEdu + '" class="school form-control" style="width: auto; display: inline-block;" value="" /></p>' +
            '</div>'
        );

        $('.school').autocomplete({
            source: "school.php"
        });
    }

    $(document).ready(function () {
        $('#addPos').click(function (event) {
            event.preventDefault(); 
        });

        $('#addEdu').click(function (event) {
            event.preventDefault(); 
            addEducationField();
        });
    });
</script>
</body>
</html>
