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
			$fn = $_GET['filename'];
			chdir('uploads');
			$csvFile = fopen($fn, "r");
			tableTags::tFormat();
			$lines = fgetcsv($csvFile);
			$len = count($lines);
			for ($x = 0; $x < $len; $x++)
			{
				tableTags::tHeader($lines[$x]);
			}

			while(!feof($csvFile))
			{
				$lines = fgetcsv($csvFile);
				$len = count($lines);
				for ($x = 0; $x < $len; $x++)
				{
					if ($x == $len-1)
					{
						tableTags::tDetail($lines[$x]);
						tableTags::endTRow();
					}
					else
					{
						tableTags::tDetail($lines[$x]);
					}
				}
			}
			fclose($csvFile);
		}
	}
			
	class tableTags
	{
		static public function headingOne($text)
		{
			return '<h1>' . $text . '</h1>';
		}

		static public function tFormat()
		{
			echo "<table>";
		}

		static public function tDetail($text)
		{
			echo '<td>' . $text . '</td>';
		}

		static public function tHeader($text)
		{
			echo '<th>' . $text . '</th>';
		}

		static public function endTRow()
		{
			echo '</tr>';
		}
	}
			
?>
