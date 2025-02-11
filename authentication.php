<?php
	include('config.php');
	$username=$_POST['username'];
	$password=$_POST['password'];
	$access=$_POST['access'];

	$sqlCheck=mysqli_query($con,"SELECT * FROM admin WHERE username='$username' AND password='$password'");
	if(mysqli_num_rows($sqlCheck)>0){
		$row=mysqli_fetch_array($sqlCheck);
		session_start();
		$_SESSION['username']=$username;
		$_SESSION['password']=$password;
		$_SESSION['access']=$access;
		$_SESSION['fullname']=$row['fullname'];
		if($access=="IT ADMIN"){
			echo "<script>window.location='settings/?main';</script>";
		}elseif($access=="HR"){
			echo "<script>window.location='hr/?main';</script>";
		}elseif($access=="PAYROLL"){
			echo "<script>window.location='payroll/?main';</script>";
		}elseif($access=="ACCOUNTING"){
			echo "<script>window.location='accounting/?main';</script>";
		}else{
			echo "<script>alert('Authentication failed!');window.location='index.php';</script>";
		}		
	}else{
		$sqlCheck=mysqli_query($con,"SELECT * FROM users WHERE username='$username' AND password='$password' AND access='$access'");
		if(mysqli_num_rows($sqlCheck)>0){
			$row=mysqli_fetch_array($sqlCheck);
			session_start();
			$_SESSION['username']=$username;
			$_SESSION['password']=$password;
			$_SESSION['access']=$access;
			$_SESSION['idno']=$row['idno'];
			$_SESSION['fullname']="";
			if($access=="IT ADMIN"){
				echo "<script>window.location='settings/?main';</script>";
			}elseif($access=="HR"){
				echo "<script>window.location='hr/?main';</script>";
			}elseif($access=="PAYROLL"){
				echo "<script>window.location='payroll/?main';</script>";
			}elseif($access=="ACCOUNTING"){
				echo "<script>window.location='accounting/?main';</script>";
			}else{
				echo "<script>alert('Authentication failed!');window.location='index.php';</script>";
			}
		}else{
			echo "<script>alert('Authentication failed!');window.location='index.php';</script>";			
		}
	}
?>