<div class="card rounted-bottom">
    <div class="tab-title clearfix">
        <h4><?php echo app_lang('vault'); ?></h4>
        <div class="title-button-group">
            <?php echo modal_anchor(get_uri("vault/modal_form"), "<i data-feather='plus-circle' class='icon-16'></i> " . app_lang('add_vault_item'), array("class" => "btn btn-default", "title" => app_lang('add_vault_item'), "data-post-client_id" => $client_id)); ?>           
        </div>
    </div>
    <div class="table-responsive">
        <table id="vault-table" class="display" cellspacing="0" width="100%">            
        </table>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {
        $("#vault-table").appTable({
            source: '<?php echo_uri("vault/list_data/" . $client_id) ?>',
            order: [[0, 'desc']],
            columns: [
                {targets: [1], visible: false},
                {title: '<?php echo app_lang("title"); ?>'},
                {title: '<?php echo app_lang("description"); ?>'},
                {title: '<?php echo app_lang("vault_user"); ?>'},
                {title: '<i data-feather="menu" class="icon-16"></i>', "class": "text-center option w100"}
            ]
        });
    });
</script>