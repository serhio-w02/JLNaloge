<!-- avtor: J. Lesjak / 2017 -->
<head>
	<title>index</title>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="index.css">
</head>

<body>
							<!--  Gumbi za navigiranje -->
		<!-- Gumb za oddajo vloge preusmeri na stran za izpolnitev vloge, -->	
	<div style="margin-top: 10%;">
	<button name ="redirect" onClick="redirect('obrazec.php');" style="width: 100%;">Oddaja vloge za maturitetni izdelek</button>
	<br />
		<!-- Ostala dva gumba prikažeta modala za identifikacijo  -->
	<button onClick="document.getElementById('ogled_modal').style.display='block';">Ogled statusa vloge</button>
	<br />
	<button onClick="document.getElementById('login_modal').style.display='block';" style="width:100%;" >Prijava pooblaščene osebe</button>
	</div>
	
	
				<!--  modal za identifikacijo pred ogledom vloge	-->
	
	<div id="ogled_modal" class="modal">
	  <form class="modal-content animate" action="ogled_vloge.php" method="POST">
		<div class="container">
		  <label><b>EMŠO</b></label>
		  <input type="text" placeholder="Vpišite vaš EMŠO" name="ogled_emso" maxlength="13" required>

		  <label><b>Podpis</b></label>
		  <input type="text" placeholder="Podpis, s katerim ste podpisali vlogo" name="ogled_podpis" required>
			
		  <button type="submit" name="ogled">Ogled statusa vloge</button>
		</div>

		<div class="container" style="background-color:#f1f1f1">
		  <button type="button" onclick="document.getElementById('ogled_modal').style.display='none'" class="cancelbtn">Prekliči</button>
		</div>
	  </form>
	</div>
		
				<!--   modal za identifikacijo pred vpisom   -->
	
		<div id="login_modal" class="modal">
	  <form class="modal-content animate" action="index.php" method="POST">
		<div class="container">
		  <label><b>Uporabniško ime</b></label>
		  <input type="text" placeholder="Vpišite uporabniško ime" name="login_uname" required>

		  <label><b>Geslo</b></label>
		  <input type="password" placeholder="Vpišite geslo" name="login_psw">
			
		  <button type="submit" name="Prijava" >Prijava</button>
		</div>

		<div class="container" style="background-color:#f1f1f1">
		  <button type="button" onclick="document.getElementById('login_modal').style.display='none'" class="cancelbtn">Prekliči</button>
		</div>
	  </form>
	</div>	
</body>


<script>

	var login_modal = document.getElementById('login_modal');
	var ogled_modal = document.getElementById('ogled_modal');
	
 
	window.onclick = function(event) {
		// ta event se sproži ob kliku na 'ozadje' modala
		if (event.target == login_modal || event.target == ogled_modal) {
			// event skrije oba modala
			login_modal.style.display = "none";
			ogled_modal.style.display = "none";
		}
	}

	function redirect(url)
	{
		window.location.href = url;
	}


</script>



<?php
ob_start();
if(isset($_POST['Prijava']))
{
	require_once 'connect.inc.php';
	
	foreach($_POST as $k => $field)
	{
		$_POST[$k] = $link->real_escape_string($field);			
	}	
	
	$user = $_POST['login_uname'];
	$pw = $_POST['login_psw'];
	$pw_hash = hash('sha512', $pw);
	
	
	$query = 'SELECT * 
			  FROM uporabnik	
			  WHERE uporabnik.username = "'. $user .'"
			  AND uporabnik.password = "'. $pw_hash .'";';
	
	if($query_run = mysqli_query($link, $query))
	{
		if(mysqli_num_rows($query_run) == 1
			&& !isset($_SESSION['user']))
		{
			// login
			$query_row = mysqli_fetch_assoc($query_run);
			unset($_POST['Prijava']);
			session_start();
			$_SESSION['user'] = $user;
			$_SESSION['psw'] = $pw;
			$_SESSION['mentorID'] = $query_row['MentorID'];
			$_SESSION['privilege'] = $query_row['privilegij'];
			$_SESSION['email'] = $query_row['email'];
			header('location: vpogled.php');
		}
		else 
		{
			if(isset($_POST['Prijava']))
			{
				echo '<script>alert("Uporabniško ime in geslo se ne  ujemata.");</script>';
			}
			header('refresh:1; location: index.php');			
		}
	}
	else
	{
		echo mysqli_error($link);
	}
}

if(isset($_POST['ogled']))
{
	
	header("location: ogled_vloge.php");
	
}



?>