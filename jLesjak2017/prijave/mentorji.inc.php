<div id="novMentor" style="display:none;">
	<form action="vpogled.php" method="POST" onSubmit="return checkForm_mentor(this);">
		<fieldset style="width: 25%;">
			<legend>Dodaj novega mentorja</legend>
			Ime mentorja: <input type="text" name="mentor_ime" onBlur="autoCap(this);" required>
						  <br />
			Priimek mentorja: <input type="text" name="mentor_priimek" onBlur="autoCap(this);" required>
						  <br />
			<input type="submit" name="dodaj" value="Dodaj" style="margin-top: 8px; margin-right: 8px; float: right;">
		</fieldset>
	</form>
</div>

<?php
	// dodaja mentorja v bazo
	
	if(@isset($_POST['dodaj']))
	{
		require_once 'connect.inc.php';
	
	/*
		foreach($_POST as $k => $e)
			$_POST[$k] = $link->real_escape_string($POST[$k]);
	
	*/
		// preprečitev podvajanja mentorjev
		$query = "SELECT MentorID 
				  FROM mentor
				  WHERE mentor.Ime_mentorja = '".$_POST['mentor_ime']."'
				  AND mentor.Priimek_mentorja = '".$_POST['mentor_priimek']."'
				  ;";
				  
		$query_run = mysqli_query($link, $query);
		if(mysqli_num_rows($query_run) != 0)
		{
			echo '<script>alert("Mentor že obstaja!");</script>';
			header("location:refresh.php");
		}
		else
		{
			unset($query);
			unset($query_run);
	
			$query = 'INSERT INTO mentor(Ime_mentorja, Priimek_mentorja)
					  VALUES("'. $_POST['mentor_ime'] .'", "'. $_POST['mentor_priimek'] .'");';
			
			//$_SESSION['location'] = 'mentorji';
			if(mysqli_query($link, $query))
			{
				echo '<script>alert("Mentor uspešno dodan!");</script>';
				header("location:refresh.php");
			}
			else
			{	
				echo '<script>alert("'. mysqli_error($link) .'");</script>';
			}
		}
	}
?>

<div id="mentorji" style="display: none;">
	<table id="seznam_mentorjev">
		<tr style="font-weight: bold;">
			<td>Ime mentorja</td>
			<td>Priimek mentorja</td>
			<td>Število kandidatov</td>
		</tr>
		<?php
			require_once 'connect.inc.php';
			
			$query = "SELECT mentor.Ime_mentorja, mentor.Priimek_mentorja, COUNT(vloga.EMSO)
						FROM mentor LEFT JOIN vloga ON(mentor.MentorID = vloga.MentorID)
						GROUP BY mentor.MentorID						
						;";
						
			if($query_run = mysqli_query($link, $query))
			{
				while($query_row = mysqli_fetch_assoc($query_run))
				{
					echo '<tr>';
					foreach($query_row as $val)
					{
						echo '<td>'. $val .'</td>';
					}
					echo '</tr>';
				}					
			}
			else
			{
				echo mysqli_error($link);
			}
		?>
	</table>
</div>