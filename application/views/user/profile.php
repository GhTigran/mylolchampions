<form>
<div class="row">
    <div class="col-sm-12">
        <div id="summoners" class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Summoners
                </h3>
            </div>
            <div class="panel-body">
                <div id="summoners-container">
                    <table class="table table-bordered table-striped">
                    <? if(!empty($summoners)) : ?>
                        <tr>
                            <th>Name</th>
                            <th style="width: 130px;">Actions</th>
                        </tr>
                        <? foreach($summoners as $summoner) : ?>
                        <tr<? if($summoner->active) : ?> class="success"<? endif; ?>>
                            <td>
                                <a href="<?= base_url('summoner/'.$summoner->region .'/'. $summoner->name) ?>" title="<?= $summoner->name .' ('. $summoner->region .')' ?>">
                                    <?= $summoner->name .' ('. $summoner->region .')' ?>
                                </a>
                            </td>
                            <td>
                                <input type="hidden" name="sid" value="<?= $summoner->sid ?>" />
                                <input type="hidden" name="sname" value="<?= $summoner->name ?>" />
                                <input type="hidden" name="sregion" value="<?= $summoner->region ?>" />
                                <input type="hidden" name="verification_key" value="<?= $summoner->verification_key ?>" />
                                <? if(!$summoner->active) : ?>
                                <input type="button" class="btn btn-primary btn-sm verify-button" value="Verify" />
                                <? endif ?>
                                <input type="button" class="btn btn-danger btn-sm unlink-summoner" value="Unlink" />
                            </td>
                        </tr>
                        <? endforeach ?>
                    <? endif ?>
                    </table>
                </div>
                <div class="form-inline col-sm-12">
                    <div class="form-group">
                        <input type="text" id="new-summoner-name" name="new-summoner-name" class="form-control" />
                        <select name="new-summoner-region" id="new-summoner-region" class="form-control">
                            <option value="br">Brazil</option>
                            <option value="eune">Europe Nordic & East</option>
                            <option value="euw">Europe West</option>
                            <option value="lan">Latin America North</option>
                            <option value="las">Latin America South</option>
                            <option value="na" selected="selected">North America</option>
                            <option value="oce">Oceania</option>
                        </select>
                        <input type="button" name="add-summoner" id="add-summoner" value="Add summoner" class="btn btn-primary" />
                    </div>
                    <img src="<?= base_url('img/ajax-loader2.gif') ?>" title="Processing..." class="loader pull-right" id="add-summoner-loader" />
                </div>
            </div>
        </div>
    </div>
</div>
</form>

<div id="verification-dialog" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Summoner verification</h4>
            </div>
            <div class="modal-body">
                <p>Please rename one of masteries for summoner <i id="sname" class="text-info"></i> to <b id="verification_key" class="text-info"><?= $summoners[0]->verification_key ?></b> and press Verify button.</p>
            </div>
            <div class="modal-footer">
                <div class="form-inline pull-right">
                    <button class="btn btn-default" data-dismiss="modal"> Cancel </button>
                    <button id="verify-summoner" class="btn btn-success"> Verify </button>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="sid" id="sid" />
<input type="hidden" name="sregion" id="sregion" />
<input type="hidden" name="sverkey" id="sverkey" />

<div id="fail-dialog" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Verification failed</h4>
            </div>
            <div class="modal-body">
                <p>It seems data is not updated yet. Please make sure you renamed one of your mastery pages to <b class="text-info" id="fail-ver-key"><?= $summoners[0]->verification_key ?></b> and try again.</p>
            </div>
            <div class="modal-footer">
                <div class="form-inline pull-right">
                    <button class="btn btn-default" data-dismiss="modal"> Close </button>
                </div>
            </div>
        </div>
    </div>
</div>