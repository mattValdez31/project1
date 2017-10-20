<?php

	$destDir = "uploads/";
	$baseName = basename($FILES["fileToUpload"]["name"]);
	$targetFile = $destDir . $baseName;
	$fileType = pathinfo($targetFile,PATHINFO_EXTENSION);
	$check = 1;

	//Check if file already exists
	if (file_exists($targetFile))
	{
		echo "Error: " . $baseName . " already exists.";
		$check = 0;
	}

	//upload file if it doesn't exist
	if ($check == 0)
	{
		echo "Sorry, file could not be uploaded because an error exists.";
	}

	else
	{
		if (move_uploaded_file($_FILES["fileToUpload"]["tmpName"], $target_file))
		{
			echo $baseName . " has been uploaded.";
		}
		else
		{
			echo "There was an error uploading the requested file. Please try again.";
		}
	}

	//header("Location: https://web.njit.edu/~mjv32/IS601p1/week5.php");
?>
