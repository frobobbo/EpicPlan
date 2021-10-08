<?php echo form_open(get_uri("tickets/save_batch_merge"), array("id" => "batch-merge-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <div class="container-fluid">
        <input type="hidden" name="ticket_ids" value="<?php echo $ticket_ids; ?>" />
        <input type="hidden" name="batch_fields" value="" id="batch_fields" />

        <div class="form-group">
            <div class="row">
                <div class="col-md-4">
                    <?php
                    echo "Select Master Ticket:";
                    ?>
                </div>
                <div class="col-md-8">
                    <?php
                    echo form_dropdown("ticket_type_id", $tickets_dropdown, "", "class='select2'");
                    ?>
                </div>
            </div>
        </div>


<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span class="fa fa-close"></span> <?php echo app_lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span class="fa fa-check-circle"></span> <?php echo app_lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#batch-merge-form .select2").select2();
    })

</script>