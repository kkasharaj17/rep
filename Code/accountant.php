<?php
session_start();
if($_SESSION['level']=='accountant'){
	include_once('conf.php');
	
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> Accountant Dashboard </title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.min.css" />
	<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/css/bootstrap.min.css"/>
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.20/css/dataTables.bootstrap4.min.css"/>
	<script type="text/javascript" src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
	<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.1/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="https://cdn.datatables.net/1.10.20/js/dataTables.bootstrap4.min.js"></script>
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
</head>

<body style="background-color: lightgrey;">

	<nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
      <a class="text-white navbar-brand"> <?php echo $_SESSION['user']; ?>'s Dashboard </a> 
	  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarCollapse">
        <ul class="navbar-nav mr-auto">
          <li class="nav-item active">
            <a class="nav-link" href="ïnsider.php">Home <span class="sr-only">(current)</span></a>
          </li>
		  <li class="nav-item">
            <a class="nav-link" href="employee.php">Employees</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="product.php">Inventory</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">Statements</a>
          </li>
        </ul>
		<ul class="nav navbar-nav navbar-right">
			<a type="button" class="btn btn-light" href="logout.php"> <i class="fas fa-sign-out-alt"></i> Logout</a>
		</ul>
      </div>
    </nav>
	
	
	<div class="container-fluid" style="margin-top: 5%;">
	  <div class="row">
		<div class="col">
				  <ol class="breadcrumb">
					<li class="breadcrumb-item active" aria-current="page"> <i class="fas fa-address-card"></i> Home</li>
					<li class="breadcrumb-item"><a href="#">Edit Profile</a></li>
				  </ol>
		  <div class="row">
			<div class="col-sm-3">
			<div class="card" style="margin: 10px; border-radius: 5%;">
			  <div class="card-header">
			<div class="float-center"> 
			  <?php $conn1=mysqli_connect(DB_HOST,USER,PASS,DB);
			$sql1 = "SELECT * FROM `web20_employee` WHERE `username` = '{$_SESSION['user']}'";
			$res = mysqli_query($conn1, $sql1); 
			$row = mysqli_fetch_assoc($res); ?>
			<img src='<?php echo $row['photo']; ?>' height='190' width='80%' style="border-radius: 20%; display: block; margin-left: auto; margin-right: auto;">
			</div>
			</div>
			<div class="card-body">
				<?php 
			$conn3=mysqli_connect(DB_HOST,USER,PASS,DB);
			$sql2 = "SELECT * FROM `web20_employee` WHERE `username` = '{$_SESSION['user']}'";
			$res2 = mysqli_query($conn3, $sql2);	
			if (!mysqli_num_rows($res2) == 0) {
			while($row1 = mysqli_fetch_assoc($res2)) { ?>
			<p style="margin-left: 20%;">
				<i class='fa fa-user-circle'></i> <span style="margin-left: 5%;"> <?php echo $row1['name']; echo " "; echo $row1['surname']; ?> </span> <br> <br>
				<i class="fas fa-briefcase"></i> <span style="margin-left: 5%;"> <?php echo $row1['job']; ?> </span> <br> <br>
				<i class='fa fa-envelope-square'></i> <span style="margin-left: 5%;"> <?php echo $row1['email']; ?> </span> <br> <br>
				<i class='fa fa-phone'></i> <span style="margin-left: 5%;"> <?php echo $row1['phone_no']; ?> </span> <br> <br>
				<i class='fa fa-birthday-cake'></i> <span style="margin-left: 5%;"> <?php echo $row1['dob']; ?> </span> <br> <br>
				<i class="fas fa-money-check-alt"></i> <span style="margin-left: 5%;"> $<?php echo $row1['netto']; ?> </span> 
			</p>	
			<?php	}
				} else echo "ERROR"; ?>
			</div>
			</div>
			</div>
			<div class="col-sm-9">
			<div class="card" style="margin: 10px;">
			  <div class="card-header">
			<div class="float-center"> <strong> Revenues Chart </strong></div>
			  </div>
			  <div class="card-body">
			    <div id="chartContainer" style="height: 370px; width: 100%;"></div>
				<script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>			  
			  </div>
			</div>
		  </div>
		</div>
	  </div>
	</div>
	</div>
	
	<script>
		$(document).ready(function() {
			$('#incoming').DataTable();
		} );
	</script>
	
	<script>
	window.onload = function () {

	var chart = new CanvasJS.Chart("chartContainer", {
		animationEnabled: true,
		theme: "light2", // "light1", "light2", "dark1", "dark2"
		title:{
			text: "Revenues per Month 2020"
		},
		axisY: {
			title: "Month"
		},
		data: [{        
			type: "column",  
			showInLegend: true, 
			legendMarkerColor: "grey",
			legendText: "Revenues in $",
			dataPoints: [      
				{ y: 1878, label: "January" },
				{ y: 2455,  label: "February" },
				{ y: 709,  label: "March" },
				{ y: 1200,  label: "April" },
				{ y: 2503,  label: "May" },
				{ y: 1500, label: "June" },
				{ y: 0,  label: "July" },
				{ y: 0,  label: "August" },
				{ y: 0,  label: "September" },
				{ y: 0,  label: "October" },
				{ y: 0,  label: "November" },
				{ y: 0,  label: "December" }
			]
		}]
	});
	chart.render();

	}
	</script>

</body>
</html>
<?php
} else {
	header("Location: login.php");
}
