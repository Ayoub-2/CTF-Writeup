<!DOCTYPE html>
<html>

<head>
    <title>Awesome Note Keeping</title>
</head>

<body style="padding: 100px; background: #000000; color: #09b576">

    <h1>Welcome to Awesome Note Keeping</h1>
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    if (isset($_POST["note"]) && isset($_POST["note_title"])) {
        if (empty($_POST["note"]) || empty($_POST["note_title"])) {
            echo "All fields are required.";
        } else if (strlen($_POST["note_title"]) >= 13) {
            echo "Note title is too long.";
        } else if (strlen($_POST["note"]) >= 40) {
            echo "Note is too long.";
        } else {
            $note_title = str_replace("flag", "", $_POST["note_title"]);
            if (!empty($note_title)) {
                if (file_exists($note_title . ".txt")) {
                    echo "There is already a note with that title and the note is <br>";
                    $note_title = str_replace("flag", "", $note_title);
                    $myNote = fopen($note_title . ".txt", "r");
                    echo fread($myNote, filesize($note_title . ".txt"));
                    fclose($myNote);
                } else {
                    $myNote = fopen($note_title . ".txt", "w");
                    fwrite($myNote, $_POST["note"]);
                    fclose($myNote);
                    echo "Your note has been saved.";
                }
            } else {
                echo "Sorry ! You can't create flag note.";
            }
        }
    }


    if (isset($_GET["note_title"]) && !empty($_GET["note_title"]) && $_GET["note_title"] != "flag") {
        if (file_exists($_GET["note_title"] . ".txt")) {
            $myNote = fopen($_GET["note_title"] . ".txt", "r");
            echo fread($myNote, filesize($_GET["note_title"] . ".txt"));
            fclose($myNote);
        } else {
            echo "Sorry ! Couldn't find any note with that title.";
        }
    }

    ?>
    <br>
    <h5>Create a Note</h5>
    <form action="" method="POST">
        <table>
            <tr>
                <td><label>Note Title : </label></td>
                <td><input type="text" name="note_title" /></td>
            </tr>
            <tr>
                <td><label>Note : </label></td>
                <td><textarea name="note"></textarea></td>
            </tr>
        </table>
        <input type="submit" value="Save" />
    </form>

    <h5>Read a Note</h5>
    <form action="" method="GET">
        <table>
            <tr>
                <td><label>Note Title : </label></td>
                <td><input type="text" name="note_title" /></td>
            </tr>
        </table>
        <input type="submit" value="Read" />
    </form>
    <!-- Hi Seli, I have created this awesome note keeping web app today. I have saved a backup file index.php.bak for you. Download it and check it out.  -->
</body>

</html>