<form action="" method="post" class="form-horizontal">
<fieldset>
    <legend>
        Login
    </legend>
    <div class="form-group">
        <label for="username" class="control-label col-sm-3">
            Username: <?= $this->load->view('template/blocks/required') ?>
        </label>
        <div class="col-sm-5">
            <input class="form-control" type="text" name="username" id="username"<? if(!empty($username)) : ?> value="<?= $username?>"<? endif ?> required />
        </div>
    </div>
    <div class="form-group">
        <label for="pass" class="control-label col-sm-3">
            Password: <?= $this->load->view('template/blocks/required') ?>
        </label>
        <div class="col-sm-5">
            <input class="form-control" type="password" name="pass" id="pass" required />
        </div>
    </div>
    <div class="form-group">
        <label for="save_user" class="control-label col-sm-3">
            Remember me:
        </label>
        <div class="col-sm-5">
            <div class="checkbox">
                <input type="checkbox" name="save_user" id="save_user" />
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <input type="submit" name="login" value="Login" class="btn btn-primary pull-right" />
        </div>
    </div>
</fieldset>
</form>