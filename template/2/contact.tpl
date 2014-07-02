<!--{IT_FORM}-->
{TRESC}

<script type="text/javascript">
function isValidEmail(email, required) {
    if (required==undefined) {   // if not specified, assume it's required
        required=true;
    }
    if (email==null) {
        if (required) {
            return false;
        }
        return true;
    }
    if (email.length==0) {  
        if (required) {
            return false;
        }
        return true;
    }
    if (! allValidChars(email)) {  // check to make sure all characters are valid
        return false;
    }
    if (email.indexOf("@") < 1) { //  must contain @, and it must not be the first character
        return false;
    } else if (email.lastIndexOf(".") <= email.indexOf("@")) {  // last dot must be after the @
        return false;
    } else if (email.indexOf("@") == email.length) {  // @ must not be the last character
        return false;
    } else if (email.indexOf("..") >=0) { // two periods in a row is not valid
	return false;
    } else if (email.indexOf(".") == email.length) {  // . must not be the last character
	return false;
    }
    return true;
}

function allValidChars(email) {
  var parsed = true;
  var validchars = "abcdefghijklmnopqrstuvwxyz0123456789@.-_";
  for (var i=0; i < email.length; i++) {
    var letter = email.charAt(i).toLowerCase();
    if (validchars.indexOf(letter) != -1)
      continue;
    parsed = false;
    break;
  }
  return parsed;
}

function check(){
	if (document.getElementById('imie').value == '' || !isValidEmail(document.getElementById('email').value,true) || document.getElementById('tresc').value == ''){
		alert('Wypełnij wszystkie wymagane pola!');
		document.getElementById('imie').focus();
		return false;
	} 
}
</script>
<h2>{T_KO_TYTUL}</h2>

<form name="form1" method="post" enctype="multipart/form-data" action="#" class="formkontakt">
<input type="hidden" name="submit_kontakt" value="1" />
<table class="formularz" >
<tr>
                    	<td class="pierw">{T_KO_IMIE}:<span>*</span></td>
                        <td><input name="imie" type="text" class="txtl" id="imie" /></td>
                    </tr>
                    <tr>
                    	<td class="pierw">{T_KO_EMAIL}:<span>*</span></td>
                        <td><input name="email" type="text" class="txtl"  id="email" /></td>
                    </tr>
                    <tr>
                    	<td class="pierw">{T_KO_TRESC}:<span>*</span></td>
                        <td><textarea name="tresc" class="txtl" style="height:90px" id="tresc"></textarea></td>
                    </tr>
                    <tr>
<tr>
	<td colspan="2">
		 <span>* </span>- {T_KO_REQ} 
	</td>
</tr>
 
</table>

<input type="reset" name="Submit2" value="{T_WYCZYSC}" class="pushb"  /><input type="submit" name="Submit" value="{T_WYSLIJ}" class="pushb" onclick="return check()"  />

</form>
<!--/{IT_FORM}-->
<!--{IT_INFO}-->
<h2>{T_KO_TYTUL}</h2>
<br/><br/>{T_KO_POTWIERDZ}
<!--/{IT_INFO}-->