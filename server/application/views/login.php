<style>
    html {
        height:90%;
    }
    body, body > #container {
        height:100%;
    }
</style>

<div class="uk-vertical-align uk-text-center uk-height-1-1">
    <div class="uk-vertical-align-middle" style="width: 250px;">

        
        <form action='<?php echo base_url("/login/auth/")?>' method='post' accept-charset='utf-8' class="uk-panel uk-panel-box uk-form">
            <div class="uk-form-row">
                <div class="uk-form-icon">
                    <i class="uk-icon-user"></i>
                    <input type="text" name="identity" id="identity" class="form-control uk-form-large" placeholder="Username" required autofocus>
                </div>
            </div>
            <div class="uk-form-row">
                <div class="uk-form-icon">
                    <i class="uk-icon-lock"></i>
                    <input type="password" name="password" id="password" class="form-control uk-form-large" placeholder="Password" required="">
                </div>
            </div>
            <div class="uk-form-row">
                <button id='signin-button' class="login-button uk-align-center uk-button uk-button-primary uk-width-1-1" type="submit" name="submit" value="Login">Login</button>
            </div>

            <!--
            <div class="uk-form-row uk-text-small">
                <label class="uk-float-left"><input type="checkbox"> Remember Me</label>
                <a class="uk-float-right uk-link uk-link-muted" href="#">Forgot Password?</a>
            </div>
            -->
        </form>

    </div>
</div>