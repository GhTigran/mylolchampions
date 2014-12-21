<? if($this->session->userdata('loggedIn')): ?>
    <p class="pull-right text-primary">
        Logged in as <b><?= $this->session->userdata('username') ?></b>
    </p>
    <div class="btn-group pull-right">
        <a class="btn btn-sm btn-primary" href="<?= base_url('user/profile') ?>" title="Profile page">Profile page</a>
        <a class="btn btn-sm btn-primary" href="<?= base_url('user/logout') ?>" title="Log out">Log out</a>
    </div>
<? else: ?>
    <div class="btn-group pull-right">
        <a href="<?= base_url('user/reg') ?>" title="Register" class="btn btn-sm btn-primary">Register</a>
        <a href="<?= base_url('user/login') ?>" title="Login" class="btn btn-sm btn-primary">Login</a>
    </div>
<? endif ?>
