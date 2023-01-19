<?php 
    header('Content-Type: application/json; charset=utf-8');
    session_start();
	$unique_id = "";
	if (isset($_SESSION['unique_id'])) {
		$unique_id = $_SESSION['unique_id'];
	}
	if (isset($_REQUEST['unique_id'])) $unique_id = $_REQUEST['unique_id'];
	if(!empty($unique_id)){
        include_once "config.php";
        $output = "[";
		if (isset($_REQUEST['last_message_id'])) {
			$last_message_id = $_REQUEST['last_message_id'];
			$sql = "SELECT * FROM messages LEFT JOIN users ON users.unique_id = messages.outgoing_msg_id
					WHERE (incoming_msg_id = '{$unique_id}' and msg_id>".$last_message_id.") ORDER BY msg_id";
		}
		else {
			$sql = "SELECT * FROM messages LEFT JOIN users ON users.unique_id = messages.outgoing_msg_id
			WHERE (incoming_msg_id = '{$unique_id}' and is_read=0) ORDER BY msg_id";
		}
        $query = mysqli_query($conn, $sql);
		$i=0;
        if(mysqli_num_rows($query) > 0){
            while($row = mysqli_fetch_assoc($query)){
                if($row['incoming_msg_id'] === $unique_id){
                    if ($i++>0) $output.=",";
					$otherParty = $row['fname']." ".$row['lname'];
					$output.="{";
					$output .= "\"id\": ".$row['msg_id'].",";
					$output .= "\"timestamp\": ".$row['timestamp'].",";
					$output .= "\"from\": \"".$otherParty."\", ";
					$output .= "\"from_id\": \"".$row['outgoing_msg_id']."\", ";
					$output .= "\"message\": \"".$row['msg']."\",";
					$output .= "\"read\": false";
					$output.="}";
					
					if ($messageRead==0) {
						// Mark the message as read
						$sql = "UPDATE messages SET is_read = 1 WHERE msg_id = ".$row["msg_id"];
						mysqli_query($conn, $sql);
					}
                }
            }
        }else{
            $output .= '';
        }
		
		echo $output."]";
    }else{
        header("location: ../login.php");
    }

?>