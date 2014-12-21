<!Doctype html>
<html>
<head>
    <title>
        <? if(!empty($metaTitle)){ echo $metaTitle.' - '; }?>MyLoLChampions.com
    </title>
    <? if(!empty($metaDescription)): ?>
    <meta name="description" content="<?= $metaDescription ?>">
    <? endif; ?>
    <meta http-equiv="Content-Type" content="text/html;charset=UTF-8">


    <link rel="icon" href="<?= base_url('img/favicon.ico'); ?>" />
    <link rel="shortcut_icon" href="<?= base_url('img/favicon.ico'); ?>" />

    <link rel="stylesheet" href="<?= base_url('css/bootstrap.min.css'); ?>" />
    <link rel="stylesheet" href="<?= base_url('css/bootstrap-theme.min.css'); ?>" />
    <link rel="stylesheet" href="<?= base_url('css/jquery-ghnav/style.css'); ?>" />
    <link rel="stylesheet" href="<?= base_url('css/styles.css'); ?>" />
    <? if(!empty($cssFiles)) : ?>
        <? foreach($cssFiles as $cssFile ): ?>
            <link rel="stylesheet" href="<?= base_url('css/' . $cssFile); ?>" />
        <? endforeach ?>
    <? endif ?>


    <script type="text/javascript" src="<?= base_url('js/jquery-1.10.2.min.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('js/jquery-ui-1.10.4.custom.min.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('js/bootstrap.min.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('js/jquery.ghnav.js'); ?>"></script>
    <script type="text/javascript" src="<?= base_url('js/common.js'); ?>"></script>
    <? if(!empty($jsFiles)) : ?>
        <? foreach($jsFiles as $jsFile ): ?>
            <script type="text/javascript" src="<?= base_url('js/' . $jsFile); ?>"></script>
        <? endforeach ?>
    <? endif ?>
</head>
<body>
<?if (ENVIRONMENT === 'production') {
    $this->load->view('template/blocks/analytics');
}  ?>
<img src="<?= base_url('img/beta.png') ?>" id="beta-ribbon" title="beta" />
<input type="hidden" id="base-url" value="<?= base_url() ?>" />
<div id="page" class="container">
    <header id="header">
        <div class="row">
            <div id="logo" class="col-sm-3">
                <a href="<?= base_url() ?>" title="LoL Champions tool" />
                    <img src="<?= base_url('img/logo.png') ?>" title="MyLoLChampions" />
                </a>
            </div>
            <div id="summoner-search" class="col-sm-6">
                <form>
                    <div class="row">
                        <div class="form-group col-sm-5">
                            <input type="text" name="s-name" id="search-name" placeholder="Summoner Name..." class="form-control" />
                        </div>
                        <div class="form-group col-sm-4">
                            <select name="region" id="search-region" class="form-control">
                                <option value="br"<? if(!empty($region) && $region == 'br'):?> selected="selected"<? endif ?>>Brazil</option>
                                <option value="eune"<? if(!empty($region) && $region == 'eune'):?> selected="selected"<? endif ?>>Europe Nordic & East</option>
                                <option value="euw"<? if(!empty($region) && $region == 'euw'):?> selected="selected"<? endif ?>>Europe West</option>
                                <option value="lan"<? if(!empty($region) && $region == 'lan'):?> selected="selected"<? endif ?>>Latin America North</option>
                                <option value="las"<? if(!empty($region) && $region == 'las'):?> selected="selected"<? endif ?>>Latin America South</option>
                                <option value="na"<? if(!empty($region) && $region == 'na'):?> selected="selected"<? endif ?>>North America</option>
                                <option value="oce"<? if(!empty($region) && $region == 'oce'):?> selected="selected"<? endif ?>>Oceania</option>
                            </select>
                        </div>
                        <div class="form-group col-sm-3">
                            <input type="submit" name="search-summoner" value="Search" class="btn btn-primary" />
                        </div>
                    </div>
                </form>
            </div>
            <div id="profile-links" class="col-sm-3">
                <? $this->load->view('template/blocks/login') ?>
            </div>
        </div>
    </header>
    <div class="row">
        <div class="col-sm-12">
            <ul id="nav">
                <li<? if($page == 'home'): ?> id="selected"<? endif ?>>
                    <a href="<?= base_url() ?>" title="Home page" />
                        Home
                    </a>
                </li>
                <li<? if($page == 'about'): ?> id="selected"<? endif ?>>
                    <a href="<?= base_url('about') ?>" title="About" />
                        About
                    </a>
                </li>
                <li class="last"<? if($page == 'contact'): ?> id="selected"<? endif ?>>
                    <a href="<?= base_url('contact') ?>" title="Contact" />
                    Contact
                    </a>
                </li>
            </ul>
        </div>
    </div>
