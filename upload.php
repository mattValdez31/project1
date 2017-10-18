<?php
	$destDir = "uploads/";
	$baseName = basename($FILES["uploadFile"]["name"]);
	$targetFile = $destDir . $baseName;
	$fileType = pathinfo($targetFile, PATHINFO_EXTENSION);
	$ok = 1;

	//Check if file already exists
	if(file_exists($targetFile))
	{
		echo "Sorry, file could not be uploaded because an error exists.";
	}
	
	//Upload file if no error exists
	if($ok == 0)
	{
		echo "Sorry, file could not be uploaded because an error exists.";
	}
	else
	{
		if (move_uploaded_file($FILES["uploadFile"]["tmpName"], $target_file))
		{
			echo $baseName . "has been uploaded.";
		}
		else
		{
			echo "There was an error uploading the requested file. Please try again.";
		}
	}

?>
