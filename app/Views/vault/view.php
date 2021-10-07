<div class="modal-body clearfix general-form">
<div class="form-group">

<fieldset>
    <legend><?php echo $model_info->title; ?></legend>
    <div class="col-md-12">
      <label for="disabledUserInput" class="form-label">Username</label>
      <input type="text" id="disabledUserInput" class="form-control" placeholder="Disabled input" value="<?php echo $model_info->username; ?>" disabled="disabled">
    </div>
    <div class="col-md-12">
        <div class="row">
            <div class="col-sm-6">
                <label for="disabledPwdInput" class="form-label">Password</label>
            </div>
            <div class="col-sm-4">
                <label for="inputPin" class="form-label">Encryption PIN</label>
            </div>
        </div>

      <div class="row">
        <div class="col-sm-6">
            <input type="password" id="disabledPwdInput" class="form-control" placeholder="Disabled input" value="<?php echo $model_info->password; ?>" disabled="disabled">
        </div>
        <div class="col-sm-4">
            <input id="inputPin" class="form-control" placeholder="Enter PIN to Unlock">
        </div>
        <div class="col-sm-2">
            <a href="#" onclick="copyPwd()"><i data-feather="unlock" class="icon-16"></i></a>
        </div>
    </div>
  </fieldset>
</br>
  <div  class="col-md-12 mt15">
            <strong>Description</strong>
        </div>
    </div>

    <div class="col-md-12 mb15 notepad">
        <?php
        echo $model_info->description;
        ?>
    </div>

    <div class="col-md-12 mb15">
        <label id="msgText">

    </div>


</div>
    <div class="modal-footer">
        <div class="row">
            <div class="col-sm-12">
                <button type="button" class="btn btn-default" data-bs-dismiss="modal"><span data-feather="x" class="icon-16"></span> <?php echo app_lang('close'); ?></button>
            </div>
          </div>
  </div>
</div>

<script>
    function copyPwd() {
        let pwdPin = document.getElementById("inputPin").value;
        let msgText = document.getElementById("msgText");


        $.ajax({
            url:"/index.php/Vault/getPassword",    //the page containing php script
            type: "post",    //request type,
            dataType: 'json',
            data: {id: "<?php echo $model_info->id; ?>", pin: pwdPin},
            success:function(result){
                if(result.data == "Invalid PIN") {
                    msgText.innerHTML = "<font color=red>You entered an invalid PIN</font>";
                } else {
                    let decpwd = result.data;
                    navigator.permissions.query({name: "clipboard-write"}).then(result => {
                        if (result.state == "granted" || result.state == "prompt") {
                            navigator.clipboard.writeText(decpwd)
                        }
                    });
                    msgText.innerHTML = "<font color=green>The password has been copied to your clipboard.</font>";
                }
            },
            error:function(){
                msgText.innerHTML = result.data;

            }
        });

    }
</script>