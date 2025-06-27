<?php
// edit.php - Edit Profile with Education
require_once "pdo.php";
require_once "util.php";

// session_start(); // Removed: Session is already started in pdo.php

// Check if the user is logged in, otherwise redirect to login.php
if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Please log in to edit a profile.";
    header('Location: login.php');
    return;
}

// Handle POST request for updating profile
if (isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) &&
    isset($_POST['headline']) && isset($_POST['summary']) && isset($_POST['profile_id'])) {

    // Validate profile data
    if (validateProfile(
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['email'],
            $_POST['headline'],
            $_POST['summary']
        ) === false) {
        header("Location: edit.php?profile_id=" . $_POST['profile_id']);
        return;
    }

    // Validate position data
    if (validatePos() === false) {
        header("Location: edit.php?profile_id=" . $_POST['profile_id']);
        return;
    }

    // Validate education data
    if (validateFields("edu_year", "edu_school", "Education") === false) {
        header("Location: edit.php?profile_id=" . $_POST['profile_id']);
        return;
    }

    try {
        // Start a transaction for atomicity
        $pdo->beginTransaction();

        // Update Profile data
        $stmt = $pdo->prepare('UPDATE Profile SET
            first_name = :fn, last_name = :ln, email = :em,
            headline = :he, summary = :su
            WHERE profile_id = :pid AND user_id = :uid');
        $stmt->execute(array(
            ':fn' => $_POST['first_name'],
            ':ln' => $_POST['last_name'],
            ':em' => $_POST['email'],
            ':he' => $_POST['headline'],
            ':su' => $_POST['summary'],
            ':pid' => $_POST['profile_id'], // Use $_POST['profile_id'] for update
            ':uid' => $_SESSION['user_id']
        ));

        // Clear old position data for this profile
        $stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id = :pid');
        $stmt->execute(array(':pid' => $_POST['profile_id']));

        // Insert new position data
        insertPosition($pdo, $_POST['profile_id']);

        // Clear old education data for this profile
        $stmt = $pdo->prepare('DELETE FROM Education WHERE profile_id = :pid');
        $stmt->execute(array(':pid' => $_POST['profile_id']));

        // Insert new education data
        insertEducation($pdo, $_POST['profile_id']);

        // Commit the transaction
        $pdo->commit();

        $_SESSION['success'] = "Profile updated";
        header("Location: index.php");
        return;

    } catch (PDOException $e) {
        // Rollback on error
        $pdo->rollBack();
        $_SESSION['error'] = "Database error: " . $e->getMessage();
        error_log("Error updating profile: " . $e->getMessage());
        header("Location: edit.php?profile_id=" . $_POST['profile_id']);
        return;
    }
}

// Handle Cancel button
if (isset($_POST["cancel"])) {
    header("Location: index.php");
    return;
}

// Check if profile_id is set in GET request
if (!isset($_GET['profile_id'])) {
    $_SESSION['error'] = "Missing profile_id";
    header('Location: index.php');
    return;
}

try {
    // Fetch profile data for pre-population
    $stmt = $pdo->prepare('SELECT * FROM Profile WHERE profile_id = :pid AND user_id = :uid');
    $stmt->execute(array(
        ':pid' => $_GET['profile_id'],
        ':uid' => $_SESSION['user_id']
    ));
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);

    // If profile not found or not owned by user, redirect
    if ($profile === false) {
        $_SESSION['error'] = "Could not load profile or unauthorized access.";
        header('Location: index.php');
        return;
    }

    // Load existing positions for pre-population
    $positions = loadPos($pdo, $_GET['profile_id']);
    $countPos = count($positions);

    // Load existing education entries for pre-population
    $educations = loadEdu($pdo, $_GET['profile_id']);
    $countEdu = count($educations);

} catch (PDOException $e) {
    $_SESSION['error'] = "Database error: " . $e->getMessage();
    error_log("Error loading profile for edit: " . $e->getMessage());
    header('Location: index.php');
    return;
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
    <h1>Editing Profile for <?= htmlentities($profile['first_name'] . ' ' . $profile['last_name']); ?></h1>
    <?php flashMessages(); ?>
    <form method="post">
        <p>
            <label for="first_name">First Name:</label>
            <input type="text" name="first_name" id="first_name" size="60" class="form-control" style="width: auto;"
                   value="<?= htmlentities($profile['first_name']); ?>">
        </p>
        <p>
            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" id="last_name" size="60" class="form-control" style="width: auto;"
                   value="<?= htmlentities($profile['last_name']); ?>">
        </p>
        <p>
            <label for="email">Email:</label>
            <input type="text" name="email" id="email" size="30" class="form-control" style="width: auto;"
                   value="<?= htmlentities($profile['email']); ?>">
        </p>
        <p>
            <label for="headline">Headline:</label>
            <input type="text" name="headline" id="headline" size="80" class="form-control" style="width: auto;"
                   value="<?= htmlentities($profile['headline']); ?>">
        </p>
        <p>
            <label for="summary">Summary:</label>
            <textarea name="summary" id="summary" rows="8" cols="80"
                      class="form-control"><?= htmlentities($profile['summary']); ?></textarea>
        </p>

        <!-- Education Section -->
        <p>
            Education: <input type="submit" id="addEdu" value="+">
        </p>
        <div id="edu_fields">
            <?php
            // Pre-populate existing education entries
            foreach ($educations as $rank_idx => $edu) {
                // Use $rank_idx + 1 for the actual rank and ID in HTML
                $current_edu_rank = $rank_idx + 1;
                echo '<div id="edu' . $current_edu_rank . '">' . "\n";
                echo '<p>Year: <input type="text" name="edu_year' . $current_edu_rank . '" value="' . htmlentities($edu['year']) . '" class="form-control" style="width: auto; display: inline-block;">' . "\n";
                echo '<input type="button" value="-" onclick="$(\'#edu' . $current_edu_rank . '\').remove(); countEdu--; return false;" class="btn btn-danger btn-sm">' . "\n";
                echo '</p>' . "\n";
                echo '<p>School: <input type="text" size="80" name="edu_school' . $current_edu_rank . '" class="school form-control" style="width: auto; display: inline-block;" value="' . htmlentities($edu['name']) . '" /></p>' . "\n";
                echo '</div>' . "\n";
            }
            ?>
        </div>

        <!-- Position Section -->
        <p>
            Position: <input type="submit" id="addPos" value="+">
        </p>
        <div id="position_fields">
            <?php
            // Pre-populate existing position entries
            foreach ($positions as $rank_idx => $pos) {
                // Use $rank_idx + 1 for the actual rank and ID in HTML
                $current_pos_rank = $rank_idx + 1;
                echo '<div id="position' . $current_pos_rank . '">' . "\n";
                echo '<p>Year: <input type="text" name="year' . $current_pos_rank . '" value="' . htmlentities($pos['year']) . '" class="form-control" style="width: auto; display: inline-block;"></p>' . "\n";
                echo '<textarea name="desc' . $current_pos_rank . '" rows="8" cols="80" class="form-control">' . htmlentities($pos['description']) . '</textarea>' . "\n";
                echo '<input type="button" value="-" onclick="$(\'#position' . $current_pos_rank . '\').remove(); countPos--; return false;" class="btn btn-danger btn-sm">' . "\n";
                echo '</div>' . "\n";
            }
            ?>
        </div>

        <p>
            <input type="hidden" name="profile_id" value="<?= htmlentities($_GET['profile_id']); ?>">
            <input type="submit" value="Save" class="btn btn-primary">
            <input type="button" value="Cancel" onclick="location.href='index.php'; return false;" class="btn btn-secondary">
        </p>
    </form>
</div>
<script>
    countPos = <?= $countPos ?>; // Initialize countPos with existing positions
    countEdu = <?= $countEdu ?>; // Initialize countEdu with existing educations

    // Function to add a new position field
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

    // Function to add a new education field
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

        // Apply autocomplete to the newly added school field
        $('.school').autocomplete({
            source: "school.php"
        });
    }

    $(document).ready(function () {
        // Event listener for adding position fields
        $('#addPos').click(function (event) {
            event.preventDefault(); // Prevent form submission
            addPositionField();
        });

        // Event listener for adding education fields
        $('#addEdu').click(function (event) {
            event.preventDefault(); // Prevent form submission
            addEducationField();
        });

        // Apply autocomplete to all existing school fields on page load
        $('.school').autocomplete({
            source: "school.php"
        });
    });
</script>
</body>
</html>
