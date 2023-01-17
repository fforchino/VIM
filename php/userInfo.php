<?php 
    header('Content-Type: application/json; charset=utf-8');
    session_start();
	$displayName = "";
	if (isset($_REQUEST['displayName'])) $displayName = $_REQUEST['displayName'];
    if(!empty($displayName)){
        include_once "config.php";
        $output = "[";
        $sql = "SELECT * FROM users WHERE lname = \"{$displayName}\" ORDER BY user_id";
        $query = mysqli_query($conn, $sql);
		$i=0;
        if(mysqli_num_rows($query) > 0){
            while($row = mysqli_fetch_assoc($query)){
				$output.="{";
				if ($i++>0) $output.=",";
				$isHuman = ($row['fname']=="Vector") ? "false" : "true";
				$output .= "\"display_name\": \"".$row['fname']." ".$row['lname']."\", ";
				$output .= "\"user_id\": \"".$row['unique_id']."\",";
				$output .= "\"is_human\": ".$isHuman;
				$output.="}";
            }
		}			
		echo $output."]";
    }else{
        echo "[]";
    }
?>