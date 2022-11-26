<?php
get_header();
?>
    <div class="container">
        <div class="row">
            <div class="col align-middle my-5 text-center">
                <button type="button" class="btn btn-primary ajax-test-1">Test Ajax</button>
                <div class="alert alert-warning" role="alert">

                </div>
            </div>
        </div>
    </div>

    <script>
        (function ($){
            $('.ajax-test-1').on('click',function(){
                $.post(
                    Ajax.ajaxurl,
                    {
                        action: 'test-ajax-1',
                        Nonce: Ajax.nonce
                    },
                    function (response) {
                        $('.alert').text(JSON.stringify(response));
                    }
                );
            });
        })(jQuery)
    </script>
<?php
get_footer();