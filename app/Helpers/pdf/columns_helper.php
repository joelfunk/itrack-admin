<?php

function columns($pdf, $table) {
	{
		// create empty column array
		foreach($table as $row) {
			$index = 0;
			foreach($row as $cell) {
				$columns[0][$index] = 0; 
				$columns[1][$index] = 0; 
				$index++;
			}
		}

		// find max column widths
		foreach($table as $table_row) {
			$cell = 0;
			foreach($table_row as $table3) {
				for ($row=0; $row<count($table); $row++) {
					$w = floor($pdf->GetStringWidth($table[$row][$cell]) * 1000) / 1000;
					if ($w > $columns[0][$cell]) $columns[0][$cell] = $w;
				}
				$cell++;
			}
		}

		// get total
		$total = 0;
		for ($i=0; $i<count($columns[0]); $i++) {
			$total += $columns[0][$i];
		}

		// convert to % of total
		for ($i=0; $i<count($columns[0]); $i++) {
			$columns[1][$i] = floor($columns[0][$i] / $total * 10000) / 10000;
		}
		
		return $columns;
	}
}
