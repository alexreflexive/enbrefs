function validate_form() 
{
    var inputs = document.getElementsByTagName('input')
    var errors = ''
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
        alert('Le formulaire est valide !')
        return true;
    } else 
    {
        alert(errors);
        return false;
    }
}


