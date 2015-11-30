<div class="container">
    <form id="form-register" class="form-horizontal" action='<?=WEB.'register/'?>' method="POST">
        <fieldset>
            <div id="legend">
                <legend class="">Register</legend>
            </div>

            <div class="control-group">
                <!-- Username -->
                <label class="control-label" for="username">Username</label>
                <div class="controls">
                    <input type="text" id="_username" name="_username" placeholder="Username" class="form-control form-register-control" maxlength="15" required autofocus>
                    <p class="help-block">Username can contain any letters or numbers, without spaces between 3 and 15</p>
                </div>
            </div>

            <div class="control-group">
                <!-- Password-->
                <label class="control-label" for="_password">Password</label>
                <div class="controls">
                    <input type="password" id="_password" name="_password" placeholder="Password" class="form-control form-register-control" maxlength="20" required>
                    <p class="help-block">Password should be between 6 an 20 characters without spaces</p>
                </div>
            </div>

            <div class="control-group">
                <!-- Password -->
                <label class="control-label" for="_password_confirm">Password (Confirm)</label>
                <div class="controls">
                    <input type="password" id="_password_confirm" name="_password_confirm" placeholder="Password (Confirm)" class="form-control form-register-control" maxlength="20" required>
                    <p class="help-block">Please confirm password</p>
                </div>
            </div>

            <div class="control-group">
                <!-- Button -->
                <div class="controls">
                    <button type="submit" class="btn btn-success">Register</button>
                </div>
            </div>
            <input type="hidden" id="_csrf_token_register" name="_csrf_token_register" value="<?=$data['csrf_token_register']?>">
        </fieldset>
    </form>
</div>