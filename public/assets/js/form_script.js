$(document).ready(function()
{
	var btn = $('button[type=submit]');
	var form_inputs = $("form input,form textarea,form select");
	var something_changed = true;

    $('form').submit(function(e)
    {
        evale_inputs();

        if(btn.attr('disabled') == undefined)
        {
            btn.html('Loading <i class="fa fa-spin fa-spinner"></i>');
            btn.attr('disabled',true);

            $('#charging_modal').modal();
        }
        else
        {
            e.preventDefault();
        }
    });

    function evale_inputs() {
        var process_continue = true;

        $('form input[required],form textarea[required],form select[required]').each(function(){
            if(process_continue && ($(this).val() == null || $(this).val().length == 0))
            {
                process_continue = false;
            }
        });

        if(process_continue)
        {
            btn.attr('disabled',false);
        }
        else
        {
            btn.attr('disabled',true);
        }
    }

	form_inputs.on("change keyup paste", function(){
        something_changed = true;
    });

    form_inputs.focusout(function () 
    {
    	if(something_changed)
    	{
			evale_inputs();

			something_changed = false;
    	}
    });

    $('form div#formSubmit').mouseover(function()
    {
        if(something_changed)
    	{
			evale_inputs();

			something_changed = false;
    	}
    });

    evale_inputs();
});
