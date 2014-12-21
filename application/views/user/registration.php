<form action="" method="post" class="form-horizontal" id="reg-form">
<fieldset>
    <legend>Registration</legend>
    <div class="row form-group">
        <label for="username" class="col-sm-3 control-label">
            Username: <?= $this->load->view('template/blocks/required') ?>
        </label>
        <div class="col-sm-5">
            <input type="text" name="username" id="username" class="form-control" />
        </div>
        <div class="col-sm-4">
            <span class="help-block"></span>
        </div>
    </div>
    <div class="row form-group">
        <label for="email" class="col-sm-3 control-label">
            Email: <?= $this->load->view('template/blocks/required') ?>
        </label>
        <div class="col-sm-5">
            <input type="text" name="email" id="email" class="form-control" />
        </div>
        <div class="col-sm-4">
            <span class="help-block"></span>
        </div>
    </div>
    <div class="row form-group">
        <label for="pass" class="col-sm-3 control-label">
            Password: <?= $this->load->view('template/blocks/required') ?>
        </label>
        <div class="col-sm-5">
            <input type="password" name="pass" id="pass" class="form-control" />
        </div>
        <div class="col-sm-4">
            <span class="help-block"></span>
        </div>
    </div>
    <div class="row form-group">
        <label for="retype-pass" class="col-sm-3 control-label">
            Retype password: <?= $this->load->view('template/blocks/required') ?>
        </label>
        <div class="col-sm-5">
            <input type="password" name="retype_pass" id="retype-pass" class="form-control" />
        </div>
        <div class="col-sm-4">
            <span class="help-block"></span>
        </div>
    </div>
    <div class="form-group">
        <div class="col-sm-12">
            <input type="submit" name="reg" value="Register" class="btn btn-success pull-right" />
        </div>
    </div>
</fieldset>
</form>