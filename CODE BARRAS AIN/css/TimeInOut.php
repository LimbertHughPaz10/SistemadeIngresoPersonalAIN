<?php
	require_once 'conn.php';
	
    if(isset($_POST['empleadoID'])){
        
        $empleadoID =$_POST['empleadoID'];
		$date = date('Y-m-d');
		$year = date('Y');
		$time = date('H:i:s A');

		$sql = "SELECT * FROM used WHERE USEDID = '$empleadoID'";
		$query = $conn->query($sql);

		if($query->num_rows < 1){
			$_SESSION['error'] = 'Cannot find Barcode number '.$empleadoID;
		}else{
				$row = $query->fetch_assoc();
				$id = $row['USEDID'];
				$sql ="SELECT * FROM attendance WHERE USEDID='$id' AND LOGDATE='$date' AND STATUS='0'";
				$query=$conn->query($sql);
				if($query->num_rows>0){
				$sql = "UPDATE attendance SET TIMEOUT='$time', STATUS='1' WHERE USEDID='$empleadoID' AND LOGDATE='$date'";
				$query=$conn->query($sql);
				$_SESSION['success'] = 'Tiempo de espera exitoso: '.$row['FIRSTNAME'].' '.$row['LASTNAME'].' | Time: '.$time;
				}else{
					$sql = "INSERT INTO attendance(USEDID,TIMEIN,LOGDATE,STATUS,YEAR) VALUES('$empleadoID','$time','$date','0','$year')";
					if($conn->query($sql) ===TRUE){
					 $_SESSION['success'] = 'Tiempo exitoso en: '.$row['FIRSTNAME'].' '.$row['LASTNAME'].' | Time: '.$time;
			 		}
					 else{
			  $_SESSION['error'] = $conn->error;
		   }	
		}
	}

	}else{
		$_SESSION['error'] = 'Escanee su número de código de barras';
}
header("location: index.php");
	   
$conn->close();
?>