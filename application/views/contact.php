<h2> Contact Us</h2>
<p>
    If you have some comments or concerns or ideas on how to make our site better, feel free to
    contact us using the following form.
</p>
<br /><br />
<form action="" method="post" class="form-horizontal">
    <div class="form-group">
        <label for="email" class="control-label col-sm-3">
            E-mail Address: <?= $this->load->view('template/blocks/required') ?>
        </label>
        <div class="col-sm-5">
            <input class="form-control" type="email" name="email" id="email" placeholder="Your e-mail address" required />
        </div>
    </div>
    <div class="form-group">
        <label for="message" class="control-label col-sm-3">
            Message: <?= $this->load->view('template/blocks/required') ?>
        </label>
        <div class="col-sm-5">
            <textarea class="form-control" rows="5" name="message" id="message" placeholder="Comments, concerns or suggestions..." required></textarea>
        </div>
    </div>
    <div class="col-sm-3">
        <input type="submit" name="send" value="Send" class="btn btn-primary pull-right" />
    </div>
</form>
