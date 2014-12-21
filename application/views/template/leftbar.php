        <? if($page == 'summoner' && !empty($summoner)): ?>
        <form id="search-champ-form" class="form-default">
            <fieldset id="search-champ-box">
                <legend>Find Champions</legend>
                    <div class="form-group">
                        <label for="search-champ-name" class="control-label">
                            Champion name:
                        </label><br />
                        <input type="text" name="champ_name" id="search-champ-name" class="form-control" />
                    </div>
                    <div class="form-group">
                        <label for="search-champ-role">
                            Primary role:
                        </label><br />
                        <select id="search-champ-role" name="champ_role" class="form-control">
                            <option value="0"> All </option>
                            <option value="assassin"> Assassin </option>
                            <option value="fighter"> Fighter </option>
                            <option value="mage"> Mage </option>
                            <option value="support"> Support </option>
                            <option value="tank"> Tank </option>
                            <option value="marksman"> Marksman </option>
                        </select>
                    </div>
                    <? if($championGroups): ?>
                        <div class="form-group">
                            <label for="search-champ-group">
                                Search in group:
                            </label><br />
                            <select id="search-champ-group" name="champ_group" class="form-control">
                                <option value="0"> All </option>
                                <? foreach($championGroups as $group): ?>
                                    <option value="<?= $group->cgId ?>"> <?= $group->title ?> </option>
                                <? endforeach ?>
                            </select>
                        </div>
                    <? endif ?>
                    <div class="form-group">
                        <label for="search-champ-order">
                            Sort By:
                        </label><br />
                        <select id="search-champ-order" name="champ_order" class="form-control">
                            <option value="name"> Champion Name </option>
                            <option value="games"> Games Played </option>
                            <option value="wins"> Wins </option>
                            <option value="random"> Random </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="search-champ-limit">
                            Show:
                        </label><br />
                        <select id="search-champ-limit" name="champ_limit" class="form-control">
                            <option value="0"> All </option>
                            <option value="1"> 1 </option>
                            <option value="3"> 3 </option>
                            <option value="5"> 5 </option>
                            <option value="10"> 10 </option>
                        </select>
                    </div>
                    <div class="form-group">
                        <input type="submit" name="search" value="Search" class="btn btn-primary" />
                        <img src="<?= base_url('img/ajax-loader.gif') ?>" title="Processing..." class="loader" />
                    </div>
            </fieldset>
        </form>

        <? endif ?>
        <div class="panel">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Free Champions
                </h3>
            </div>
            <div class="panel-body">
                <ul id="free-champs" class="list-unstyled">
                    <? foreach($this->freeChampions as $freeChamp) : ?>
                        <li>
                            <div>
                                <img src="<?= base_url('img/champion_icons/'. clearedName($freeChamp->name) .'.png') ?>" title="<?= $freeChamp->name ?>" class="champ-icon" />
                                <?= $freeChamp->name ?>
                            </div>
                        </li>
                    <? endforeach; ?>
                </ul>
            </div>
        </div>
        <? $this->load->view('template/blocks/donations') ?>