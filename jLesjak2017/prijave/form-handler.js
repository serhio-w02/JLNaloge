function titleCase(str) {  
  str = str.toLowerCase().split(' ');

  for(var i = 0; i < str.length; i++){
    str[i] = str[i].split('');
    str[i][0] = str[i][0].toUpperCase(); 
    str[i] = str[i].join('');
  }
  return str.join(' ');
}

function autoCap(element)
{
	element.value = element.value.charAt(0).toUpperCase() + element.value.substring(1);
}



// https://www.uradni-list.si/glasilo-uradni-list-rs/vsebina?urlid=19998&stevilka=345
function emso_verify(emso)
{
if(emso.length != 13) return false;
	// za testiranje
	if(true)
	{
		var mul_map = [7, 6, 5, 4, 3, 2, 7, 6, 5, 4, 3, 2];
		var sum = 0;
		
		for(var i = 0; i < mul_map.length; i++)
		{
			sum += (mul_map[i] * emso[i]);
		}
		
		var mod = (sum % 11);
		var ctrl = 11 - mod;
		
		return (emso[12] == ctrl);
	}
	else
	{
		return true;
	}
}



function checkEmso(element)
{
	if(!emso_verify(element.value))
	{
		element.style.borderColor = "rgba(255, 0, 0, 0.4)";
		return false;
	}
	element.style.borderColor = "inherit";
	return true;
}

function checkIme(element)
{
	// preverja se, če je ime sestavljeno iz sklopov 
	var	ime_re = /[A-Za-z\u0080-\u00FF ]+/;
	if(element.value == "" || !ime_re.test(element.value) || element.value.length < 2)
	{
		element.style.borderColor = "rgba(255, 0, 0, 0.4)";
		return false;
	}
	
	element.value = titleCase(element.value);
	
	element.style.borderColor = "inherit";
	return true;
}

function selected(element)
{
	if(element.value == "-")
	{
		element.style.borderColor = "rgba(255, 0, 0, 0.4)";
		return false;
	}
	element.style.borderColor = "inherit";
	return true;
}

function notEmpty(element)
{
	if(element.value == "")
	{
		element.style.borderColor = "rgba(255, 0, 0, 0.4)";
		return false;
	}
	element.style.borderColor = "inherit";
	return true;
}

function matchRazred(form)
{
	// funkcija preveri, če se izbrani razred ujema z oddelkom
	// (računalniški oddelki se vedno začnejo z 'R', elektrotehniški pa z 'E')
	
	if(form.k_oddelek.value != "Odrasli")
	{
		if((form.k_program.value == "Tehnik računalništva" && form.k_oddelek.value[0] == 'R') ||
			(form.k_program.value == "Elektrotehnik" && form.k_oddelek.value[0] == 'E'))
			{
				return true;
			}
			else
			{
				alert("Napaka: oddelek se ne ujema s programom");
				return false;
			}
	
	}

}

function checkForm_obrazec(form)
{
	// funkcija se kliče ob kliku na gumb za oddajo. 
	// preveri veljavnost podatkov vseh polj s tem, da kliče zgoraj definirane funkcije.
	// podatki se pošljejo samo v primeru, da ta funkcije vrne TRUE. 

	return (
	   checkEmso(form.k_emso)
	&& checkIme(form.k_ime)
	&& checkIme(form.k_priimek)
	&& selected(form.k_program)
	&& selected(form.k_oddelek)
	&& notEmpty(form.k_naslovNaloge)
	&& notEmpty(form.k_opisNaloge)
	&& selected(form.k_mentor)
	&& notEmpty(form.k_podpis)
	&& matchRazred(form)
	);
}


function checkEmail(element)
{
	// email je opcijski
	if(element.value.length == 0)
		return true;
	
	var email_re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
	if(!email_re.test(element.value))
	{
		element.style.borderColor = "rgba(255, 0, 0, 0.4)";
		return false;
	}
	element.style.borderColor = "inherit";
	return true;
}

function checkMatch(element1, element2)
{
	if(element1.value != element2.value)
	{
		element1.style.borderColor = "rgba(255, 0, 0, 0.4)";
		element2.style.borderColor = "rgba(255, 0, 0, 0.4)";	
		alert("Gesli se ne ujemata");
		return false;
	}
	
	element1.style.borderColor = "inherit";
	element2.style.borderColor = "inherit";
	return true;
}


function checkForm_novRacun(form)
{
	if(form.n_pw.length < 3)
		return false;

	return (
		   (selected(form.n_user) || form.privileged.checked)
		&& checkEmail(form.n_email)
		&& checkMatch(form.n_pw, form.n_pw_check)
	);
}

function checkForm_mentor(form)
{
	return (checkIme(form.mentor_ime) && checkIme(form.mentor_priimek));

}


function toggle_visibility(id) {
   var e = document.getElementById(id);
   if(e.style.display == 'block')
	  e.style.display = 'none';
   else
	  e.style.display = 'block';
}

function go_to(id)
{
	// Pri različnih uporabniških pravicah se nalagajo različni 
	// deli strani (divi), zato pred spreminjanjem stila preverjam 
	// obstoj elementov, da se stran v nobenem primeru ne sesuje.
	
	if(document.getElementById('tabelaPrijav') !== null)
		document.getElementById('tabelaPrijav').style.display = 'none';
		
	if(document.getElementById('tabelaPrijav_master') !== null)
		document.getElementById('tabelaPrijav_master').style.display = 'none';
		
	if(document.getElementById('novMentor') !== null)
		document.getElementById('novMentor').style.display = 'none';
	
	if(document.getElementById('mentorji') !== null)
		document.getElementById('mentorji').style.display = 'none';
		
	if(document.getElementById('uporabniki') !== null)
		document.getElementById('uporabniki').style.display = 'none';

	//...
	if(document.getElementById(id) !== null)
		document.getElementById(id).style.display = 'block';
}

function go_to_mentorji()
{
	// zgornja funkcija prikaže samo en podan element.
	// ker je ta del strani sestavljen iz večih div-ov,
	// se zanj kliče ta posebna funkcija. 
	
	go_to('novMentor');
	
	if(document.getElementById('mentorji') !== null)
		document.getElementById('mentorji').style.display = 'block';
}
	







