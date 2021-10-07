<?php echo view("estimates/comment_form"); ?>

<script type="text/javascript">
    $(document).ready(function () {
        var uploadUrl = "<?php echo get_uri("estimates/upload_file"); ?>";
        var validationUrl = "<?php echo get_uri("estimates/validate_estimate_file"); ?>";

        var decending = "<?php echo $sort_as_decending; ?>";

        var dropzone = attachDropzoneWithForm("#estimate-comment-dropzone", uploadUrl, validationUrl);

        $("#comment-form").appForm({
            isModal: false,
            onSuccess: function (result) {
                $("#description").val("");

                if (decending) {
                    $(result.data).insertAfter("#comment-form-container");
                } else {
                    $(result.data).insertBefore("#comment-form-container");
                }

                appAlert.success(result.message, {duration: 10000});

                dropzone.removeAllFiles();
            }
        });
    });
</script>