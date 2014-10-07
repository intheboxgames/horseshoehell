<header id="header" class="site-header uk-margin-small-top uk-margin-small-bottom" role="banner">
    <nav id="site-navigation" class="uk-navbar" role="navigation">
        <div class="uk-container uk-container-center uk-hidden-small">
            <ul class="uk-navbar-nav uk-hidden-small" style="width:100%;">
                <li class=""><a href="<?php echo base_url(''); ?>">Home</a></li>
                <li class=""><a href="<?php echo base_url('/reports'); ?>">Scores</a></li>
                <li class=""><a href="<?php echo base_url('/climbs'); ?>">Routes</a></li>
                <?php if($this->auth->logged_in()) { ?>
                    <li class="" style="float:right;"><a href="<?php echo base_url('/login/logout'); ?>">Logout</a></li>
                    <li class="" style="float:right;"><a href="<?php echo base_url('/manage/'); ?>">Edit Content</a></li>
                <?php } else { ?>
                    <li class="" style="float:right;"><a href="<?php echo base_url('/login'); ?>">Login</a></li>
                <?php } ?>
            </ul>
        </div>
        <a href="#offcanvas-main" class="uk-navbar-toggle uk-visible-small" data-uk-offcanvas="{target:'#offcanvas-main'}" style="padding-top: 20px;"></a>
    </nav>
</header>

<div id="offcanvas-main" class="uk-offcanvas uk-margin-small-top">
    <div class="uk-offcanvas-bar uk-offcanvas-bar-show">
        <div style="margin-top:90px"></div>

        <ul class="uk-nav uk-nav-offcanvas uk-nav-offcanvas-main uk-nav-parent-icon" data-uk-nav="">
                <li class=""><a href="<?php echo base_url(''); ?>">Home</a></li>
                <li class=""><a href="<?php echo base_url('/reports'); ?>">Scores</a></li>
                <li class=""><a href="<?php echo base_url('/climbs'); ?>">Routes</a></li>
                <?php if($this->auth->logged_in()) { ?>
                    <li class=""><a href="<?php echo base_url('/manage/'); ?>">Edit Content</a></li>
                    <li class=""><a href="<?php echo base_url('/login/logout'); ?>">Logout</a></li>
                <?php } else { ?>
                    <li class=""><a href="<?php echo base_url('/login'); ?>">Login</a></li>
                <?php } ?>
        </ul>
    </div>
</div>