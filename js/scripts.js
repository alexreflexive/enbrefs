function validate_form() 
{
    var inputs = document.getElementsByTagName('input') ;
    var errors = '' ;
    for(i=0; i < inputs.length; i++) 
    {
        var input = inputs[i]
        if(input.classList.contains('required')) 
        {
            // Le champ est à valider
            if(input.value == "") 
            {
                errors += 'Le champ '+input.name+' est obligatoire.\n';
                //debugger;
                input.classList.add('error');
            }
            else 
            {
            	input.classList.remove('error') ;
            }

        }
    }
    if(errors.length == 0) 
    {
        //alert('Le formulaire est valide !') ;
        return true;
    } else 
    {
        alert(errors);
        return false;
    }
}


//<label>Mot de passe : <input type="password" id="new_password" oninput="checkPasswords()"></label>
//<label>Confirmer mot de passe : <input type="password" id="confirm_password" oninput="checkPasswords()"></label>
function checkPasswords() {
  var new_password = document.getElementById('new_password');
  var confirm_password = document.getElementById('confirm_password');
  if (new_password.value != confirm_password.value) {
    confirm_password.setCustomValidity('Les mots de passe ne sont pas identiques');
  } else {
    confirm_password.setCustomValidity('');
  }
}

function test_ChampsMdp(){
    var mdp1 = document.getElementById('n_mdp1') ;
    var mdp2 = document.getElementById('n_mdp2') ;
    if(mdp1.value==mdp2.value && mdp1!=""){
        alert("test réussi");
        mdp1.classList.remove('error');
        mdp2.classList.remove('error');
        return true ;
    }else{
        alert("Echec du test") ;
        mdp1.classList.add('error');
        debugger; console.log(mdp1.classList.classList);
        mdp2.classList.add('error');
        return false ;
    }
}


