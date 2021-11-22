<?php 
    include("./components/Session.php");
    if (!$signed_in_user_id) {exit;}

    $user_contacts = "";
    $user_recieved_messages = "";
    $user_sent_messages = "";

    $mysqli = new mysqli(
        "mysql1.cs.clemson.edu", 
        "CPSC4620MTb_8b5n", 
        "cpsc4620-metube", 
        "CPSC4620-MeTube_uk72"
    );

    // fetch contacts
    $stmt = $mysqli->prepare("SELECT Contact_ID from Contacts 
    WHERE User_ID=?") or die("Error: ".$mysqli->error);
    $stmt -> bind_param('s', $signed_in_user_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows != 0) {
        while ($row = $res->fetch_assoc()) {
            $contact_id = $row["Contact_ID"];
            $user_contacts = $user_contacts . "<option value='$contact_id'>$contact_id</option>";
        }
    }

    function render_message($recipient, $body, $id, $sender='', $r_id='') {
        $sender_html = "<div>From: <span class='font-weight-bold'>$sender</span></div>";
        if ($sender == '') {
            $sender_html = "";
        }
        return "
        <div id='$id' class='border-bottom pb-2'>
            <div>To: <span class='font-weight-bold'>$recipient</span></div>
            $sender_html
            <div>$body</div>
        </div>
        ";
    }

    // fetch sent messages
    $stmt = $mysqli->prepare("SELECT * from Messages 
    WHERE Sender_ID=?") or die("Error: ".$mysqli->error);
    $stmt -> bind_param('s', $signed_in_user_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows != 0) {
        while ($row = $res->fetch_assoc()) {
            $msg_sender = $row["Sender_ID"];
            $msg_recipient = $row["Recipient_ID"];
            $msg_body = $row["Message"];
            $msg_id = $row["Message_ID"];
            $user_sent_messages = $user_sent_messages 
            . render_message($msg_recipient, $msg_body, $msg_id);
        }
    }

    // fetch recieved messages
    $stmt = $mysqli->prepare("SELECT * from Messages 
    WHERE Recipient_ID=?") or die("Error: ".$mysqli->error);
    $stmt -> bind_param('s', $signed_in_user_id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows != 0) {
        while ($row = $res->fetch_assoc()) {
            $msg_sender = $row["Sender_ID"];
            $msg_recipient = $row["Recipient_ID"];
            $msg_body = $row["Message"];
            $msg_id = $row["Message_ID"];
            $user_recieved_messages = $user_recieved_messages 
            . render_message($msg_recipient, $msg_body, $msg_id, $msg_sender);
        }
    }

    if (isset($_POST["recipient"]) && isset($_POST["body"])) {
        $stmt = $mysqli->prepare("INSERT INTO Messages VALUES (?,?,?,?,?)")  
        or die("Error: ".$mysqli->error);
        $i = uniqid();
        $rid = '';
        $stmt -> bind_param('sssss', 
            $signed_in_user_id, 
            $_POST["recipient"], 
            $_POST["body"],
            $i,
            $rid);
        $stmt->execute();
        header("Location: ".$_SERVER["PHP_SELF"]);
        die();
    }
    $mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="style.css" /> 
    <title>MeTube Development</title>
</head>
<body>
    <?php include("./components/NavBar.php"); ?>
    <?php echo "<h3>$signed_in_user_id</h3>" ?>
    <div class="container">
        <div class="row">
            <div class="col">
                <h3>Inbox</h3>
                <div class="border border-primary p-3" style="max-height:500px; overflow-y:scroll">
                    <?php echo ($user_recieved_messages) ? $user_recieved_messages : "You have no messages."; ?>
                </div>
                <div class="border">
                    <h5>Compose Message</h5>
                    <form action="<?php htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="post">
                        <div id='recipient-div'>
                            To: 
                            <select name='recipient' 
                            <?php echo ($user_contacts) ? '' : 'disabled'; ?>>
                                <?php echo $user_contacts; ?>
                            </select>
                            <span><?php echo ($user_contacts) ? '' : 'No contacts found.'; ?></span>
                        </div>
                        <div>
                            <textarea
                            class='form-control'
                            type='text'
                            required='true' 
                            name='body'
                            placeholder='Message Body'></textarea>
                        </div>
                        <button type='submit' class='btn btn-primary'>Send</button>
                    </form>
                </div>
            </div>
            <div class="col">
                <h3>Outbox</h3>
                <div class="border border-primary p-3" style="max-height:500px; overflow-y:scroll">
                    <?php echo ($user_sent_messages) ? $user_sent_messages : "You have not sent any messages."; ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>