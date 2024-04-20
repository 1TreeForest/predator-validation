<?php
	/* Include Requeried files */
	define("PREPEND_PATH", "../");
	$hooks_dir = dirname(__FILE__);
	include("{$hooks_dir}/../defaultLang.php");
	include("{$hooks_dir}/../language.php");
	include("{$hooks_dir}/../lib.php");
	include("{$hooks_dir}/language-summary-reports.php");
	include("{$hooks_dir}/SummaryReport.php");
	@header("Content-Type: text/html; charset=" . datalist_db_encoding);
 	
	$x = new StdClass;
	$x->TableTitle = "Applications/Leases Over Time";
	include_once("{$hooks_dir}/../header.php");
	
	$filterable_fields = array (
		0 => 'id',
		1 => 'tenants',
		2 => 'status',
		3 => 'property',
		4 => 'unit',
		5 => 'type',
		6 => 'total_number_of_occupants',
		7 => 'start_date',
		8 => 'end_date',
		9 => 'recurring_charges_frequency',
		10 => 'next_due_date',
		11 => 'rent',
		12 => 'security_deposit',
		13 => 'security_deposit_date',
		14 => 'emergency_contact',
		15 => 'co_signer_details',
		16 => 'notes',
		17 => 'agreement',
	);


	$config_array = array(
		'reportHash' => 'rs1tinh2qcc2azdob0r6',
		'request' => $_REQUEST,
		'groups_array' => $groups_array,
		'override_permissions' => false,
		'title' => 'Applications/Leases Over Time',
		'custom_where' => '',
		'table' => 'applications_leases',
		'label' => 'status',
		'group_function' => 'count',
		'label_title' => 'Application status',
		'value_title' => 'Count of Applications/Leases',
		'thousands_separator' => ',',
		'decimal_point' => '.',

		// show data table section?
		'data_table_section' => 1,

		// max number of data points to show on charts
		'chart_data_points' => 20,
		
		// barchart options
		'barchart_section' => 1,
		'barchart_options' => array(
			// see https://gionkunz.github.io/chartist-js/api-documentation.html#chartistbar-declaration-defaultoptions
			'axisX' => array(
				'offset' => 30,
				'position' => 'end',
				'labelOffset' => array('x' => 0, 'y' => 0),
				'showLabel' => true,
				'showGrid' => true,
				'scaleMinSpace' => 30,
				'onlyInteger' => false
			),
			'axisY' => array(
				'offset' => 40,
				'position' => 'start',
				'labelOffset' => array('x' => 0, 'y' => 0),
				'showLabel' => true,
				'showGrid' => true,
				'scaleMinSpace' => 20,
				'onlyInteger' => false
			),
			// 'width' => false,
			// 'height' => false,
			// 'high' => false,
			// 'low' => false,
			'referenceValue' => 0,
			'chartPadding' => array('top' => 15, 'right' => 15, 'bottom' => 5, 'left' => 10),
			'seriesBarDistance' => 15,
			'stackBars' => false,
			'stackMode' => 'accumulate',
			'horizontalBars' => false,
			'distributeSeries' => false,
			'reverseData' => false,
			'showGridBackground' => false,
			'classNames' => array(
				'chart' => 'ct-chart-bar',
				'horizontalBars' => 'ct-horizontal-bars',
				'label' => 'ct-label',
				'labelGroup' => 'ct-labels',
				'series' => 'ct-series',
				'bar' => 'ct-bar',
				'grid' => 'ct-grid',
				'gridGroup' => 'ct-grids',
				'gridBackground' => 'ct-grid-background',
				'vertical' => 'ct-vertical',
				'horizontal' => 'ct-horizontal',
				'start' => 'ct-start',
				'end' => 'ct-end'
			)
		),

		// piechart options
		'piechart_section' => 0,
		'piechart_options' => array(
			// see https://gionkunz.github.io/chartist-js/api-documentation.html#chartistpie-declaration-defaultoptions
			// 'width' => false,
			// 'height' => false,
			'chartPadding' => 5,
			'classNames' => array(
				'chartPie' => 'ct-chart-pie',
				'chartDonut' => 'ct-chart-donut',
				'series' => 'ct-series',
				'slicePie' => 'ct-slice-pie',
				'sliceDonut' => 'ct-slice-donut',
				'sliceDonutSolid' => 'ct-slice-donut-solid',
				'label' => 'ct-label'
			),
			'startAngle' => 0,
			// 'total' => false,
			'donut' => false,
			'donutSolid' => false,
			'donutWidth' => 60,
			'showLabel' => true,
			'labelOffset' => '50',
			'labelPosition' => 'center',
			'labelDirection' => 'neutral',
			'reverseData' => false,
			'ignoreEmptyValues' => true
		),
		'piechart_classes' => 'ct-square',

		'date_format' => 'm/d/Y',
		'date_separator' => '/',
		'jsmoment_date_format' => 'MM/DD/YYYY',
		'date_field' => 'start_date',
		'date_field_index' => '8',
		'label_field_index' => 3,
		'filterable_fields' => $filterable_fields
	);
	$report = new SummaryReport($config_array);
	echo $report->render();

	include_once("{$hooks_dir}/../footer.php");
