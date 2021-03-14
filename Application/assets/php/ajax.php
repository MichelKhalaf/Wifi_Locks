<?php


//header('Content-Type: application/json'); // TODO don't need this line?
$qResult = array();



if (!isset($_POST['queryname']) || !isset($_POST['queryargs'])) {
	$qResult['error'] = 'Query not set!';
}



if (true || !isset($qResult['error'])) {
	phpalert("ajax.php line 16");
	switch (strtoupper($_POST['queryname'])) { // Make upper case
		case "INSERT":
			// qTable:     String
			// qColNames:  Array
			// qColValues: Array
			if ( !is_array($_POST['queryargs']) || count($_POST['queryargs']) != 3 || !is_array($_POST['queryargs'][1]) || !is_array($_POST['queryargs'][2]) || count($_POST['queryargs'][1]) != count($_POST['queryargs'][2]) ) {
				$qResult['error'] = 'Error in INSERT query arguments!';
			} else {
				$qTable     = $_POST['queryargs'][0];
				$qColNames  = $_POST['queryargs'][1];
				$qColValues = $_POST['queryargs'][2];
				
				// INSERT INTO table (x1, ...) VALUES (y, ...)
				$qResult['result'] = qInsert($qTable, $qColNames, $qColValues);
			}
			break;
			
		case "UPDATE":
			// qTable:     String
			// qColNames:  Array
			// qColValues: Array
			if ( !is_array($_POST['queryargs']) || count($_POST['queryargs']) != 3 || !is_array($_POST['queryargs'][1]) || !is_array($_POST['queryargs'][2]) || count($_POST['queryargs'][1]) != count($_POST['queryargs'][2]) ) {
				$qResult['error'] = 'Error in UPDATE query arguments!';
			} else {
				$qTable     = $_POST['queryargs'][0];
				$qColNames  = $_POST['queryargs'][1];
				$qColValues = $_POST['queryargs'][2];
				
				// UPDATE table SET (x = y, ...) WHERE primarykeyName = primarykeyValue
				$qResult['result'] = qUpdate($qTable, $qColNames, $qColValues);
			}
			break;
			
		case "DELETE":
			// qTable:     String
			// qPKeyName:  String
			// qPKeyValue: String
			if ( !is_array($_POST['queryargs']) || (count($_POST['queryargs']) != 3) ) {
					$qResult['error'] = 'Error in DELETE query arguments!';
				} else {
					$qTable     = $_POST['queryargs'][0];
					$qPKeyName  = $_POST['queryargs'][1];
					$qPKeyValue = $_POST['queryargs'][2];
					
					// DELETE FROM table WHERE primarykeyName = primarykeyValue
					$qResult['result'] = qDelete($qTable, $qPKeyName, $qPKeyValue);
				}
				break;
				
		default:
			$qResult['error'] = 'Error in query type!';
			break;
	}
}

echo json_encode($qResult);

?>