<?php echo form_open(get_uri("vault/save"), array("id" => "item-form", "class" => "general-form", "role" => "form")); ?>
<div class="modal-body clearfix">
    <input type="hidden" name="id" value="<?php echo $model_info->id; ?>" />
    <input type="hidden" name="client_id" value="<?php echo $client_id; ?>" />

    <div class="form-group">
        <label for="title" class=" col-md-3"><?php echo app_lang('title'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "title",
                "name" => "title",
                "value" => $model_info->title,
                "class" => "form-control validate-hidden",
                "placeholder" => app_lang('title'),
                "autofocus" => true,
                "data-rule-required" => true,
                "data-msg-required" => app_lang("field_required"),
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="description" class="col-md-3"><?php echo app_lang('description'); ?></label>
        <div class=" col-md-9">
            <?php
            echo form_textarea(array(
                "id" => "description",
                "name" => "description",
                "value" => $model_info->description ? $model_info->description : "",
                "class" => "form-control",
                "placeholder" => app_lang('description'),
                "data-rich-text-editor" => true
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="vault_user" class=" col-md-3"><?php echo app_lang('vault_user'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "vault_user",
                "name" => "username",
                "value" => $model_info->username,
                "class" => "form-control",
                "placeholder" => app_lang('vault_user')
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="vault_pass" class=" col-md-3"><?php echo app_lang('vault_pass'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "vault_pass",
                "name" => "password",
                "type" => "password",
                "value" => $model_info->password,
                "class" => "form-control",
                "placeholder" => app_lang('vault_pass')
            ));
            ?>
        </div>
    </div>
    <div class="form-group">
        <label for="vault_pass_pin" class=" col-md-3"><?php echo app_lang('vault_pass_pin'); ?></label>
        <div class="col-md-9">
            <?php
            echo form_input(array(
                "id" => "vault_pass_pin",
                "name" => "passPin",
                "class" => "form-control",
                "placeholder" => app_lang('vault_pass_pin')
            ));
            ?>
        </div>
    </div>

</div>

<div class="modal-footer">
    <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo lang('close'); ?></button>
    <button type="submit" class="btn btn-primary"><span data-feather="check-circle" class="icon-16"></span> <?php echo lang('save'); ?></button>
</div>
<?php echo form_close(); ?>

<script type="text/javascript">
    $(document).ready(function () {
        $("#item-form").appForm({
            onSuccess: function (result) {
                $("#vault-table").appTable({newData: result.data, dataId: result.id});
            }
        });
    });
</script>