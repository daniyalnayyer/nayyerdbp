<?php

//brand_fetch.php

include('database_connection.php');

$query = '';

$output = array();
$query .= "
SELECT * FROM Customer 
INNER JOIN user_details ON user_details.user_id = Customer.user_id 
";

if(isset($_POST["search"]["value"]))
{
	$query .= 'WHERE Customer.CustomerName LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR user_details.user_name LIKE "%'.$_POST["search"]["value"].'%" ';
	$query .= 'OR Customer.status LIKE "%'.$_POST["search"]["value"].'%" ';
}

if(isset($_POST["order"]))
{
	$query .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
}
else
{
	$query .= 'ORDER BY Customer.CID DESC ';
}

if($_POST["length"] != -1)
{
	$query .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
}

$statement = $connect->prepare($query);

$statement->execute();

$result = $statement->fetchAll();

$data = array();

$filtered_rows = $statement->rowCount();

foreach($result as $row)
{
	$status = '';
	if(strcmp($row['status'], 'active'))
	{
		$status = '<span class="label label-success">Active</span>';
	}
	else
	{
		$status = '<span class="label label-danger">Inactive</span>';
	}
	$sub_array = array();
	$sub_array[] = $row['CID'];
	$sub_array[] = $row['user_id'];
	$sub_array[] = $row['CustomerName'];

	$sub_array[] = $status;
	$sub_array[] = '<button type="button" name="update" id="'.$row["CID"].'" class="btn btn-warning btn-xs update">Update</button>';
	$sub_array[] = '<button type="button" name="delete" id="'.$row["CID"].'" class="btn btn-danger btn-xs delete" data-status="'.$row["status"].'">Delete</button>';
	$data[] = $sub_array;
}

function get_total_all_records($connect)
{
	$statement = $connect->prepare('SELECT * FROM Customer');
	$statement->execute();
	return $statement->rowCount();
}

$output = array(
	"draw"				=>	intval($_POST["draw"]),
	"recordsTotal"		=>	$filtered_rows,
	"recordsFiltered"	=>	get_total_all_records($connect),
	"data"				=>	$data
);

echo json_encode($output);

?>
