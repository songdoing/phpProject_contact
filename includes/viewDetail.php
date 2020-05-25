<!--
Identification : viewDetail.php
Author : group 14
Purpose : This page is to be displyes when the 'View Details' button is clicke on the contact list page.        
-->
<?php
$db_conn = connectDB();
function viewDetail($db_conn, $id) {
?>
    <h3>Contact Detail List</h3>
        <form method = "POST">
            <?php displayVeiwDetail($db_conn, $id); ?>
            <input type="submit" name="ct_b_backMain" value="Main Page">
        </form>
<?php
}
?>
<?php
function displayVeiwDetail($db_conn, $id) {
    
    $id = implode($id);
        
    $qry = "select ct_id, ct_type, ct_disp_name, ad_city, ad_province, ad_country, ad_post_code, ad_type, ad_line_1, ad_line_2, ad_line_3, em_type, em_email, we_type, we_url, ph_type, ph_number, no_note from contact left join contact_address on ct_id = ad_ct_id left join contact_email on ad_ct_id = em_ct_id left join contact_web on em_ct_id = we_ct_id left join contact_phone on we_ct_id = ph_ct_id left join contact_note on ph_ct_id = no_ct_id";

    $qry .= " where ct_id = '".$id."';";
    	$stmt = $db_conn->prepare($qry);
	if (!$stmt){
		echo "<p>Error in display prepare: ".$dbc->errorCode()."</p>\n<p>Message ".implode($dbc->errorInfo())."</p>\n";
		exit(1);
	}
        
	$status = $stmt->execute();
	if ($status){
		if ($stmt->rowCount() > 0){
		//echo $qry;
?>
			<table border="1">
			<tr><td colspan="2" style="font-weight:bold;font-size:20px;">Contact Detail</td></tr>
			
<?php while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){ 
?>
            <tr><td>Contact Type</td><td><?php echo $row['ct_type']; ?></td></tr>
            <tr><td>Display/Business Name</td><td><?php echo $row['ct_disp_name']; ?></td></tr>
            <tr><td colspan="2"></td></tr>
            
            <tr><td>Address Type</td><td><?php echo $row['ad_type']; ?></td></tr>
            <tr><td>Address Line 1</td><td><?php echo $row['ad_line_1']; ?></td></tr>
            <tr><td>Address Line 2</td><td><?php echo $row['ad_line_2']; ?></td></tr>
            <tr><td>Address Line 3</td><td><?php echo $row['ad_line_3']; ?></td></tr>
            <tr><td>City</td><td><?php echo $row['ad_city']; ?></td></tr>
            <tr><td>Province</td><td><?php echo $row['ad_province']; ?></td></tr>
            <tr><td>Post Code</td><td><?php echo $row['ad_post_code']; ?></td></tr>
            <tr><td>Country</td><td><?php echo $row['ad_country']; ?></td></tr>
            <tr><td colspan="2"></td></tr>
            
            <tr><td>Phone Type</td><td><?php echo $row['ph_type']; ?></td></tr>
            <tr><td>Phone Number</td><td><?php echo $row['ph_number']; ?></td></tr>
            <tr><td colspan="2"></td></tr>
            
            <tr><td>Email Type</td><td><?php echo $row['em_type']; ?></td></tr>
            <tr><td>Email Address</td><td><?php echo $row['em_email']; ?></td></tr>
            <tr><td colspan="2"></td></tr>
            
            <tr><td>Web Site Type</td><td><?php echo $row['we_type']; ?></td></tr>
            <tr><td>Web Site URL</td><td><?php echo $row['we_url']; ?></td></tr>
            <tr><td colspan="2"></td></tr>
            
            <tr><td>Note</td><td><?php echo $row['no_note']; ?></td></tr>
            
			
<?php } }?>
			</table> 
<?php
		} else {
			echo "<div>\n";
			echo "<p>No contacts to display</p>\n";
			echo "</div>\n";
		}
	} 
?>    
    
