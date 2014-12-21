<? if(!empty($basic_info)): ?>
    <input type="hidden" name="group-champs-<?= $basic_info->cgid ?>" class="group-champs" value="<?= $basic_info->champions ?>" />
    <input type="hidden" name="group-name-<?= $basic_info->cgid ?>" class="group-name" value="<?= $basic_info->title ?>" />
    <input type="hidden" name="group-id-<?= $basic_info->cgid ?>" class="group-id" value="<?= $basic_info->cgid ?>" />
    <input type="hidden" name="group-access-<?= $basic_info->cgid ?>" class="group-access" value="<?= $basic_info->access ?>" />
<? endif ?>
<? if(!empty($champions)) : ?>
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
        </tr>
        </thead>
        <tbody>
        <? foreach($champions as $champion): ?>
            <tr>

                <td data-sort="<?= $champion->name ?>">
                    <img src="<?= base_url('img/champion_icons/'. clearedName($champion->name) .'.png') ?>" title="<?= $champion->name ?>" class="champ-icon" /><?= $champion->name ?>
                </td>

                <? $games = !empty($champion->games)?$champion->games : '-' ?>
                <td data-sort="<?= $games ?>">
                    <?= $games ?>
                </td>

                <? $wins = !empty($champion->wins)?$champion->wins : '-' ?>
                <td class="text-success" data-sort="<?= $wins ?>">
                    <?= $wins ?>
                </td>

                <? $losses = !empty($champion->losses)?$champion->losses : '-' ?>
                <td class="text-danger" data-sort="<?= $losses ?>">
                    <?= $losses ?>
                </td>

                <? $win_per = !empty($champion->win_per)?round($champion->win_per, 1) : '-' ?>
                <td class="text-primary" data-sort="<?= $win_per ?>">
                    <?= ($win_per == '-' ? $win_per : $win_per.'%') ?>
                </td>

                <? $kills = !empty($champion->games)?round($champion->kills / $champion->games, 1) : '-' ?>
                <td class="text-success" data-sort="<?= $kills ?>">
                    <?= $kills ?>
                </td>

                <? $deaths = !empty($champion->games)?round($champion->deaths / $champion->games, 1) : '-' ?>
                <td class="text-danger" data-sort="<?= $deaths ?>">
                    <?= $deaths ?>
                </td>

                <? $assists = !empty($champion->games)?round($champion->assists / $champion->games, 1) : '-' ?>
                <td class="text-primary" data-sort="<?= $assists ?>">
                    <?= $assists ?>
                </td>
                <? $kda = !empty($champion->games)?round(($kills + $assists) / (($deaths)?$deaths:1), 2) : '-'; ?>
                <td class="text-primary" data-sort="<?= $kda ?>"> <?= ($kda !== '-')?$kda.':1':$kda ?> </td>
            </tr>
        <? endforeach ?>
        </tbody>
    </table>
<? else: ?>
<i>No data matching your search options.</i>
<? endif ?>