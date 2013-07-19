<?php

function sortTable($table, $sortCol, $desc = FALSE) {
// Sorts a two-dimensional array $arr by column $sortCol with sort-order ascending, (unless $desc is set to a non-false value) treating each datum in each sub-array as a part of a row in a column.
// ex.:
// sortMultiDimArray(array('foo' => array(6, 3, 2 8), 'bar' => array('y', 'n', 'c', 'd')), 'bar', TRUE)
// - returns -
// array(
// 	'foo' => array(6, 3, 8, 2),
// 	'bar' => array('y', 'n', 'd', 'c')
// )
// 

	if(!array_key_exists($sortCol, $table))			// If the given column to sort by is invalid, return FALSE.  Simultaneously, this ensures there is at least one column in the given array.
		return FALSE;
	
	$keys = array_keys($table);
	$nCols = count($keys);
	$nRows = count($table[$keys[0]]);
	
	// Now check to make sure there is a consistent number of rows in each column.
	for($i = 0; $i < $nCols; $i++)
		if(count($table[$keys[$i]]) - $nRows)
			return FALSE;				// Return FALSE if not.
	
	// Sort $sortCol, maintaining key association.
	if($desc)
		arsort($table[$sortCol]);
	else
		asort($table[$sortCol]);
	
	$newRowOrder = array_keys($table[$sortCol]);		// Get the new order of the array's keys.
	
	// Now reorder the remaining columns to maintain association with $sortCol
	for($i = 0; $i < $nCols; $i++) {
		if($keys[$i] == $sortCol)
			continue;				// Skip the column that's already been sorted.
		
		$colCopy = $table[$keys[$i]];			// Copy the column to be reordered.
		for($j = 0; $j < $nRows; $j++)
			$table[$keys[$i]][$j] = $colCopy[$newRowOrder[$j]];
	}
	
	return $table;
}


/*
$testArr = array(
	'id' => array(1, 2, 3),
	'first_name' => array('Max', 'Angelina', 'Pika'),
	'last_name' => array('Katz', 'Fabbro', 'The Cat')
);
*/

$testArr = array(
	array('id' => '1', 'first_name' => 'Max', 'last_name' => 'Katz'),
	array('id' => '2', 'first_name' => 'Angelina', 'last_name' => 'Fabbro'),
	array('id' => '3', 'first_name' => 'Pika', 'last_name' => 'The Cat')
);

print_r(sortTable($testArr, 'first_name', FALSE));

?>