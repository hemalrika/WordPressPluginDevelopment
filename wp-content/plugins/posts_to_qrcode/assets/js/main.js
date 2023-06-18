;(function($) {
    $('#toggle1').minitoggle();
    $('#toggle1').on("toggle", function(e){
        if (e.isActive)
            $('#toggle_input').val(1);
        else
            $('#toggle_input').val(0);
    });
})(jQuery)