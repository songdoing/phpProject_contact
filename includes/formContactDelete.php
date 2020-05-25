<!--
Identification : formContactDelete.php
Author : group 14 (Nam, Yahye, Caleb, Jenny, Paul)
Purpose : Delete contact function        
-->

<?php
// show the selected contact info and confirm before performing soft delete
function formContactDelete($db_conn,$id){
?>
	<h3>Delete Contact?</h3>
	<form method="POST" >
        <?php displayContactToDelete($db_conn, $id);?>
    <table>
    <tr>
        <td><input type="submit" name="ct_b_next" value="Delete" ></td>
    </tr>
    <tr>
		<td><input type="submit" name="ct_b_cancel" value="Cancel"></td>
    </tr>
    </table>
	</form>
<?php
}
?>
<?php

// Retrieve selected contact from db to display
function displayContactToDelete($db_conn, $id){
    $id = implode($id);
	$qry = "select ct_id, ct_disp_name, ad_city from contact left join contact_address on ct_id = ad_ct_id";
    $qry .= " where ct_id = '".$id."';";
	$stmt = $db_conn->prepare($qry);
	if (!$stmt){
		echo "<p>Error in display prepare: ".$dbc->errorCode()."</p>\n<p>Message ".implode($dbc->errorInfo())."</p>\n";
		exit(1);
	}
        
	$status = $stmt->execute();
	if ($status){
		if ($stmt->rowCount() > 0){
?>
			<table border="1">
			<tr><th>ID#</th><th>Name</th><th>Location</th></tr>
			<tr>
<?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){ ?>

			<td><?php echo $row['ct_id']; ?></td>
			<td><?php echo $row['ct_disp_name']; ?></td>
			<td><?php echo $row['ad_city']; ?></td>
			</tr>
<?php } }?>
			</table>
<?php
		} else {
			echo "<div>\n";
			echo "<p>No contacts to display</p>\n";
			echo "</div>\n";
		}
	} 


// SQL to delete contact when id matches
function deleteContact($db_conn, $id){
    $id = implode($id);
    $stmt = $db_conn->prepare('UPDATE contact set ct_deleted =? WHERE ct_id =?');
    if (!$stmt){
        echo "Error ".$db_conn->errorCode()."\nMessage ".implode($db_conn->errorInfo())."\n";
        exit(1);
    }
    $data = array("Y", $id);
    $status = $stmt->execute($data);
    if(!$status){
       echo "Error ".$stmt->errorCode()."\nMessage ".implode($stmt->errorInfo())."\n";
        exit(1);
    }
	}
?>
