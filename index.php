<?php
//PHP Front Controller

	//turn on debugging messages

	ini_set('display_errors', 'On');
	error_reporting(E_ALL);

	//instantiate the program object

	//Class to load classes it finds the file when the program starts to fail for calling a missing class
	class Manage 
	{
		public static function autoload($class)
		{
			//you can put any file name or dir here
			include $class . '.php';
		}
	}

	spl_autoload_register(array('Manage', 'autoload'));

	//instantiate the program object
	$obj = new main();

	class main
	{
		public function __construct()
		{
			//print_r($_REQUEST);
			//set default page request when no parameters are in URL
			$pageRequest = 'homepage';
			//check if there are parameters
			if(isset($_REQUEST['page']))
			{
				//load the type of page the request wants into page request
				$pageRequest = $_REQUEST['page'];
			}
			//instantiate the class that is being requested
			$page = new $pageRequest;

			if ($_SERVER['REQUEST_METHOD'] == 'GET')
			{
				$page->get();
			}
			else 
			{
				$page->post();
			}
		}
	}
	
	abstract class page
	{
		protected $html;
		public function __construct()
		{
			$this->html .= '<html>';
			$this->html .= '<link rel="stylesheet" href="styles.css">';
			$this->html .= '<body>';
		}
		public function __destruct()
		{
			$this->html .= '</body></html>';
			stringFunctions::printThis($this->html);
		}

		public function get()
		{
			echo 'default get message';
		}
		public function post()
		{
			print_r($_POST);
		}
	}

	class homepage extends page 
	{
		public function get()
		{
			$form = '<form action="index2.php" method="post">';
			$form .= 'First name:<br>';
			$form .= '<input type="text" name="firstname" value="Mickey">';
			$form .= '<br>';
			$form .= 'Last name:<br>';
			$form .= '<input type="text" name="lastname" value="Mouse">';
			$form .= '<input type="submit" value="Submit">';
			$form .= '</form> ';
			$this->html .= 'homepage';
			$this->html .= $form;
		}
	}

	class uploadform extends page
	{
		public function get()
		{
			$form = '<form action="index.php?page=uploadForm" method="post" enctype="multipart/form-data">';
			$form .= '<input type="file" name="fileToUpload" id="fileToUpload">';
			$form .= '<input type="submit" value="Upload File" name="submit">';
			$form .= '</form>';
			$this->html .= '<h1>Upload Form</h1>';
			$this->html .= $form;
		}
		
		public function post()
		{
			/*echo 'test';
			print_r($_FILES);*/

			$destDir = "uploads/";
			$fName = $_FILES["fileToUpload"]["name"];
			$baseName = basename($_FILES["fileToUpload"]["name"]);
			$targetFile = $destDir . $_FILES["fileToUpload"]["name"];
			$check = 1;

			//Check if file already exists
			if (file_exists($targetFile))
			{
				echo "Error: " . $fName . " already exists.";
				$check = 0;
			}

			//upload file if it doesn't exist
			if($check == 0)
			{
				echo "Sorry, file couldn't be uploaded due to an
				error.";
			}
			else
			{
				if
				(move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile))
				{
					header("Location: index.php?page=htmlTable&filename=$fName");
				}
				else
				{
					echo "There was an error uploading the requested file.";
				}
			}
		}
	}

	class htmlTable extends page
	{
		public function get()
		{
			
			$csv = $_GET['filename'];
			chdir('uploads');
			$file = fopen($csv, "r");
			htmlTags::tableFormat();
			$row = 1;
			while (($data=fgetcsv($file)) !== FALSE)
			{
				foreach($data as $value)
				{
					if($row == 1)
					{
						htmlTags::tableHeader($value);
					}
					else
					{
						htmlTags::tableContent($value);
					}
				}
				$row++;
				htmlTags::breakTableRow();
			}
		fclose($file);
		}
	}
			
	class htmlTags
	{
		static public function headingOne($text)
		{
			return '<h1>' . $text . '</h1>';
		}

		static public function tableFormat()
		{
			echo "<table>";
		}

		static public function tableHeader($text)
		{
			echo '<th>' . $text . '</th>';
		}

		static public function tableContent($text)
		{
			echo '<td>' . $text . '</td>';
		}

		static public function breakTableRow()
		{
			echo '</tr>';
		}
	}
			
					
			/*$out = '<table>';
			$out .= '<?php';
			//$out .= '$csvFile = fopen($_FILES["fileToUpload"]["name"], "r");';
			$out .= '$csvFile = fopen("csv sample 1.csv", "r");';
				$out .= '$lines = fgetcsv($csvFile);';
				$out .= '$len = count($lines);';
				$out .= 'for ($x = 0; $x < $len; $x++)';
				$out .= '{';
					//$out .= 'echo $len;';
					//$out .= "echo '<th> ' . $lines[$x] . ' </th>';";
					$out .= "echo '<th>  {$lines[$x]}  </th>';";
				$out .= '}';

				$out .= 'while(!feof($csvFile))';
				$out .= '{';
					$out .= '$lines = fgetcsv($csvFile);';
					$out .= '$len = count($lines);';

					$out .= 'for ($x = 0; $x < $len; $x++)';
					$out .= '{';
						$out .= 'switch($x)';
						$out .= '{';
							$out .= 'case 0:';
								$out .= 'echo '<tr> <td> ' . $lines[$x] . '</td>';';
								$out .= 'break;';
							$out .= 'case ($len-1):';
								$out .= 'echo '<td> ' . $lines[$x] . '</td> </tr>';';
								$out .= 'break;';
							$out .= 'default:';
								$out .= 'echo '<td> ' . $lines[$x] . '</td>';';
						$out .= '}';
					$out .= '}';
				$out .= '}';
			$out .= '?>';
			$out .= '</table>';

			$this->html .= $out; 
		}
	}*/

?>
