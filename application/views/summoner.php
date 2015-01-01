<? if(!empty($summoner)) : ?>
    <div id="content">
        <div id="profile-header">
            <div class="row">
                <div class="col-sm-9 media">
                    <img src="<?= base_url('img/profile_icons/'. $summoner->profileIconId .'.jpg') ?>" title="<?= $summoner->name ?>" class="pull-left" />
                    <div class="media-body">
                        <h4 class="media-heading">
                            <?= $summoner->name ?>
                        </h4>
                        <p>
                            <?= strtoupper($summoner->region) ?> - lvl <?= $summoner->summonerLevel ?>
                        </p>
                    </div>
                </div>
                <div id="badge" class="col-sm-3 pull-right">
                    <? //ToDo placement matches ?>

                    <? if($summoner->summonerLevel == 30): ?>
                        <? if($summoner->leagueData) : ?>
                            <img src="<?= base_url(); ?>img/badges/<?= strtolower($summoner->leagueData->tier) . '_' . $arabicNumbers[$summoner->leagueData->rank] ?>.png" title="<?= $summoner->leagueData->tier . '_' . $arabicNumbers[$summoner->leagueData->rank] ?>" class="pull-right" />
                            <div id="league-rank" class="pull-right"><?=  $summoner->leagueData->rank ?></div>
                        <? else : ?>
                            <img src="<?= base_url('img/badges/placing.png') ?>" title="Placing" class="pull-right" />
                        <? endif ?>
                    <? else : ?>
                        <img src="<?= base_url('img/badges/unranked.png') ?>" title="Not ranked" class="pull-right" />
                    <? endif ?>
                </div>
            </div>
        </div>

        <? if(!empty($ownsSummoner)): ?>
        <div class="form-group pull-right">
            <button id="add-group-button" class="btn btn-primary"> Add champion group </button>
        </div>
        <div class="clearfix"></div>
        <? endif ?>
        <div class="panel-group" id="group-list">
            <div class="panel panel-primary hidden" id="group-search">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#group-list" href="#collapse-search">
                            Search results
                        </a>
                    </h4>
                </div>
                <div id="collapse-search" class="panel-collapse collapse">
                    <div class="panel-body"></div>
                </div>
            </div>
            <? if($championGroups): ?>
                <? foreach($championGroups as $championGroup): ?>
            <div class="panel panel-primary" id="group-<?= $championGroup->cgid ?>">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#group-list" href="#collapse-<?= $championGroup->cgid ?>">
                            <?= $championGroup->title ?>
                        </a>
                    </h4>
                    <? if(!empty($ownsSummoner)): ?>
                        <div class="pull-right btn-group btn-group-sm">
                            <button class="btn btn-default edit-group-button" title="Edit group">
                                <span class="glyphicon glyphicon-cog"></span>
                            </button>
                            <button class="btn btn-danger delete-group-button" title="Delete group">
                                <span class="glyphicon glyphicon-trash"></span>
                            </button>
                        </div>
                    <? endif ?>
                </div>
                <div id="collapse-<?= $championGroup->cgid ?>" class="panel-collapse collapse">
                    <div class="panel-body">
                        <input type="hidden" name="group-champs-<?= $championGroup->cgid ?>" class="group-champs" value="<?= $championGroup->champions ?>" />
                        <input type="hidden" name="group-name-<?= $championGroup->cgid ?>" class="group-name" value="<?= $championGroup->title ?>" />
                        <input type="hidden" name="group-access-<?= $championGroup->cgid ?>" class="group-access" value="<?= $championGroup->access ?>" />
                        <input type="hidden" name="group-id-<?= $championGroup->cgid ?>" class="group-id" value="<?= $championGroup->cgid ?>" />
                        <? if($championGroup->champions): ?>
                            <table class="tablesorter champions-list">
                                <thead>
                                <tr>
                                    <th> Champion </th>
                                    <th> Games </th>
                                    <th> Wins </th>
                                    <th> Losses </th>
                                    <th> Win % </th>
                                    <th> Kills </th>
                                    <th> Deaths </th>
                                    <th> Assists </th>
                                    <th> KDA </th>
                                    <?/*
                                    <th> Avg. gold (k) </th>
                                    <th> CS </th>
                                    <th> Double kills </th>
                                    <th> Triple kills </th>
                                    <th> Quadra kills </th>
                                    <th> Penta kills </th>
                                    */?>
                                </tr>
                                </thead>
                                <tbody>
                                <? $champIds = explode(',', $championGroup->champions) ?>
                                <? foreach($champIds as $champId): ?>
                                    <tr>
                                        <td data-sort="<?= $champions[$champId]->name ?>">
                                            <img src="<?= base_url('img/champion_icons/'. clearedName($champions[$champId]->name) .'.png') ?>" title="<?= $champions[$champId]->name ?>" class="champ-icon" />
                                            <?= $champions[$champId]->name ?>
                                        </td>

                                        <? $games = !empty($rankedStats[$champId]->games)?$rankedStats[$champId]->games : '-' ?>
                                        <td data-sort="<?= $games ?>">
                                            <?= $games ?>
                                        </td>

                                        <? $wins = !empty($rankedStats[$champId]->wins)?$rankedStats[$champId]->wins : '-' ?>
                                        <td class="text-success" data-sort="<?= $wins ?>">
                                            <?= $wins ?>
                                        </td>

                                        <? $losses = !empty($rankedStats[$champId]->losses)?$rankedStats[$champId]->losses : '-' ?>
                                        <td class="text-danger" data-sort="<?= $losses ?>">
                                            <?= $losses ?>
                                        </td>

                                        <? $winPer = !empty($rankedStats[$champId]->win_per)?round($rankedStats[$champId]->win_per, 1) : '-' ?>
                                        <td class="text-primary" data-sort="<?= $winPer ?>">
                                            <?= ($winPer !== '-') ? $winPer . '%' : $winPer?>
                                        </td>

                                        <? $kills = !empty($rankedStats[$champId]->games)?round($rankedStats[$champId]->kills / $rankedStats[$champId]->games, 1) : '-' ?>
                                        <td class="text-success" data-sort="<?= $kills ?>">
                                            <?= $kills ?>
                                        </td>

                                        <? $deaths = !empty($rankedStats[$champId]->games)?round($rankedStats[$champId]->deaths / $rankedStats[$champId]->games, 1) : '-' ?>
                                        <td class="text-danger" data-sort="<?= $deaths ?>">
                                            <?= $deaths ?>
                                        </td>

                                        <? $assists = !empty($rankedStats[$champId]->games)?round($rankedStats[$champId]->assists / $rankedStats[$champId]->games, 1) : '-' ?>
                                        <td class="text-primary" data-sort="<?= $assists ?>">
                                            <?= $assists ?>
                                        </td>
                                        <? $kda = !empty($rankedStats[$champId]->games)?round(($kills + $assists) / (($deaths)?$deaths:1), 2) : '-'; ?>
                                        <td class="text-primary" data-sort="<?= $kda ?>"> <?= ($kda !== '-')?$kda.':1':$kda ?> </td>
                                        <?/*
                                        <td> <?= !empty($rankedStats[$champId]->games)?round($rankedStats[$champId]->gold_earned / $rankedStats[$champId]->games / 1000, 1) : '-' ?> </td>
                                        <td> <?= !empty($rankedStats[$champId]->games)?round($rankedStats[$champId]->cs / $rankedStats[$champId]->games) : '-' ?> </td>
                                        <td> <?= !empty($rankedStats[$champId]->double_kills)?$rankedStats[$champId]->double_kills : '-' ?> </td>
                                        <td> <?= !empty($rankedStats[$champId]->triple_kills)?$rankedStats[$champId]->triple_kills : '-' ?> </td>
                                        <td> <?= !empty($rankedStats[$champId]->quadra_kills)?$rankedStats[$champId]->quadra_kills : '-' ?> </td>
                                        <td> <?= !empty($rankedStats[$champId]->penta_kills)?$rankedStats[$champId]->penta_kills : '-' ?> </td>
                                        */?>
                                    </tr>
                                <? endforeach; ?>
                                </tbody>
                            </table>
                        <? else: ?>
                            No champions chosen for this group
                        <? endif ?>
                    </div>
                </div>
            </div>
                <? endforeach; ?>
            <? endif; ?>
            <? if(!empty($rankedStats)) : ?>
            <div class="panel panel-primary" id="group-ranked-all">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#group-list" href="#collapse-ranked-all">
                            Champions played in Ranked Solo Queue
                        </a>
                    </h4>
                </div>
                <div id="collapse-ranked-all" class="panel-collapse collapse">
                    <div class="panel-body">
                        <table class="tablesorter champions-list">
                            <thead>
                                <tr>
                                    <th> Champion </th>
                                    <th> Games </th>
                                    <th> Wins </th>
                                    <th> Losses </th>
                                    <th> Win % </th>
                                    <th> Kills </th>
                                    <th> Deaths </th>
                                    <th> Assists </th>
                                    <th> KDA </th>
                                    <?/*
                                    <th> Avg. gold (k) </th>
                                    <th> CS </th>
                                    <th> Double kills </th>
                                    <th> Triple kills </th>
                                    <th> Quadra kills </th>
                                    <th> Penta kills </th>
                                    */?>
                                </tr>
                            </thead>
                            <tbody>
                            <? foreach($rankedStats as $stats): ?>
                                <tr>
                                    <td data-sort="<?= $champions[$stats->chid]->name ?>">
                                        <img src="<?= base_url('img/champion_icons/'. clearedName($champions[$stats->chid]->name) .'.png') ?>" title="<?= $champions[$stats->chid]->name ?>" class="champ-icon" />
                                        <?= $champions[$stats->chid]->name ?>
                                    </td>
                                    <td data-sort="<?= $stats->games ?>"> <?= $stats->games ?> </td>
                                    <td class="text-success" data-sort="<?= $stats->wins ?>"> <?= $stats->wins ?> </td>
                                    <td class="text-danger" data-sort="<?= $stats->losses ?>"> <?= $stats->losses ?> </td>
                                    <td class="text-primary" data-sort="<?= $stats->win_per ?>"> <?= round($stats->win_per, 1) ?>% </td>
                                    <td class="text-success" data-sort="<?= $stats->kills / $stats->games ?>"> <?= round($stats->kills / $stats->games, 1) ?> </td>
                                    <td class="text-danger" data-sort="<?= $stats->deaths / $stats->games ?>"> <?= round($stats->deaths / $stats->games, 1) ?> </td>
                                    <td class="text-primary" data-sort="<?= $stats->assists / $stats->games ?>"> <?= round($stats->assists / $stats->games, 1) ?> </td>
                                    <? $kda = round(($stats->kills + $stats->assists) / (($stats->deaths)?$stats->deaths:1), 2); ?>
                                    <td class="text-primary" data-sort="<?= $kda ?>"> <?= ($kda !== '-')?$kda.':1':$kda ?> </td>
                                    <?/*
                                    <td> <?= round($stats->gold_earned / $stats->games / 1000, 1) ?> </td>
                                    <td> <?= round($stats->cs / $stats->games) ?> </td>
                                    <td> <?= $stats->double_kills ?> </td>
                                    <td> <?= $stats->triple_kills ?> </td>
                                    <td> <?= $stats->quadra_kills ?> </td>
                                    <td> <?= $stats->penta_kills ?> </td>
                                    */?>
                                </tr>
                            <? endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <? endif ?>
        </div>
    </div>

    <div id="edit-group-dialog" class="modal fade">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Add / Edit Champions Group</h4>
                </div>
                <div class="modal-body">
                    <form id="edit-group-form">
                        <div class="form-inline form-group">
                            <label for="group-name" class="control-label">
                                Group name:
                            </label>
                            <input type="text" name="group_name" id="group-name" required class="form-control" />
                            <label for="group-name" class="control-label">
                                Visibility:
                            </label>
                            <select name="group_access" id="group-access" required class="form-control">
                                <option value="private"> Private </option>
                                <option value="public"> Public </option>
                            </select>
                            <input type="hidden" name="group_id" id="group-id" />
                            <input type="submit" name="group_name" id="group-name" value="Save" class="btn btn-success pull-right" />
                        </div>
                        <div id="all-champions">
                            <div class="row">
                            <? $i = 0; ?>
                            <? foreach($champions as $champion): ?>
                                <div class="col-sm-2">
                                    <img src="<?= base_url('img/champion_icons/'. clearedName($champion->name) .'.png') ?>" title="<?= $champion->name ?>" class="champ-icon desaturate" for="group-champion-<?= $champion->chid ?>" />
                                    <input type="checkbox" name="group_champions[<?= $champion->chid ?>]" class="select-champion-checkbox" id="group-champion-<?= $champion->chid ?>" />
                                </div>
                                <? if(++$i % 6 == 0) : ?>
                            </div>
                            <div class="row">
                                <? endif ?>
                            <? endforeach ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div id="group-delete-confirm-dialog" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                    <h4 class="modal-title">Delete Group</h4>
                </div>
                <div class="modal-body">
                    <p><span class="glyphicon glyphicon-warning-sign text-danger"></span> This group will be permanently deleted and cannot be recovered. Are you sure?</p>
                </div>
                <div class="modal-footer">
                    <div class="form-inline pull-right">
                        <button class="btn btn-default" data-dismiss="modal"> Cancel </button>
                        <button id="delete-group" class="btn btn-danger"> Delete </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="sid" name="sid" value="<?= $summoner->sid ?>" />
    <input type="hidden" id="region" name="region" value="<?= $summoner->region ?>" />
<? else : ?>
<h2>Oops...</h2>
<p>Sorry, we can't find summoner with summoner name and server you provided. Please make sure you made no mistake in summoner name and try again. If you still can't find your desired summoner,
please feel free to contact our support team
<a href="<?= base_url('contact') ?>" title="Contact Us">here</a>.
</p>
<? endif ?>