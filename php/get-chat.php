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
        $output = "";
        $sql = "SELECT * FROM messages LEFT JOIN users ON users.unique_id = messages.outgoing_msg_id
                WHERE (outgoing_msg_id = '{$outgoing_id}' AND incoming_msg_id = '{$incoming_id}')
                OR (outgoing_msg_id = '{$incoming_id}' AND incoming_msg_id = '{$outgoing_id}') ORDER BY msg_id";
        $query = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query) > 0){
            while($row = mysqli_fetch_assoc($query)){
				$msg = convert_emoticons($row['msg']);
                if($row['outgoing_msg_id'] === $outgoing_id){
                    $output .= '<div class="chat outgoing">
                                <div class="details">
                                    <p>'. $msg .'</p>
                                </div>
                                </div>';
                }else{
                    $output .= '<div class="chat incoming">
                                <img src="php/images/'.$row['img'].'" alt="">
                                <div class="details">
                                    <p>'. $msg .'</p>
                                </div>
                                </div>';
                }
            }
        }else{
            $output .= '<div class="text">No messages are available. Once you send message they will appear here.</div>';
        }
        echo $output;
    }else{
        header("location: ../login.php");
    }

function startsWith( $haystack, $needle ) {
     $length = strlen( $needle );
     return substr( $haystack, 0, $length ) === $needle;
}

function endsWith( $haystack, $needle ) {
    $length = strlen( $needle );
    if( !$length ) {
        return true;
    }
    return substr( $haystack, -$length ) === $needle;
}

function convert_emoticons($str) {
	if (startsWith($str, '*') && endsWith($str, '*')) {
		$fName = substr($str, 1, strlen($str)-2);
		$str='<img height="24" src="emo/'.$fName.'.png"\>';
	}	
	return $str;
}
?>