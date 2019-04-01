(function ($) {

    $('body').on('click', '.subscribe', function (e) {
        e.preventDefault();
        var u_id = $(this).data('user-id');
        var wrap = $(this).parent('div');
        $(this).html('<span class="spinner-grow spinner-grow-sm"></span>')
            $.ajax({
                url: $(this).attr('href'),
                type: 'GET',
                // Get form values to post everything
                data: {'sub_id' : u_id},
                success: function (data) {
                    if(1 === data.success){
                        $(e.target).parent('div').html(data.link);
                    }


                    console.log(e);

                },
                fail: function () {
                    alert('Ajax Error!')
                }
            });
    });

})(jQuery);