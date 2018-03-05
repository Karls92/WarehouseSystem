$(document).ready(function()
{
    $(document.body).on('click','.show_image_preview',function(e)
        {
            e.preventDefault();

            var src = $(this).attr('src');

            $('#image_preview_modal').modal();
            $('#image_preview_modal img').attr('src',src);
        }
    );

    $(document.body).on('click','.confirmation_delete_modal',function(e)
        {
            e.preventDefault();

            var delete_id = $(this).attr('id').split('_')[1];

            $('#confirmation_delete_modal').modal();
            $('#delete_btn').data('id',delete_id);
        }
    );

    $(document.body).on('click','#delete_btn',function(e)
        {
            e.preventDefault();

            $('#confirmation_delete_modal').modal('hide');
            $('#charging_modal').modal();

            location.href = ajax_url_delete+$(this).data('id');
        }
    );
});