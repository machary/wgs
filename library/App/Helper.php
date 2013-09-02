<?php
/**
 * Seksi Pembantu
 * @author Kanwil
 */
 
class App_Helper 
{
	/**
	 * Convert string seperti "sonar_name" menjadi "Sonar Name"
	 */
	public static function fieldToName($field)
	{
		// di lowercase, ganti '_' jadi ' ', uppercase huruf awal tiap kata
		return ucwords(str_replace('_', ' ', strtolower($field)));
	}
	
	/**
	 * Transform the rows from a database query (array) into a Tree [] => array('node' => ROW, 'children' => array())
	 * @param array  $raw        the database query result, an associative array
	 * @param string $id_key     the key pointing to the row's primary key
	 * @param string $parent_key the key pointing to the row's parent's primary key
	 */
	public static function rowsToTree($raw, $id_key = 'id', $parent_key = 'parent_id') {
		// First, transform $raw to $rows so that array key == id
		$rows = array();
		foreach ($raw as $row) {
			$rows[$row[$id_key]] = $row;
		}
		$tree = array();
		$tree_index = array(); // Storing the reference to each node
	 
		while (count($rows)) {
			foreach ($rows as $id => $row) {
				if ($row[$parent_key]) { // If it has parent
					// Abnormal case: has parent id but no such id exists
					if (!array_key_exists($row[$parent_key], $rows) AND !array_key_exists($row[$parent_key], $tree_index)) {
						unset($rows[$id]);
					}
					// If the parent id exists in $tree_index, insert itself
					else if (array_key_exists($row[$parent_key], $tree_index)) {
						$parent = &$tree_index[$row[$parent_key]];
						$parent['children'][$id] = array('node' => $row, 'children' => array());
						$tree_index[$id] = &$parent['children'][$id];
						unset($rows[$id]);
					}
				} else { // Top parent
					$tree[$id] = array('node' => $row, 'children' => array());
					$tree_index[$id] = &$tree[$id];
					unset($rows[$id]);
				}
			}
		}
		return $tree;
	}

}