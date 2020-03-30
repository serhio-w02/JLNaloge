<?php
	if(isset($_POST['clearFilters']))
	{
		unset($_POST['filter']);
		unset($_POST['clearFilters']);
	}
?>

<div id="tabelaPrijav" style="display:none; margin-top: 10px;">

	<form action="vpogled.php" method="POST">

		Filtrirajte podatke: <input type="text" name="filter" placeholder="Išči..."
		value="<?php echo isset($_POST['filter']) ? $_POST['filter'] : '' ?>">
		
		<input type="submit" name="refresh" value="Išči" />
		<input type="submit" name="clearFilters" value="Počisti filter" />
		
	</form>
	
	<a href="download.php">
		<button style="margin: 5px; padding: 2px;">Izvozi .xlsx</button>
	</a>
	

<?php
	require_once 'connect.inc.php';

	 // format: 
	//    mentorPriimek, mentorIme, EMSO, ime, priimek, program, razred, naslov, opis, datum, odobritev mentorja, odobritev komisije
	$query = "SELECT 
			  mentor.Priimek_mentorja, mentor.Ime_mentorja, 
			  vloga.EMSO, vloga.Ime, vloga.Priimek, vloga.Izobrazevalni_program,
			  vloga.Razred, vloga.Naslov_naloge, vloga.Naslov_naloge_eng, vloga.Opis_naloge, vloga.Datum,
			  vloga.Odobritev_mentor, vloga.Odobritev_komisija
			  FROM
			  vloga JOIN mentor ON(vloga.MentorID = mentor.MentorID)
			  WHERE mentor.MentorID = ". $_SESSION['mentorID'] .";
			";
			
	
	if($query_run = mysqli_query($link, $query))
	{
		if(mysqli_num_rows($query_run) == 0)
		{
			echo '<h1 style="text-align:center;">Ni prijav!</h1>';
			return;
		}
		else
		{		
			echo '<table class="w3-table-all" style="display: block; overflow: auto; width: 80%; font-size: 15;">';
			echo '
				<tr class="w3-grey">
					<td>Priimek mentorja</td>
					<td>Ime mentorja</td>
					<td>EMŠO kandidata</td>
					<td>Ime kandidata</td>
					<td>Priimek kandidata</td>
					<td>Izobraževalni program</td>
					<td>Oddelek</td>
					<td>Naslov naloge</td>
					<td>Opis naloge</td>
					<td>Datum oddaje vloge</td>
					<td>Odobritev mentorja</td>
					<td>Odobritev komisije</td>
				</tr>';

			// pretvori rezultat poizvedbe v asociativen array
			while($query_row = mysqli_fetch_assoc($query_run))
			{
				// če je nastavljen filter se izločijo neustrezni zapisi
				if(isset($_POST['filter']))
				{			
					$go = FALSE;
				
					// zato, da funkcije strpos() ne sesuje programa ob praznem iskanem nizu
					// (znak '-' vsebuje vsak zapis v polju 'datum')
					if(strlen($_POST['filter']) == 0)
						$_POST['filter'] = "-";
				
					foreach($query_row as $val)
					{
						if (strpos($val, $_POST['filter']) !== FALSE)
						{
							$go = TRUE;
							if( ! in_array($query_row['EMSO'], $_SESSION['export_IDs']))
							{
								$_SESSION['export_IDs'][] = $query_row['EMSO'];
							}
						}
					}
					
					if( ! $go )
					{
						continue;
					}
				}
				else
				{
					if( ! in_array($query_row['EMSO'], $_SESSION['export_IDs']))
					{
						$_SESSION['export_IDs'][] = $query_row['EMSO'];
					}
				}
			
			
				echo '<tr>';
				foreach($query_row as $row => $val)
				{
					if($row == "Odobritev_mentor")
					{
						if($val != "Nepregledano")
						{
							echo '<td>' . $val . '</td>';
						}
						else
						{
								// podatki se skripti za obdelavo posljejo kot GET parametri
							$onclick_odobri = "javascript:location.href='odobritev_handler.php";
							 $onclick_odobri .= "?id=" . $query_row['EMSO'] . "&action=Odobreno&kdo=mentor';"; 
							
							
							$onclick_zavrni = "javascript:location.href='odobritev_handler.php";
							 $onclick_zavrni .= "?id=" . $query_row['EMSO'] . "&action=Zavrnjeno&kdo=mentor';";
												
							echo "
								<td><button onclick={$onclick_odobri}>Odobri</button>
								<button onclick={$onclick_zavrni}>Zavrni</button></td>
							";
						}
					}
					
					else if($row == "Naslov_naloge")
					{
						echo '<td>' . wordwrap($val, 25, '<br />' , true)  . '<br />'. '(' .wordwrap($query_row['Naslov_naloge_eng'], 25, '<br />' , true) . ')' .'</td>';
					}
					
					// to polje se obravnava skupaj z Naslov_naloge, zato se tukaj ne obravnava. 
					else if($row == "Naslov_naloge_eng"){}
					
					
					else 
					{
						echo '<td>' . wordwrap($val, 25, '<br />', true) . '</td>';
					}
				}
				echo '</tr>';
			}
			echo '</table>';
			}
	} 
	else 
	{
		echo mysqli_error($link);		
	}
	
?>

</div>