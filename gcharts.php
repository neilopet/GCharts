<?php

/*
 * GCharts.php
 * Google Charts API - PHP Wrapper
 * @author Neil Opet <neil.opet@gmail.com>
 */

class GCharts {

	const LIB_URL = 'https://www.google.com/jsapi';

	private $type,
			$title,
			$fields = array(),
			$values = array();

	function __construct( $type ) {
		if (!in_array($type, array('pie'))) {
			die('Not a valid type.');
		}
		$this->type = $type;
	}

	function addKeys( array $keys ) {

	}

	function addFields( array $fields ) {
		if (count($fields) < 1) {
			die('No fields given.');
		}
		$this->fields = $fields;
	}

	function addValues( array $values ) {
		$szVals = count($values);
		if ($szVals < 1) {
			die('No values given.');
		}

		if ($szVals != count($this->fields)) {
			die('Field:Value mismatch.');
		}
		$this->values = $values;
	}

	function setTitle( $text ) {
		$this->title = $text;
	}

	function render() {
		$div_id = 'gcharts_div_' . mt_rand(0, 999);
		echo '
			<script type="text/javascript" src="' . self::LIB_URL . '"></script>
			<script type="text/javascript">
      			google.load(\'visualization\', \'1.0\', {\'packages\':[\'corechart\']});
			    google.setOnLoadCallback(function() {
			    var data = new google.visualization.DataTable();
		        data.addColumn(\'string\', \'Key\');
		        data.addColumn(\'number\', \'Val\');
		        data.addRows([
		        ';
		$rows = array();
		for ($i = 0, $szVals = count($this->values); $i < $szVals; $i++) {
			$rows[] = "['{$this->fields[$i]}', {$this->values[$i]}]";
		}
		echo implode(',', $rows);
		echo '
		        ]);

		        // Set chart options
		        var options = {\'title\':\'' . $this->title . '\',
		                       \'width\':400,
		                       \'height\':300};

		        // Instantiate and draw our chart, passing in some options.
		        var chart = new google.visualization.PieChart(document.getElementById(\'' . $div_id . '\'));
		        chart.draw(data, options);
			    });
			</script>
			<div id="' . $div_id . '"></div>
		';
	}

}