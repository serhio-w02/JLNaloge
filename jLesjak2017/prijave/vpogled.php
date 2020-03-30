<?php
	ob_start();
	session_start();
	if(!isset($_SESSION['user']))
	{
		header('location: index.php');
	}
	
	$_SESSION['export_IDs'] = array();
	
?>

<head>
	<meta charset="UTF-8">
	<title>Vpogled</title>
	<link rel="stylesheet" type="text/css" href="vpogled.css">
	<link rel="stylesheet" type="text/css" href="w3.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
	<script src="form-handler.js"></script>
	
	<script>
		// za dinamično prilagajanje sidebar-a
		$(document).ready(function(){
			var height = $('#content').height();
			$('#sidebar').height(height);
			
			$(window).resize(function(){
				var height2 = $('#content').height();
				$('#sidebar').height(height2);
			});
		});    
    </script>
	
</head>
<body>
	<div id="navbar" style="position: relative; display: block; ">
		<p style="bottom: 0; left: 25px; position: absolute;">Prijavljeni ste kot: 
			<?php
				echo '<b>'. $_SESSION['user'] .'</b>';
							
			?>
		 <a href="odjava.php">(odjava)</a>
		</p>
	</div>
	

	
	
	
	<div id="content" style="">
		<div id="sidebar" style="min-height: 85%;">
			<?php
				
				// generiranje gumbov na sidebar-u glede na uporabniske pravice
				if($_SESSION['privilege'] == true)
				{
					$tmp = "'tabelaPrijav_master'";
				}
				else
				{
					$tmp = "'tabelaPrijav'";
				}
				
				echo'
					<div onClick="go_to('.$tmp.')">
						<p> Prijave </p>
					</div>
				';
				
				if($_SESSION['privilege'] == true)
				{
					$tmp = "'uporabniki'";
					echo'
						<div onClick="go_to_mentorji()">
							<p>	Mentorji </p>
						</div>
						<div onClick="go_to('.$tmp.');">
							<p> Uporabniki </p>
						</div>';
				}
			?>
			<div id="naVrh">
				<a href="#navbar">Nazaj na vrh</a>
			</div>
		</div>
		
		<?php
			// nalaganje vsebine strani
			
			// priviligirani uporabnik
			if($_SESSION['privilege'] == true)
			{
				require_once 'mentorji.inc.php';
				require_once 'uporabniki.inc.php';
				require_once 'prijave_master.inc.php';
			}
			// ostali uporabniki
			else
			{
				require_once 'prijave.inc.php';
			}
		?>
	<!-- /contents -->
	</div>
</body>

<?php
		if($_SESSION['privilege'] == true)
		{
			$tmp = "'tabelaPrijav_master'";
		}
		else
		{
			$tmp = "'tabelaPrijav'";
		}
		
		echo '<script>go_to('.$tmp.');</script>';
?>











