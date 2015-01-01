<? if(!empty($basicInfo)): ?>
    <input type="hidden" name="group-champs-<?= $basicInfo->cgid ?>" class="group-champs" value="<?= $basicInfo->champions ?>" />
    <input type="hidden" name="group-name-<?= $basicInfo->cgid ?>" class="group-name" value="<?= $basicInfo->title ?>" />
    <input type="hidden" name="group-id-<?= $basicInfo->cgid ?>" class="group-id" value="<?= $basicInfo->cgid ?>" />
    <input type="hidden" name="group-access-<?= $basicInfo->cgid ?>" class="group-access" value="<?= $basicInfo->access ?>" />
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

                <? $winPer = !empty($champion->win_per)?round($champion->win_per, 1) : '-' ?>
                <td class="text-primary" data-sort="<?= $winPer ?>">
                    <?= ($winPer == '-' ? $winPer : $winPer.'%') ?>
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