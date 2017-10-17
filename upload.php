<?php
	$destDir = "uploads/";
	$baseName = basename($FILES["uploadFile"]["name"]);
	$targetFile = $destDir . $baseName;
	$fileType = pathinfo($targetFile, PATHINFO_EXTENSION);
	$ok = 1;

	move_uploaded_file($FILES["uploadFile"]["tmpName"], $target_file)

?>
