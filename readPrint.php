<html>
	<body>
	<table>
		<?php
			$csvFile = fopen("csv sample 1.csv", "r");

			$lines = fgetcsv($csvFile);
			$len = count($lines);
			for ($x = 0; $x < $len; $x++)
			{
				echo '<th> ' . $lines[$x] . ' </th>';
			}

			while (!feof($csvFile))
			{
				$lines = fgetcsv($csvFile);
				$len = count($lines);
				for ($x = 0; $x < $len; $x++)
				{
					switch($x)
					{
						case 0:
							echo '<tr> <td> ' .
							$lines[$x] . '</td>';
							break;
						case ($len-1):
							echo '<td> ' . $lines[$x]
							. '</td> </tr>';
							break;
						default:
							echo '<td> ' . $lines[$x]
							. '</td>';
					}
				}
			}
		?>
	</table>
	</body>
</html>
