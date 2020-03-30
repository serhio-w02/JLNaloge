<div id="uporabniki" style="display:none;">
		<form action="vpogled.php" method="POST" onsubmit="return checkForm_novRacun(this);" style="width: 50%;">
			<fieldset>
				<legend>Ustvari nov uporabniški račun</legend>
				<br />
				Uporabnik:
				<br />
				<select name="n_mentor" style="margin-top: 5px;">
					<option value="-">-</option>
					<?php
						require_once 'connect.inc.php';	
						$query_run = mysqli_query($link, "SELECT * FROM Mentor;");
						while($query_row = mysqli_fetch_assoc($query_run))
						{
							echo '<option value="'.$query_row["MentorID"]. '">'.$query_row["Priimek_mentorja"]. ' ' . $query_row["Ime_mentorja"].'</option>';
						}
					?>
				</select>
				<br />
				<input type="checkbox" name="n_privileged" value="yes" style="margin-left: 30px;"> Priviligiran uporabnik
				<br /><br />
				Uporabniško ime:
				<br />
				<input type="text" name="n_user" required>
				<br />
				Geslo:
				<br />
				<input type="password" name="n_pw" required>
				<br />
				Geslo, še enkrat:
				<br />
				<input type="password" name="n_pw_check" required>
				<br />
				E-mail (opcijsko):
				<br />		
				<input type="email" name="n_email" placeholder="janez@example.com" onblur="checkEmail(this);">
				<br />
				<span id="sendmail_checkbox">
					<input type="checkbox" name="n_sendMail" value="yes" style="margin-left: 30px;">Pošlem obvestilo na mail?
				</span>
				<br />
				<br />
				<input type="submit" name="Ustvari" value="Ustvari">
			</fieldset>
		</form>
		
		<?php
			
			if(isset($_POST['Ustvari']))
			{
				require_once 'connect.inc.php';
				foreach($_POST as $k => $e)
					$_POST[$k] = $link->real_escape_string($_POST[$k]);
							
				$pw = $_POST['n_pw'];
				$pw_hash = hash('sha512', $pw);
			
				$priv = 0;
				if(isset($_POST['n_privileged']))
					$priv = 1;
				
				if($_POST['n_mentor'] == "-")
				{
					$query = "INSERT INTO uporabnik(username, password, privilegij, email)
							  VALUES('".$_POST['n_user']."', '".$pw_hash."',
							  ".$priv.", '".$_POST['n_email']."');"; 
				}
				else
				{
					$query = "INSERT INTO uporabnik(username, password, MentorID, privilegij, email)
							  VALUES('".$_POST['n_user']."', '".$pw_hash."', '".$_POST['n_mentor']."',
							  ".$priv.", '".$_POST['n_email']."');";
				}
				if(!($query_run = mysqli_query($link, $query)))
				{
					echo mysqli_error($link);
				}
	
				else
				{
					if($_POST['n_email'] != "" && isset($_POST['n_sendMail']))
					{
						$to = $_POST['n_email'];
						$subject = "Ustvarjen uporabniški račun";
						$message = "Pozdravljeni, vaš uporabniški račun je bil uspešno ustvarjen. 
						Uporabniško ime: ".$_POST['n_user']."
						Geslo: ".$_POST['n_pw']."";
						
						$message = wordwrap($message, 70);
						
						if(mail($to, $subject, $message))
						{
							echo '<script>alert("Mail uspešno poslan!");</script>';
						}
						else
						{
							//echo '<script>alert("Opa! Nekaj je šlo narobe!");</script>';
						}
							
						
						// ...
						
					}
					header("location: refresh.php");
					exit;					
				}
			}
		?>
		
	
		<?php
		// seznam uporabnikov
			require_once 'connect.inc.php';
			
			$query = "SELECT * FROM uporabnik
					  ORDER BY uporabnik.privilegij DESC;";
			
			if($query_run = mysqli_query($link, $query))
			{
				echo '<hr><h3>Obstoječi uporabniki: </h3>';
				echo '<table id="uporabniki_table">';
				echo '<tr style="font-weight: bold;">
						<td>Uporabniško ime</td>
						<td>Mentor</td>
						<td>Privilegij</td>
					  </tr>';
				while($query_row = mysqli_fetch_assoc($query_run))
				{
					
						// query za ime in priimek mentorja po ID-ju
						$m_query = "SELECT * FROM mentor
									WHERE mentor.MentorID ='".$query_row['MentorID']."';";
				
						$m_query_run = mysqli_query($link, $m_query);
						$m_query_row = mysqli_fetch_assoc($m_query_run);
						
						if($query_row['MentorID'] === NULL )
						{
							$mentor_s = "";
						}
						else
						{
							$mentor_s = $m_query_row['Ime_mentorja'] . ' ' . $m_query_row['Priimek_mentorja'];
						}
						echo '<tr style="text-align:left;">';
						echo '<td>'.$query_row['username'].'</td>';
						echo '<td>'.$mentor_s.'</td>';
						
						if($query_row['privilegij'] == 1)
							echo '<td style="text-align: center;">Ja</td>';
						else
							echo '<td style="text-align: center;"> </td>';
						echo '</tr>';
				}
				echo '</table>';
			}
			else
			{
				echo mysqli_error($link);
			}
			
			
		?>
	
	</div>