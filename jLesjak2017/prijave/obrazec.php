<head>

	<title>Obrazec</title>

	<link rel="stylesheet" type="text/css" href="obrazec.css">
	<meta charset="UTF-8">
	<script src="form-handler.js"></script>

</head>


<body>
	<div id="obrazec">
		<form action="obrazec.php" method="POST" onsubmit="return checkForm_obrazec(this);">
			<fieldset>
				<img src="vegova_logotip.png">
				<h1 style="text-align:center; padding: 20px;">Vloga za maturitetni izdelek</h1>
																																
				EMŠO kandidata: <input type="text" name="k_emso" maxlength="13" size="13" onblur="checkEmso(this);" 
								value="<?php echo isset($_POST['k_emso']) ? $_POST['k_emso'] : '' ?>" required><br/> 
				Ime kandidata: <input type="text" name="k_ime" onblur="checkIme(this);" 
								value="<?php echo isset($_POST['k_ime']) ? $_POST['k_ime'] : '' ?>" required><br/>
				Priimek kandidata: <input type="text" name="k_priimek" onblur="checkIme(this);" 
								value="<?php echo isset($_POST['k_priimek']) ? $_POST['k_priimek'] : '' ?>" required><br/>
				Izobraževalni program: 
				<select name="k_program" onblur="selected(this);" value="<?php echo isset($_POST['k_program']) ? $_POST['k_program'] : '' ?>" required>
							<?php
								$programi = array("-", "Elektrotehnik", "Tehnik računalništva"); 
								foreach($programi as $el)
								{
									if(isset($_POST['k_program']) && $_POST['k_program'] == $el)
										echo '<option selected="selected">'.$el.'</option>';
									else
										echo '<option>'.$el.'</option>';
								}
							?>
				</select>
				
				Oddelek: <select name="k_oddelek" onblur="selected(this);">
							<?php
								$oddelki = array("Odrasli", "E4A", "E4B", "E4C", "E4D", "R4A", "R4B", "R4C", "R4D"); 
								foreach($oddelki as $el)
								{
									if(isset($_POST['k_oddelek']) && $_POST['k_oddelek'] == $el)
										echo '<option selected="selected">'.$el.'</option>';
									else
										echo '<option>'.$el.'</option>';
								}
							?>
						</select>
						
				Naslov naloge: <input type="text" name="k_naslovNaloge" onblur="notEmpty(this);" 
								value="<?php echo isset($_POST['k_naslovNaloge']) ? $_POST['k_naslovNaloge'] : '' ?>" required><br/>
				Naslov naloge v angleščini: <input type="text" name="k_naslovNaloge_eng" onblur="notEmpty(this);" 
								value="<?php echo isset($_POST['k_naslovNaloge_eng']) ? $_POST['k_naslovNaloge_eng'] : '' ?>" required><br/>
				Kratek opis naloge: <br />
				<textarea rows="10" cols="40" name="k_opisNaloge" maxlength="1000" onblur="notEmpty(this);" 
				required><?php echo isset($_POST['k_opisNaloge']) ? trim($_POST['k_opisNaloge']) : '' ?></textarea><br/>
				
				Predlagani mentor: <select name="k_mentor" style="width: 25%;" onblur="selected(this);" required>
									<option>-</option>
				<!-- dropdown meni za izbiro mentorja -->
				<?php
					require_once 'connect.inc.php';
					$query = "SELECT * FROM mentor;";
				
					if(!($query_run = mysqli_query($link, $query)))
					{
						echo mysqli_error($link);
					}
					else 
					{
						while($query_row = mysqli_fetch_assoc($query_run))
						{
							echo '<option value="'. $query_row['MentorID'] .'"';
							
							if(isset($_POST['k_mentor']) && $_POST['k_mentor'] == $query_row['MentorID'])
								echo ' selected="selected"';
							
							
							echo '>' .($query_row['Ime_mentorja'] . ' ' . $query_row['Priimek_mentorja']) 
							.'</option>';
						
						}
					}
					mysqli_close($link);					
				?>
				</select>
				
				<div style="float:right;">
				
					Podpis kandidata: 

					<input type="text" name="k_podpis" style="width:50%;" onblur="notEmpty(this);" 
							value="<?php echo isset($_POST['k_podpis']) ? $_POST['k_podpis'] : '' ?>" required>
					
					<div id="questionmark">
						<img src="questionmark.png" alt="(?)">
						
						<span class="tooltiptext">
							Ta podpis boste potrebovali za identifikacijo ob ogledu vloge.
						</span>
					</div>
				</div>
				
				<input type="submit" name="poslji" value="Pošlji">
			</fieldset>
		</form>
	</div>
</body>


<?php
 
 require 'connect.inc.php';
   
function proper_json($string)
{
	// iz stringa, ki ga vrne API, izloči in vrne vsebino JSON arraya
	$r = "";
	$check = false;
	foreach(str_split($string, 1) as $c)
	{
		if($c == '{')
			$check = true;
			
		if($check)
			$r .= $c;
	}
	return $r;
}

  
  
if(@$_POST['poslji'] == 'Pošlji')
{
	// sanitacija vnešenih podatkov
	foreach($_POST as $k => $field)
	{
		$_POST[$k] = $link->real_escape_string($field);
		$_POST[$k] = htmlspecialchars($_POST[$k]);		
	}	
	// preverjanje, če so podatki veljavni
	
	$processed = FALSE;
	$ready = FALSE;
	$ERROR_MESSAGE = '';

	// ************* Call API:
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "http://infosys1.s-sser.lj.edus.si/secure/php/api_1eC.php");
	curl_setopt($ch, CURLOPT_POST, 1);// set post data to true
	curl_setopt($ch, CURLOPT_POSTFIELDS,"ime=l2&priimek=priloli&emso={$_POST['k_emso']}&key=2");   // post data
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	$json = curl_exec($ch);
	curl_close ($ch);

	// vrnjeni json string zgleda: {"ime":Matija,"priimek":"PAPEŽ","emso":"1812000500192"}
	$json = proper_json($json);
	$obj = json_decode($json);           // json v tabelo
	
	// v primeru, da ga ne najde v bazi, vrne {"bad":"errorNumber"}
	if ( ! isset($obj->{'bad'}) )
	{
	  $processed = TRUE;
	}else{
	  echo '<script>alert("Vnešenega emša ni v bazi dijakov");</script>';
	  $ERROR_MESSAGE = $obj->{'bad'};
	  $processed = FALSE;
	  die($ERROR_MESSAGE);
	}
	
	if($processed)
	{
		// ime in priimek pridobljena iz baze (po emšu)
		$fetch_ime = trim($obj->{'ime'});
		$fetch_priimek = trim($obj->{'priimek'});
		$fetch_priimek = mb_convert_case($fetch_priimek, MB_CASE_TITLE, "UTF-8");
		
		
		if($_POST['k_ime'] != $fetch_ime || $_POST['k_priimek'] != $fetch_priimek)
		{
			echo '<script>alert("Neveljavni podatki - EMŠO se ne ujema s priimkom ali imenom");</script>';
		}
		else
		{
			$ready = TRUE;
		}
		
	}
	
	// preverjanje, če zapis pod vnesenim emšom že obstaja
	if($ready)
	{
	
		$query = "SELECT *
				  FROM vloga
				  WHERE vloga.EMSO = {$_POST['k_emso']}";
	
		$query_run = mysqli_query($link, $query);
		if(mysqli_num_rows($query_run) != 0)
		{
			echo '<script>alert("Vnešen EMŠO že obstaja");</script>';
			$ready = FALSE;
		}
	}
	
	
	// če je vse vredu, potem se podatki zapišejo v podatkovno bazo
	if($ready)
	{
		@$query = "INSERT INTO 
					vloga(EMSO, Ime, Priimek, Izobrazevalni_program, Razred, 
					Naslov_naloge, Naslov_naloge_eng, Opis_naloge, Datum, Podpis_kandidata, MentorID)
				VALUES				
					('". $_POST['k_emso'] ."', '" 
					  . $_POST['k_ime'] ."', '"
					  . $_POST['k_priimek'] ."', '"
					  . $_POST['k_program'] ."', '"
					  . $_POST['k_oddelek'] ."', '"
					  . $_POST['k_naslovNaloge'] ."', '"
					  . $_POST['k_naslovNaloge_eng'] ."', '"
					  . $_POST['k_opisNaloge'] ."', '"
					  .	date("y-m-d") . "', '" // 'datum' je datum oddaje vloge
					  . $_POST['k_podpis'] ."', "
					  . $_POST['k_mentor'] .");";
			
		if(mysqli_query($link, $query))
		{
			$_POST = array();
			echo '<script type="text/javascript">'; 
			echo 'alert("Vloga uspešno oddana!");'; 
			echo 'window.location.href = "index.php";';
			echo '</script>';
		} 
		else 
		{
			echo mysqli_error($link);
		}
	}
}
mysqli_close($link);
?>