<?php
// show the selected contact info and confirm before performing edits
function formEditContact($db_conn,$id){
    
?>
    
	<h3>Edit Contact?</h3>
	<form method="POST" >
        <?php showEditContact($db_conn, $id);?>
    <table>
    <tr>
		<td><input type="submit" name="ct_b_edit" value="Commit Edit"></td>
    </tr>
    <tr>
		<td><input type="submit" name="ct_b_cancel" value="Cancel"></td>
    </tr>
    </table>
	</form>
<?php
}

// Retrieve selected contact from db to display
function showEditContact($db_conn, $id){
    $id = implode($id);
        
    $qry = "select ct_id, ct_type, ct_first_name, ct_last_name, ct_disp_name, ad_city, ad_province, ad_country, ad_post_code, ad_type, ad_line_1, ad_line_2, ad_line_3, em_type, em_email, we_type, we_url, ph_type, ph_number, no_note from contact left join contact_address on ct_id = ad_ct_id left join contact_email on ad_ct_id = em_ct_id left join contact_web on em_ct_id = we_ct_id left join contact_phone on we_ct_id = ph_ct_id left join contact_note on ph_ct_id = no_ct_id";

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
			<tr><td colspan="2" style="font-weight:bold;font-size:20px;">Contact Detail</td></tr>
			
<?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
?>
            <tr>
                <td>Contact Type</td>
                <td><input type = text name="edit_ct_type" value="<?php echo $row['ct_type']; ?>"></td>
            </tr>
            <tr>
                <td>First Name</td>
                <td><input type = text name="edit_ct_first_name" value="<?php echo $row['ct_first_name']; ?>"></td>
            </tr>
            <tr>
                <td>Last Name</td>
                <td><input type = text name="edit_ct_last_name" value="<?php echo $row['ct_last_name']; ?>"></td>
            </tr>            
            <tr>
                <td>Display/Business Name</td>
                <td><input type = text name="edit_ct_disp_name" value="<?php echo $row['ct_disp_name']; ?>"></td>
            </tr>
            <tr><td colspan="2"></td></tr>
            
            <tr>
                <td>Address Type</td>
                <td><select name="edit_ad_type">
                    <option><?php echo $row['ad_type']; ?></option>
                    <option>Home</option>
                    <option>Work</option>
                    <option>Other</option></select>
                </td>
            </tr>
            <tr>
                <td>Address Line 1</td>
                <td><input type = text name="edit_ad_line_1" value="<?php echo $row['ad_line_1']; ?>"></td>
            </tr>
            <tr>
                <td>Address Line 2</td>
                <td><input type = text name="edit_ad_line_2" value="<?php echo $row['ad_line_2']; ?>"></td>
            </tr>
            <tr>
                <td>Address Line 3</td>
                <td><input type = text name="edit_ad_line_3" value="<?php echo $row['ad_line_3']; ?>"></td>
            </tr>
            <tr>
                <td>City</td>
                <td><input type = text name="edit_ad_city" value="<?php echo $row['ad_city']; ?>"></td>
            </tr>
            <tr><td>Province</td>
                <td><input type = text name="edit_ad_province" value="<?php echo $row['ad_province']; ?>"></td>
            </tr>
            <tr>
                <td>Post Code</td>
                <td><input type = text name="edit_ad_post_code" value="<?php echo $row['ad_post_code']; ?>"></td>
            </tr>
            <tr>
                <td>Country</td>
                <td><input type = text name="edit_ad_country" value="<?php echo $row['ad_country']; ?>"></td>
                </tr>
            <tr><td colspan="2"></td></tr>
            
            <tr>
                <td>Phone Type</td>
                <td><input type = text name="edit_ph_type" value="<?php echo $row['ph_type']; ?>"></td>
            </tr>
            <tr>
                <td>Phone Number</td>
                <td><input type = text name="edit_ph_number" value="<?php echo $row['ph_number']; ?>"></td>
            </tr>
            <tr><td colspan="2"></td></tr>
            
            <tr>
                <td>Email Type</td>
                <td><input type = text name="edit_em_type" value="<?php echo $row['em_type']; ?>"></td>
            </tr>
            <tr>
                <td>Email Address</td>
                <td><input type = text name="edit_em_email" value="<?php echo $row['em_email']; ?>"></td>
            </tr>
            <tr><td colspan="2"></td></tr>
            
            <tr>
                <td>Web Site Type</td>
                <td><input type = text name="edit_we_type" value="<?php echo $row['we_type']; ?>"></td>
            </tr>
            <tr>
                <td>Web Site URL</td>
                <td><input type = text name="edit_we_url" value="<?php echo $row['we_url']; ?>"></td>
            </tr>
            <tr><td colspan="2"></td></tr>
            
            <tr>
                <td>Note</td>
                <td><input type = text name="edit_no_note" value="<?php echo $row['no_note']; ?>"></td>
            </tr>
<?php }
        }?>
        </table> 
<?php
    } else {
        echo "<div>\n";
        echo "<p>No contacts to display</p>\n";
        echo "</div>\n";
    }
}    

// SQL to update contacts from edits
function commitEditContact($db_conn, $id){
    $id = implode($id);
	$field_data = array();
    $qry_ct = "update contact set ct_type= ?";
	$field_data[] = $_SESSION['edit_ct_type'];
	if (isset($_SESSION['edit_ct_first_name'])){
		$qry_ct .= ", ct_first_name= ?";
		$field_data[] = $_SESSION['edit_ct_first_name'];
	}
	if (isset($_SESSION['edit_ct_last_name'])){
		$qry_ct .= ", ct_last_name= ?";
		$field_data[] = $_SESSION['edit_ct_last_name'];
	}
	if (isset($_SESSION['edit_ct_disp_name'])){
		$qry_ct .= ", ct_disp_name= ?";
		$field_data[] = $_SESSION['edit_ct_disp_name'];
	}
    $qry_ct .= " where ct_id = '".$id."';";
	$stmt = $db_conn->prepare($qry_ct);
	if (!$stmt){
		echo "<p>Error in contact prepare: ".$dbc->errorCode()."</p>\n<p>Message ".implode($dbc->errorInfo())."</p>\n";
		exit(1);
	}
	$status = $stmt->execute($field_data);
	if (!$status){
		echo "<p>Error in contact execute: ".$stmt->errorCode()."</p>\n<p>Message ".implode($stmt->errorInfo())."</p>\n";
		exit(1);
	}
	unset($field_data);

        
	$field_data = array();
    $qry_ad = "update contact_address set ad_ct_id= ?, ad_type= ?";
		$field_data[] = $id;
		$field_data[] = $_SESSION['edit_ad_type'];
		if (isset($_SESSION['edit_ad_line_1'])){
			$qry_ad .= ", ad_line_1= ?";
			$field_data[] = $_SESSION['edit_ad_line_1'];
		}
		if (isset($_SESSION['edit_ad_line_2'])){
			$qry_ad .= ", ad_line_2= ?";
			$field_data[] = $_SESSION['edit_ad_line_2'];
		}
		if (isset($_SESSION['edit_ad_line_3'])){
			$qry_ad .= ", ad_line_3= ?";
			$field_data[] = $_SESSION['edit_ad_line_3'];
		}
		if (isset($_SESSION['edit_ad_city'])){
			$qry_ad .= ", ad_city= ?";
			$field_data[] = $_SESSION['edit_ad_city'];
		}
		if (isset($_SESSION['edit_ad_province'])){
			$qry_ad .= ", ad_province= ?";
			$field_data[] = $_SESSION['edit_ad_province'];
		}
		if (isset($_SESSION['edit_ad_post_code'])){
			$qry_ad .= ", ad_post_code= ?";
			$field_data[] = $_SESSION['edit_ad_post_code'];
		}
		if (isset($_SESSION['edit_ad_country'])){
			$qry_ad .= ", ad_country= ?";
			$field_data[] = $_SESSION['edit_ad_country'];
		}
        $qry_ad .= " where ad_ct_id = '".$id."';";

		$stmt = $db_conn->prepare($qry_ad);
		if (!$stmt){
			echo "<p>Error in address prepare: ".$dbc->errorCode()."</p>\n<p>Message ".implode($dbc->errorInfo())."</p>\n";
			exit(1);
		}
		$status = $stmt->execute($field_data);
		if (!$status){
			echo "<p>Error in address execute ".$stmt->errorCode()."</p>\n<p>Message ".implode($stmt->errorInfo())."</p>\n";
			exit(1);
		}
	unset($field_data);

	$field_data = array();
	if (isset($_SESSION['edit_ph_type'])){

		$qry_ph = "update contact_phone  set ph_ct_id= ?, ph_type = ?";
		$field_data[] = $id;
		$field_data[] = $_SESSION['edit_ph_type'];
		if (isset($_SESSION['edit_ph_number'])){
			$qry_ph .= ", ph_number= ?";
			$field_data[] = $_SESSION['edit_ph_number'];
		}
        $qry_ph .= " where ph_ct_id = '".$id."';";

		$stmt = $db_conn->prepare($qry_ph);
		if (!$stmt){
			echo "<p>Error in phones prepare: ".$dbc->errorCode()."</p>\n<p>Message ".implode($dbc->errorInfo())."</p>\n";
			exit(1);
		}
		$status = $stmt->execute($field_data);
		if (!$status){
			echo "<p>Error in phone execute ".$stmt->errorCode()."</p>\n<p>Message ".implode($stmt->errorInfo())."</p>\n";
			exit(1);
		}
	}
	unset($field_data);
	$field_data = array();

	if (isset($_SESSION['edit_em_type'])){
		$qry_em = "update contact_email set em_ct_id= ?, em_type  = ?";
		$field_data[] = $id;
		$field_data[] = $_SESSION['edit_em_type'];
		if (isset($_SESSION['edit_em_email'])){
			$qry_em .= ", em_email= ?";
			$field_data[] = $_SESSION['edit_em_email'];
		}
        $qry_em .= " where em_ct_id = '".$id."';";

		$stmt = $db_conn->prepare($qry_em);
		if (!$stmt){
			echo "<p>Error in email prepare: ".$dbc->errorCode()."</p>\n<p>Message ".implode($dbc->errorInfo())."</p>\n";
			exit(1);
		}
		$status = $stmt->execute($field_data);
		if (!$status){
			echo "<p>Error in email execute ".$stmt->errorCode()."</p>\n<p>Message ".implode($stmt->errorInfo())."</p>\n";
			exit(1);
		}
	}
	unset($field_data);

    $field_data = array();
	if (isset($_SESSION['edit_we_type'])){
		$qry_we = "update contact_web  set we_ct_id= ?, we_type = ?";
		$field_data[] = $id;
		$field_data[] = $_SESSION['edit_we_type'];
		if (isset($_SESSION['we_url'])){
			$qry_we .= ", we_url= ?";
			$field_data[] = $_SESSION['edit_we_url'];
		}
        $qry_we .= " where we_ct_id = '".$id."';";

        $stmt = $db_conn->prepare($qry_we);
		if (!$stmt){
			echo "<p>Error in URL prepare: ".$dbc->errorCode()."</p>\n<p>Message ".implode($dbc->errorInfo())."</p>\n";
			exit(1);
		}
		$status = $stmt->execute($field_data);
		if (!$status){
			echo "<p>Error in URL execute ".$stmt->errorCode()."</p>\n<p>Message ".implode($stmt->errorInfo())."</p>\n";
			exit(1);
		}
	}
	unset($field_data);

	$field_data = array();
	if (isset($_SESSION['edit_no_note'])){
		$qry_no = "update contact_note  set no_ct_id= ?";
		$field_data [] = $id;
		$qry_no .= ", no_type= ?";
		$field_data[] = "";
		$qry_no .= ", no_note= ?";
		$field_data[] = $_SESSION['edit_no_note'];
        $qry_no .= " where no_ct_id = '".$id."';";

		$stmt = $db_conn->prepare($qry_no);
		if (!$stmt){
			echo "<p>Error in note prepare: ".$dbc->errorCode()."</p>\n<p>Message ".implode($dbc->errorInfo())."</p>\n";
			exit(1);
		}
		$status = $stmt->execute($field_data);
		if (!$status){
			echo "<p>Error in note execute ".$stmt->errorCode()."</p>\n<p>Message ".implode($stmt->errorInfo())."</p>\n";
			exit(1);
		}
	}
	unset($field_data);
}


function editPosttoSession(){
	$_SESSION['edit_ad_type'] = $_POST['edit_ad_type'];
	$_SESSION['edit_ad_line_1'] = $_POST['edit_ad_line_1'];
	$_SESSION['edit_ad_city'] = $_POST['edit_ad_city'];
	$_SESSION['edit_ad_line_2'] = (isset($_POST['edit_ad_line_2'])) ?
        trim($_POST['edit_ad_line_2']) : "";
	$_SESSION['edit_ad_line_3'] = (isset($_POST['edit_ad_line_3'])) ?
        trim($_POST['edit_ad_line_3']) : "";
	$_SESSION['edit_ad_province'] = (isset($_POST['edit_ad_province'])) ?
        trim($_POST['edit_ad_province']) : "";
	$_SESSION['edit_ad_post_code'] = (isset($_POST['edit_ad_post_code'])) ? 
        trim($_POST['edit_ad_post_code']) : "";
	$_SESSION['edit_ad_country'] = (isset($_POST['edit_ad_country'])) ?
        trim($_POST['edit_ad_country']) : "";
    
	$_SESSION['edit_em_type'] = $_POST['edit_em_type'];
	$_SESSION['edit_em_email'] = $_POST['edit_em_email'];
	$_SESSION['edit_ct_first_name'] = $_POST['edit_ct_first_name'];
	$_SESSION['edit_ct_last_name'] = $_POST['edit_ct_last_name'];
	$_SESSION['edit_ct_disp_name'] = $_POST['edit_ct_disp_name'];
	$_SESSION['edit_no_note'] = $_POST['edit_no_note'];
	$_SESSION['edit_ph_type'] = $_POST['edit_ph_type'];
	$_SESSION['edit_ph_number'] = $_POST['edit_ph_number'];
	$_SESSION['edit_ct_type'] = $_POST['edit_ct_type'];
	$_SESSION['edit_we_type'] = $_POST['edit_we_type'];
	$_SESSION['edit_we_url'] = $_POST['edit_we_url'];
}
?>