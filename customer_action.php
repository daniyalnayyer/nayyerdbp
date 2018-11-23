<?php



include('database_connection.php');

if(isset($_POST['btn_action']))
{
	if($_POST['btn_action'] == 'Add')
	{
		$query = "
		INSERT INTO  Customer (user_id, CustomerName)
		VALUES (:user_id, :CustomerName)
		";
		$statement = $connect->prepare($query);

		$statement->execute(
			array(	
				':user_id'	=>	$_POST["user_id"],
				':CustomerName'	=>	$_POST["CustomerName"]
			)
		);
			

		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Customer Added';
			echo $connect->errorInfo();	

		}
	}

	if($_POST['btn_action'] == 'fetch_single')
	{
		$query = "
		SELECT * FROM Customer WHERE CID = :CID
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':CID'	=>	$_POST["CID"]
			)
		);
		$result = $statement->fetchAll();
		foreach($result as $row)
		{
			$output['user_id'] = $row['user_id'];
			$output['CustomerName'] = $row['CustomerName'];
		}
		echo json_encode($output);
	}
	if($_POST['btn_action'] == 'Edit')
	{
		$query = "
		UPDATE Customer set 
		user_id = :user_id, 
		CustomerName = :CustomerName 
		WHERE CID = :CID
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':CID'		=>	$_POST["CID"],
				':user_id'	=>	$_POST["user_id"],
				':CustomerName'	=>	$_POST["CustomerName"],
				
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'Customer Edited';
		}
	}

	if($_POST['btn_action'] == 'delete')
	{
		$status = 'active';
		if($_POST['status'] == 'active')
		{
			$status = 'inactive';
		}
		$query = "
		UPDATE Customer 
		SET status = :status 
		WHERE CID = :CID
		";
		$statement = $connect->prepare($query);
		$statement->execute(
			array(
				':status'	=>	$status,
				':CID'		=>	$_POST['CID']
			)
		);
		$result = $statement->fetchAll();
		if(isset($result))
		{
			echo 'customer status change to ' . $status;
		}
	}
}

?>
