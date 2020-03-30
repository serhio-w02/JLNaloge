<head>
	<title>Ogled vloge</title>

	<style>

		.left {
			text-align: right;
			font-weight: bold;
			vertical-align: top;
		}
		
		table, td {
			padding: 5px;
		}
		
		table {
			border: 1px solid black;
			border-radius: 10px;
			
		}
		
	</style>
</head>
<body>
	<?php

	require_once 'connect.inc.php';

	foreach($_POST as $k => $field)
	{
		$_POST[$k] = $link->real_escape_string($field);
		$_POST[$k] = htmlspecialchars($_POST[$k]);
	}



	$query = "SELECT * 
			  FROM vloga
			  WHERE vloga.EMSO = '{$_POST['ogled_emso']}'
			  AND vloga.Podpis_kandidata = '{$_POST['ogled_podpis']}'";
			  
	if(!$query_run = mysqli_query($link, $query))
	{
		echo mysqli_error($link);
	}

	if(mysqli_num_rows($query_run) != 1)
	{
			echo '<script type="text/javascript">'; 
			echo 'window.location.href = "index.php";';
			echo 'alert("Takega zapisa ni v bazi");'; 
			echo '</script>';
			//header("location: index.php");
	}

	$query_row = mysqli_fetch_assoc($query_run);

	$cols = array("EMŠO", "Ime", "Priimek", "Izobraževalni program", "Oddelek", "Naslov naloge", 
				  "Naslov naloge (ang)", "Kratek opis naloge", "Datum oddaje", "Podpis");
					// odziv mentorja, odziv komisije


					
	$i = 0;

	$barve = array("Odobreno" => "rgba(0, 255, 0, 0.4)", 
				   "Nepregledano" => "rgba(255, 255, 0, 0.4)",
				   "Zavrnjeno" => "rgba(255, 0, 0, 0.4)"
				   );

	echo '<table>';
	foreach($query_row as $row => $val)
	{
		if($row == "MentorID")
		{
			$mentor_q = mysqli_query($link, "SELECT * FROM mentor WHERE mentor.MentorID = '{$val}'");
			$mentor_q_row = mysqli_fetch_assoc($mentor_q);
			
			echo '<tr>';
			echo '<td class="left">Predlagani mentor: </td>';
			echo '<td>'. ($mentor_q_row['Ime_mentorja'] . ' ' . $mentor_q_row['Priimek_mentorja']) .'</td>';
			echo '</tr>';
			break;
		}

		echo '<tr>';
		echo '<td class="left">'. $cols[$i++] .': </td>';
		echo '<td>'. wordwrap($val, 50, '<br />' , true) .'</td>';
		echo '</tr>';

	}

		echo '<tr>';
		echo '<td class="left">Odziv mentorja: </td>';
		echo '<td style="background-color: '.$barve[$query_row['Odobritev_mentor']].'">'. $query_row['Odobritev_mentor'] .'</td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '<td class="left">Odziv komisije: </td>';
		echo '<td style="background-color: '.$barve[$query_row['Odobritev_komisija']].'">'. $query_row['Odobritev_komisija'] .'</td>';
		echo '</tr>';

	echo '</table>';

	?>
	<br />
	<a href="index.php"> Nazaj na prvo stran</a>
</body>
