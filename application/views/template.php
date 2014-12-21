<!Doctype html>
<html>
    <? $this->load->view('template/header') ?>

    <div id="main" class="row">
        <div id="left-bar" class="col-sm-3">
            <? $this->load->view('template/leftbar'); ?>
        </div>
        <div id="main-bar" class="col-sm-9">
            <div id="alerts">
                <? $alerts = $this->session->userdata('alerts'); ?>
                <? if(!empty($alerts)) : ?>
                    <? foreach($alerts as $alert) : ?>
                    <div class="alert alert-<?= $alert['status'] ?>">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <p><?= $alert['message'] ?></p>
                    </div>
                    <? endforeach ?>
                    <? $this->session->unset_userdata('alerts'); ?>
                <? endif ?>
            </div>

            <? $this->load->view($page); ?>
	    <br /> <br />
            <? $this->load->view('template/blocks/horizontalad') ?>
        </div>
        <? $this->load->view('template/blocks/rightsidead'); ?>
    </div>
    <? $this->load->view('template/footer'); ?>
</body>
</html>