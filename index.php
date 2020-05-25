<!--
Identification : index.php
Author : group 14 (Nam, Yahye, Caleb, Jenny, Paul)
Purpose : This is the main page.        
-->

<?php 
	session_start(); 
	if (!isset($_SESSION['mode'])){
		$_SESSION['mode'] = "Display";
	}
	require_once("./includes/db_connection.php"); 
	require_once("./includes/displayContacts.php"); 
	require_once("./includes/formContactType.php");
	require_once("./includes/formContactName.php");
	require_once("./includes/formContactAddress.php");
	require_once("./includes/formContactPhone.php");
	require_once("./includes/formContactEmail.php");
	require_once("./includes/formContactWeb.php");
	require_once("./includes/formContactNote.php");
	require_once("./includes/formContactSave.php");
	require_once("./includes/formContactDelete.php");
	require_once("./includes/viewDetail.php");
	require_once("./includes/formEditContact.php");
	require_once("./includes/clearAddContactFromSession.php");
	require_once("./includes/displayErrors.php");
?>
<html>
	<head>
		<meta charset="utf-8">
		<title>Contact List</title>
	</head>
	<body>
<?php
if (isset($_POST['ct_b_add']) && ($_POST['ct_b_add'] == "Add New Contact")){
	$_SESSION['mode'] = "Add";
	$_SESSION['add_part'] = 0;
} else if (isset($_POST['ct_b_edit']) && ($_POST['ct_b_edit'] == "Edit")){
	$_SESSION['mode'] = "Edit";
} else if (isset($_POST['ct_b_delete']) && ($_POST['ct_b_delete'] == "Delete")){
	$_SESSION['mode'] = "Delete";
} else if (isset($_POST['ct_b_view_details']) && ($_POST['ct_b_view_details'] == "View Details")){
	$_SESSION['mode'] = "View";
} else if (isset($_POST['ct_b_cancel']) && ($_POST['ct_b_cancel'] == "Cancel")){
	if ($_SESSION['mode'] == "Add"){
		$_SESSION['add_part'] = 0;
		clearAddContactFromSession();
	}
	$_SESSION['mode'] = "Display";
    
// added to handle delete confirmation button    
} else if (isset($_POST['ct_b_next']) && ($_POST['ct_b_next'] == "Delete")){
    $id = ($_SESSION['list_select']);
    $db_conn = connectDB();
    deleteContact($db_conn,$id);
    clearAddContactFromSession();
    
    $db_conn = NULL;
    $_SESSION['mode'] = "Display";
} else if (isset($_POST['ct_b_backMain']) && ($_POST['ct_b_backMain'] == "Main Page")) {
    $_SESSION['mode'] = "Display";

    // added to handle edit confirmation button    
} else if (isset($_POST['ct_b_edit']) && ($_POST['ct_b_edit'] == "Commit Edit")){
    $id = ($_SESSION['list_select']);
    $db_conn = connectDB();
    $err_msgs = [];
 //   $err_msgs = validateEdits();
//    if (count($err_msgs) > 0){
  //      formEditContact($db_conn, $id);
  //  } else {
        editPostToSession();
        commitEditContact($db_conn,$id);
        clearAddContactFromSession();

        $db_conn = NULL;
        $_SESSION['mode'] = "Display";

}

if(($_SESSION['mode'] == "Add") && ($_SERVER['REQUEST_METHOD'] == "GET")){ 
	switch ($_SESSION['add_part']) {
		case 0:
		case 1:
			formContactType();
			break;
		case 2:
			formContactName();
			break;
		case 3:
			formContactAddress();
			break;
		case 4:
			formContactPhone();
			break;
		case 5:
			formContactEmail();
			break;
		default:
	}
} else if($_SESSION['mode'] == "Add"){ 
	switch ($_SESSION['add_part']) {
		case 0:
			echo "<h1> Add New Contact </h1>\n";
			$_SESSION['add_part'] = 1;
			formContactType();
			break;
		case 1:
			echo "<h1> Add New Contact </h1>\n";
			$err_msgs = validateContactType();
			if (count($err_msgs) > 0){
				displayErrors($err_msgs);
				formContactType();
			} else {
				contactTypePostToSession();
				$_SESSION['add_part'] = 2;
				formContactName();
			}
			break;
		case 2:
			echo "<h1> Add New Contact </h1>\n";
			$err_msgs = validateContactName();
			if (count($err_msgs) > 0){
				displayErrors($err_msgs);
				formContactName();
			} else if ((isset($_POST['ct_b_next']))
					&& ($_POST['ct_b_next'] == "Next")){
				contactNamePostToSession();
				$_SESSION['add_part'] = 3;
				formContactAddress();
			} else if ((isset($_POST['ct_b_back']))
						&& ($_POST['ct_b_back'] == "Back")){
				contactNamePostToSession();
				$_SESSION['add_part'] = 1;
				formContactType();
			}
			break;
		case 3:
			echo "<h1> Add New Contact </h1>\n";
			$err_msgs = validateContactAddress();
			if ((!isset($_POST['ct_b_skip'])) && (count($err_msgs) > 0)){
				displayErrors($err_msgs);
				formContactAddress();
			} else if (isset($_POST['ct_b_skip'])){
				$_SESSION['add_part'] = 4;
				formContactPhone();
			} else if ((isset($_POST['ct_b_next']))
					&& ($_POST['ct_b_next'] == "Next")){
				contactAddressPostToSession();
				$_SESSION['add_part'] = 4;
				formContactPhone();
			} else if ((isset($_POST['ct_b_back']))
						&& ($_POST['ct_b_back'] == "Back")){
				contactAddressPostToSession();
				$_SESSION['add_part'] = 2;
				formContactName();
			}
			break;
		case 4:
			echo "<h1> Add New Contact </h1>\n";
			$err_msgs = validateContactPhone();
			if ((!isset($_POST['ct_b_skip'])) && (count($err_msgs) > 0)){
				displayErrors($err_msgs);
				formContactPhone();
			} else if (isset($_POST['ct_b_skip'])){
				$_SESSION['add_part'] = 5;
				formContactEmail();
			} else if ((isset($_POST['ct_b_next']))
					&& ($_POST['ct_b_next'] == "Next")){
				contactPhonePostToSession();
				$_SESSION['add_part'] = 5;
				formContactEmail();
			} else if ((isset($_POST['ct_b_back']))
						&& ($_POST['ct_b_back'] == "Back")){
				contactPhonePostToSession();
				$_SESSION['add_part'] = 3;
				formContactAddress();
			}
			break;
		case 5:
			echo "<h1> Add New Contact </h1>\n";
			$err_msgs = validateContactEmail();
			if ((!isset($_POST['ct_b_skip'])) && (count($err_msgs) > 0)){
				displayErrors($err_msgs);
				formContactEmail();
			} else if (isset($_POST['ct_b_skip'])){
				$_SESSION['add_part'] = 6;
				formContactWeb();
			} else if ((isset($_POST['ct_b_next']))
					&& ($_POST['ct_b_next'] == "Next")){
				contactEmailPostToSession();
				$_SESSION['add_part'] = 6;
				formContactWeb();
			} else if ((isset($_POST['ct_b_back']))
						&& ($_POST['ct_b_back'] == "Back")){
				contactEmailPostToSession();
				$_SESSION['add_part'] = 4;
				formContactPhone();
			}
			break;
		case 6:
			echo "<h1> Add New Contact </h1>\n";
			$err_msgs = validateContactWeb();
			if ((!isset($_POST['ct_b_skip'])) && (count($err_msgs) > 0)){
				displayErrors($err_msgs);
				formContactWeb();
			} else if (isset($_POST['ct_b_skip'])){
				$_SESSION['add_part'] = 7;
				formContactNote();
			} else if ((isset($_POST['ct_b_next']))
					&& ($_POST['ct_b_next'] == "Next")){
				contactWebPostToSession();
				$_SESSION['add_part'] = 7;
				formContactNote();
			} else if ((isset($_POST['ct_b_back']))
						&& ($_POST['ct_b_back'] == "Back")){
				contactWebPostToSession();
				$_SESSION['add_part'] = 5;
				formContactEmail();
			}
			break;
		case 7:
			echo "<h1> Add New Contact </h1>\n";
			$err_msgs = validateContactNote();
			if ((!isset($_POST['ct_b_skip'])) && (count($err_msgs) > 0)){
				displayErrors($err_msgs);
				formContactNote();
			} else if (isset($_POST['ct_b_skip'])){
				$_SESSION['add_part'] = 8;
				formContactSave();
			} else if ((isset($_POST['ct_b_next']))
					&& ($_POST['ct_b_next'] == "Next")){
				contactNotePostToSession();
				$_SESSION['add_part'] = 8;
				formContactSave();
			} else if ((isset($_POST['ct_b_back']))
						&& ($_POST['ct_b_back'] == "Back")){
				contactNotePostToSession();
				$_SESSION['add_part'] = 6;
				formContactWeb();
			}
			break;
		case 8:
			if ((isset($_POST['ct_b_next']))
					&& ($_POST['ct_b_next'] == "Save")){
				$db_conn = connectDB();
				saveContact($db_conn);
				$db_conn = NULL;
				$_SESSION['add_part'] = 0;
				clearAddContactFromSession();
				$_SESSION['mode'] = "Display";
				formContactDisplay();
                       
			} else if ((isset($_POST['ct_b_back']))
						&& ($_POST['ct_b_back'] == "Back")){
				echo "<h1> Add New Contact </h1>\n";
				$_SESSION['add_part'] = 7;
				formContactNote();
			}
			break;
		default:
	}



} else if($_SESSION['mode'] == "Edit"){ 
  if (isset($_POST['list_select'])) {
        $_SESSION['list_select'] = ($_POST['list_select']);
        $id = ($_POST['list_select']);
        $db_conn = connectDB();
        formEditContact($db_conn, $id);
 // if nothing selected...        
    } else {
        echo "<p style='color:red'>Select from the list</p>";

	formContactDisplay();
    }
// when delete button click> display contact to delete and get confirmation
} else if($_SESSION['mode'] == "Delete"){
    if (isset($_POST['list_select'])) {
        $_SESSION['list_select'] = ($_POST['list_select']);
        $id = ($_POST['list_select']);
        $db_conn = connectDB();
        formContactDelete($db_conn, $id);
        
// if nothing selected...        
    } else {
        echo "<p style='color:red'>Select from the list</p>";

	formContactDisplay();
    }


} else if($_SESSION['mode'] == "View"){ 
    if (isset($_POST['list_select'])) {
        $_SESSION['list_select'] = ($_POST['list_select']);
        $id = ($_POST['list_select']);
        $db_conn = connectDB();
        viewDetail($db_conn, $id);
    }
    else {
        echo "<p style='color:red'> Select one from the list</p>";
        formContactDisplay();
    }
} else if($_SESSION['mode'] == "Display"){ 
	formContactDisplay();
} 
?>
	</body>
</html>

<?php
function formContactDisplay(){
	$db_conn = connectDB();
	$fvalue = "";
	if (isset($_POST['ct_b_filter']) && isset($_POST['ct_filter'])){
		$_SESSION['ct_filter'] = trim($_POST['ct_filter']);
		$fvalue = $_SESSION['ct_filter'];
	} else if (isset($_POST['ct_b_filter_clear'])){
		$_SESSION['ct_filter'] = "";
		$fvalue = $_SESSION['ct_filter'];
	} else if (isset($_SESSION['ct_filter'])){
		$fvalue = $_SESSION['ct_filter'];
	}
?>
		<h1> Contacts </h1>
		<div>
			<h2> Contacts </h2>
		</div>
		<div>
		<form method="POST">
		<table>
		<tr>
			<td><label for="ct_filter">Filter Value</label></td>
			<td><input type="text" name="ct_filter" id="ct_filter" value="<?php echo $fvalue; ?>"></td>
			<td><input type="submit" name="ct_b_filter" value="Filter">
			<td><input type="submit" name="ct_b_filter_clear" value="Clear Filter">
		</tr>
		</table>
		<br>
<?php
	displayContacts($db_conn);
	$db_conn = NULL;
?>
			<br>
			<table>
			<tr>
				<td><input type="submit" name ="ct_b_view_details" value="View Details"></td>
				<td><input type="submit" name ="ct_b_edit" value="Edit"></td>
				<td><input type="submit" name ="ct_b_delete" value="Delete"></td>
			</tr>
			<tr></tr>
			<tr>
				<td><input type="submit" name ="ct_b_add" value="Add New Contact"></td>
			</tr>
			</table>
		</form>
		</div>
<?php } ?>
