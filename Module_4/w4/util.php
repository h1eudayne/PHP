<?php
function flashMessages() {
    if (isset($_SESSION['error'])) {
        echo('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");
        unset($_SESSION['error']);
    }
    if (isset($_SESSION['success'])) {
        echo('<p style="color: green;">' . htmlentities($_SESSION['success']) . "</p>\n");
        unset($_SESSION['success']);
    }
}

function validateProfile($firstName, $lastName, $email, $headline, $summary) {
    if (strlen($firstName) == 0 || strlen($lastName) == 0 ||
        strlen($email) == 0 || strlen($headline) == 0 ||
        strlen($summary) == 0) {
        $_SESSION["error"] = "All fields are required";
        return false;
    }
    if (strpos($email, '@') === false) {
        $_SESSION["error"] = "Email address must contain @";
        return false;
    }
    return true; 
}

function validatePos() {
    for ($i = 1; $i <= 9; $i++) {
        if (!isset($_POST['year' . $i])) continue;
        if (!isset($_POST['desc' . $i])) continue;

        $year = $_POST['year' . $i];
        $desc = $_POST['desc' . $i];

        if (strlen($year) == 0 || strlen($desc) == 0) {
            $_SESSION["error"] = "All fields are required";
            return false;
        }
        if (!is_numeric($year)) {
            $_SESSION["error"] = "Position year must be numeric";
            return false;
        }
    }
    return true; 
}

function validateEdu() {
    return validateFields("edu_year", "edu_school", "Education");
}

function validateFields($yearField, $otherField, $posOrEdu) {
    for ($i = 1; $i <= 9; $i++) {
        if (!isset($_POST[$yearField . $i])) continue;
        if (!isset($_POST[$otherField . $i])) continue;

        $year = $_POST[$yearField . $i];
        $other = $_POST[$otherField . $i];

        if (strlen($year) == 0 || strlen($other) == 0) {
            $_SESSION["error"] = "All fields are required";
            return false;
        }
        if (!is_numeric($year)) {
            $_SESSION["error"] = $posOrEdu . " year must be numeric";
            return false;
        }
    }
    return true;
}


function insertPosition($pdo, $profile_id) {
    $rank = 1;
    for ($i = 1; $i <= 9; $i++) {
        if (!isset($_POST['year' . $i])) continue;
        if (!isset($_POST['desc' . $i])) continue;

        $year = $_POST['year' . $i];
        $desc = $_POST['desc' . $i];

        $stmt = $pdo->prepare('INSERT INTO Position
            (profile_id, rank, year, description)
            VALUES (:pid, :rank, :year, :descr)');
        $stmt->execute(array(
            ':pid' => $profile_id,
            ':rank' => $rank,
            ':year' => $year,
            ':descr' => $desc
        ));
        $rank++;
    }
}

function insertEducation($pdo, $profile_id) {
    $rank = 1;
    for ($i = 1; $i <= 9; $i++) {
        if (!isset($_POST['edu_year' . $i])) continue;
        if (!isset($_POST['edu_school' . $i])) continue;

        $year = $_POST['edu_year' . $i];
        $school = $_POST['edu_school' . $i];

        $institution_id = false;
        $stmt = $pdo->prepare('SELECT institution_id FROM Institution WHERE name = :name');
        $stmt->execute(array(':name' => $school));
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row !== false) {
            $institution_id = $row['institution_id'];
        }

        if ($institution_id === false) {
            $stmt = $pdo->prepare('INSERT INTO Institution (name) VALUES (:name)');
            $stmt->execute(array(':name' => $school));
            $institution_id = $pdo->lastInsertId();
        }

        $stmt = $pdo->prepare('INSERT INTO Education
            (profile_id, institution_id, rank, year)
            VALUES (:pid, :iid, :rank, :year)');
        $stmt->execute(array(
            ':pid' => $profile_id,
            ':iid' => $institution_id,
            ':rank' => $rank,
            ':year' => $year
        ));
        $rank++;
    }
}

function loadPos($pdo, $profile_id) {
    $stmt = $pdo->prepare('SELECT * FROM Position WHERE profile_id = :pid ORDER BY rank');
    $stmt->execute(array(':pid' => $profile_id));
    $positions = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $positions;
}

function loadEdu($pdo, $profile_id) {
    $stmt = $pdo->prepare('SELECT year, name FROM Education JOIN Institution
        ON Education.institution_id = Institution.institution_id
        WHERE Education.profile_id = :pid ORDER BY rank');
    $stmt->execute(array(':pid' => $profile_id));
    $educations = $stmt->fetchAll(PDO::FETCH_ASSOC);
    return $educations;
}

function loadProfile($pdo, $profile_id, $user_id) {
    $stmt = $pdo->prepare('SELECT * FROM Profile WHERE profile_id = :pid AND user_id = :uid');
    $stmt->execute(array(':pid' => $profile_id, ':uid' => $user_id));
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);
    return $profile;
}

function initSession($yearName, $otherName) {
    for ($i = 1; $i <= 9; $i++) {
        $year = $yearName . $i;
        $other = $otherName . $i;

        if (isset($_POST[$year]) && isset($_POST[$other])) {
            $_SESSION[$year] = $_POST[$year];
            $_SESSION[$other] = $_POST[$other];
        }
    }
}
?>
