<div class="uk-panel uk-panel-box">
    <ul class="uk-nav uk-nav-side" data-uk-nav="">
    	<?php foreach($sidebar as $entry) { ?>
        	<li class="<?php if($entry['active'] === true){ echo 'uk-active'; } ?>"><a href="<?php echo base_url($entry['link']); ?>"><?php echo $entry['text'];?></a></li>
    	<?php } ?>
    </ul>
</div>