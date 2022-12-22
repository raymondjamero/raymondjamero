<?php
    require '../config/config.php';
    require $paths['DATABASE_MODEL_PATH'] . 'POSITIONS.php';

    $studentid = $_POST['studentid'];

    $record = array_slice($_POST, 1);

    $query0 = "SELECT * FROM tblstudent WHERE studentid = $studentid AND votestatus = 1";

    try {
        if (mysqli_num_rows($conn->query($query0))) {
            header('location: ../../index.php?error=This student has already voted');
        }
    } catch (Exception $e) {
        header('location: ../../index.php?error=Something went wrong');
    }


    foreach($record as $position => $vote) {
        $position = str_replace("_", " ", $position);
        $query1 = "UPDATE tblposition SET votecount = votecount + 1 WHERE position = '$position'";
        try {
            
            $conn->query($query1);
        } catch (Exception $e) {
            header('location: ../../index.php?error=Something went wrong');
        }
        foreach($vote as $candidateid) {
            $query2 = "UPDATE tblcandidate SET votecount = votecount + 1 WHERE candidateid = $candidateid";
            try {

                $conn->query($query2);
            } catch (Exception $e) {
                header('location: ../../index.php?error=Something went wrong');
            }
        }
    }


    $query3 = "UPDATE tblstudent SET votestatus = 1 WHERE studentid = $studentid";

    try {
        $conn->query($query3);
        header('location: ../../index.php?message=Voted successfully');
    } catch (Exception $e) {
        header('location: ../../index.php?error=Something went wrong');
    }
    