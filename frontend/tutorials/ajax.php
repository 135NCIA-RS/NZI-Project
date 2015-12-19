<?php ob_start(); ?>
$(document).ready(function(){
    $(;#dodaj').ready(function(){
        $.ajax({
            url: '/koszyk/dodaj',
            success: function(response){
                alert('udalo sie');
                $('#html').html(respose);
            }
        }
    });
});

<?php $js = ob_get_clean(); ?>
