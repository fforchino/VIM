<?php 
    session_start();
	if (isset($_SESSION['unique_id'])) {
		$unique_id = $_SESSION['unique_id'];
	}
	if (empty($unique_id)) {
		if (isset($_POST['unique_id'])) $unique_id = $_POST['unique_id'];
	}
    if(!empty($unique_id)){
        include_once "config.php";
        $outgoing_id = $unique_id;
        $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
        $message = mysqli_real_escape_string($conn, $_POST['message']);
        if(!empty($message)){
            $sql = mysqli_query($conn, "INSERT INTO messages (incoming_msg_id, outgoing_msg_id, msg)
                                        VALUES ('{$incoming_id}', '{$outgoing_id}', '{$message}')") or die();
        }
		
		$sql = "SELECT * FROM users WHERE unique_id = \"{$outgoing_id}\"";
        $query = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query) > 0){
            $row = mysqli_fetch_assoc($query);
		}
		
    }else{
        header("location: ../login.php");
    }
?>