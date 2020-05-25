<!--
Identification : displayErrors.php
Author : group 14 (Nam, Yahye, Caleb, Jenny, Paul)
Purpose : Display any errors.        
-->
<?php
function displayErrors($errs){
	echo "<div>\n";
	echo "<h3> This form contains the following errors</h3>\n";
	echo "<ul>\n";
	foreach ($errs as $err){
		echo "<li>".$err."</li>\n";
	}
	echo "</ul>\n";
	echo "</div>\n";
}
?>
