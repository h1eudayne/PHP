<?php

require_once "util.php"; 
require_login();

// Handle POST request
if (isset($_POST['first_name']) && isset($_POST['last_name']) &&
    isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary'])) {

    $validation_result = validateProfile(
        $_POST['first_name'],
        $_POST['last_name'],
        $_POST['email'],
        $_POST['headline'],
        $_POST['summary']
    );

    if ($validation_result !== true) {
        flash_message($validation_result, "danger");
        header("Location: add.php");
        exit();
    }

    $pos_validation_result = validatePos();
    if ($pos_validation_result !== true) {
        flash_message($pos_validation_result, "danger");
        header("Location: add.php");
        exit();
    }

    $pdo->beginTransaction();
    try {
        $stmt = $pdo->prepare('INSERT INTO Profile
            (user_id, first_name, last_name, email, headline, summary)
        VALUES ( :uid, :fn, :ln, :em, :he, :su)');
        $stmt->execute(array(
            ':uid' => $_SESSION['user_id'],
            ':fn' => $_POST['first_name'],
            ':ln' => $_POST['last_name'],
            ':em' => $_POST['email'],
            ':he' => $_POST['headline'],
            ':su' => $_POST['summary'])
        );
        $profile_id = $pdo->lastInsertId(); 

        insertPositions($pdo, $profile_id);

        $pdo->commit();
        flash_message("Profile added successfully!", "success");
        header("Location: index.php");
        exit();

    } catch (PDOException $e) {
        $pdo->rollBack(); 
        error_log("Error inserting profile/positions: " . $e->getMessage());
        flash_message("An error occurred while adding the profile. Please try again.", "danger");
        header("Location: add.php");
        exit();
    }
}

if (isset($_POST['cancel'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php
    $pageTitle = "d2334fbc"; 
    require_once "head.php"; 
    ?>
</head>
<body>
<div class="container">
    <h1>Adding Profile for UMSI</h1>
    <?php display_flash_message(); ?>

    <form method="POST">
        <p>First Name:
            <input type="text" name="first_name" id="first_name" size="60" value="<?= htmlentities($_POST['first_name'] ?? '') ?>" class="form-control"></p>
        <p>Last Name:
            <input type="text" name="last_name" id="last_name" size="60" value="<?= htmlentities($_POST['last_name'] ?? '') ?>" class="form-control"></p>
        <p>Email:
            <input type="text" name="email" id="email" size="30" value="<?= htmlentities($_POST['email'] ?? '') ?>" class="form-control"></p>
        <p>Headline:<br>
            <input type="text" name="headline" id="headline" size="80" value="<?= htmlentities($_POST['headline'] ?? '') ?>" class="form-control"></p>
        <p>Summary:<br>
            <textarea name="summary" id="summary" rows="8" cols="80" class="form-control"><?= htmlentities($_POST['summary'] ?? '') ?></textarea>
        </p>

        <p>
            Position: <input type="button" id="add_position" value="+" class="btn btn-success btn-xs">
        </p>
        <div id="position_fields">
            <?php
            $pos_count_for_js = 0; 

            if (isset($_POST['year1']) || isset($_POST['desc1'])) {
                for ($i = 1; $i <= 9; $i++) {
                    if (isset($_POST['year' . $i]) || isset($_POST['desc' . $i])) {
                        $year = htmlentities($_POST['year' . $i] ?? '');
                        $desc = htmlentities($_POST['desc' . $i] ?? '');
                        echo <<<EOL
                        <div id="position{$i}">
                            <p>Year: <input type="text" name="year{$i}" value="{$year}" class="form-control" style="width: auto; display: inline-block;">
                            <input type="button" value="-" onclick="$('#position{$i}').remove(); return false;" class="btn btn-danger btn-xs"></p>
                            <textarea name="desc{$i}" rows="8" cols="80" class="form-control">{$desc}</textarea>
                        </div>
                        EOL;
                        $pos_count_for_js = $i; 
                    }
                }
            }
            ?>
        </div>
        
        <p>
            <input type="submit" value="Add" class="btn btn-primary">
            <input type="submit" name="cancel" value="Cancel" class="btn btn-default">
        </p>
    </form>
</div>

<script>
    countPos = <?= $pos_count_for_js ?>;

    $(document).ready(function() {
        $('#add_position').click(function(event) {
            event.preventDefault(); 
            if (countPos >= 9) {
                alert("Maximum of nine position entries exceeded");
                return;
            }
            countPos++;
            $('#position_fields').append(
                '<div id="position' + countPos + '"> \
                    <p>Year: <input type="text" name="year' + countPos + '" value="" class="form-control" style="width: auto; display: inline-block;"> \
                    <input type="button" value="-" onclick="$(\'#position' + countPos + '\').remove(); return false;" class="btn btn-danger btn-xs"></p> \
                    <textarea name="desc' + countPos + '" rows="8" cols="80" class="form-control"></textarea> \
                </div>');
        });
    });
</script>
</body>
</html>
