<?php
	if(isset($_POST['clearFilters']))
	{
		unset($_POST['filter']);
		unset($_POST['clearFilters']);
	}
?>


<div id="tabelaPrijav_master" style="display: none;">
	<form action="vpogled.php" method="POST" style="width: 50%;">
		<fieldset>
		Filtrirajte podatke: <input type="text" name="filter" placeholder="Išči..."
		value="<?php echo isset($_POST['filter']) ? $_POST['filter'] : '' ?>">
		
		<input type="submit" name="refresh" value="Išči" />
		<input type="submit" name="clearFilters" value="Počisti filter" />
		</fieldset>
	</form>
	<a href="download.php">
		<button style="margin: 5px; padding: 2px;">Izvozi .xlsx</button>
	</a>
	<?php
	
	require_once 'connect.inc.php';

	 // format: 
	//    mentorPriimek, mentorIme, EMSO, ime, priimek, program, razred, 
	// 	  naslov, opis, datum, odobritev mentorja, odobritev komisije
	$query = "SELECT 
			  mentor.Priimek_mentorja, mentor.Ime_mentorja, 
			  vloga.EMSO, vloga.Ime, vloga.Priimek, vloga.Izobrazevalni_program,
			  vloga.Razred, vloga.Naslov_naloge, vloga.Naslov_naloge_eng, vloga.Opis_naloge, vloga.Datum, 
			  vloga.Odobritev_mentor, vloga.Odobritev_komisija
			  FROM
			  vloga JOIN mentor ON(vloga.MentorID = mentor.MentorID)
			  ORDER BY mentor.MentorID ASC
			";
	
	if($query_run = mysqli_query($link, $query))
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
				<td>Naslov naloge (sl, ang)</td>
				<td>Opis naloge</td>
				<td>Datum oddaje vloge</td>
				<td colspan="2">Odobritev mentorja</td>
				<td colspan="2">Odobritev komisije</td>
			</tr>';

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
						// ID-ji zadetkov se sproti shranjujejo v tabelo seje,
						// da se lahko posredujejo skripti za generacijo excel dokumenta
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
			if($row == "Odobritev_mentor" || $row == "Odobritev_komisija")
			{
				if($val != "Nepregledano")
				{
					echo '<td colspan="2">' . $val . '</td>';
				}
				else
				{
						// podatki se skripti za obdelavo posljejo kot GET parametri
					$onclick_odobri = "javascript:location.href='odobritev_handler.php";
					 $onclick_odobri .= "?id=" . $query_row['EMSO'] . "&action=Odobreno"; 
					
					
					$onclick_zavrni = "javascript:location.href='odobritev_handler.php";
					 $onclick_zavrni .= "?id=" . $query_row['EMSO'] . "&action=Zavrnjeno";
					
					if($row == "Odobritev_mentor")
					{
						$onclick_odobri .= "&kdo=mentor';";
						$onclick_zavrni .= "&kdo=mentor';";
					}
					else
					{
						$onclick_odobri .= "&kdo=komisija';";
						$onclick_zavrni .= "&kdo=komisija';";
					}
					
					
					echo "
						<td colspan='2'><button onclick={$onclick_odobri}>Odobri</button>
						<button onclick={$onclick_zavrni}>Zavrni</button></td>
					";
				}
			}
			else if($row == "Naslov_naloge")
			{
				echo '<td>' . wordwrap($val, 25, '<br />' , true)  . '<br />'. '(' .wordwrap($query_row['Naslov_naloge_eng'], 25, '<br />' , true) . ')' .'</td>';
			}
			
			// to polje se obravnava skupaj z Naslov_naloge, zato se tukaj preskoči. 
			else if($row == "Naslov_naloge_eng"){}

			
			else
				echo '<td>' . wordwrap($val, 25, '<br />' , true) . '</td>';
		}
		echo '</tr>';
	}
	echo '</table>';
	} else {
		echo mysqli_error($link);
	}

	?>			
</div>



