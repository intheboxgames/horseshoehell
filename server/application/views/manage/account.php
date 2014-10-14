
<h3>Manage Account</h3>
<br/>

        
<form id="account-form" action='<?php echo base_url("/users/account/edit")?>' method='post' accept-charset='utf-8' class="uk-form uk-form-horizontal">
    <input type="hidden" name="id" id="id" >
    <div class="uk-form-row">
        <label class="uk-form-label" for="email">Email</label>
        <input type="text" name="email" id="email" class="uk-form-control uk-form-blank" style="min-width:20em" value="<?php echo $user->email; ?>" required data-uk-tooltip="{delay:1000}" title="Your email address. This is also your login username">
    </div>
    <div class="uk-form-row">
        <label class="uk-form-label" for="first_name">Name</label>
        <input type="text" name="first_name" id="first_name" class="uk-form-control uk-form-blank" value="<?php echo $user->first_name; ?>" required data-uk-tooltip="{delay:1000}" title="Your name is used for logging purposes to find out who broke something if something happens to break">
        <input type="text" name="last_name" id="last_name" class="uk-form-control uk-form-blank" value="<?php echo $user->last_name; ?>" required data-uk-tooltip="{delay:1000}" title="Your name is used for logging purposes to find out who broke something if something happens to break">
    </div>
    <div class="uk-form-row">
        <label class="uk-form-label" for="password">Password</label>
        <input type="password" name="password" id="password" class="uk-form-control uk-form-blank" value="**************" required data-uk-tooltip="{delay:1000}" title="Your password">
    </div>   
    <div id="password_confirm_wrapper" class="uk-form-row" style="display:none">
        <label class="uk-form-label" for="password">Password Confirm</label>
        <input type="password" name="password_confirm" id="password_confirm" class="uk-form-control" value="**************" required data-uk-tooltip="{delay:1000}" title="This must match your password to save changes">
    </div>            

    <hr/>
    <div class="uk-form-row uk-clear-fix">
        <div class="uk-form-controls uk-float-right">                    
            <button id='submit-button' class="uk-button uk-button-primary" type="submit" name="submit" value="Submit">Save Changes</button>
        </div>
    </div>
</form>

<script>
    $(document).ready( function () {
        $("#password").on('keyup', function() {
            $("#password_confirm").val("");
            $("#password_confirm_wrapper").slideDown();
            $("#password").off('keyup');
        });
    });
</script>