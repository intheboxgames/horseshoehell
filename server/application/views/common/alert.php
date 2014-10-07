
<div class="uk-grid" style="width:100%">
	<?php if($this->message->has_errors()) { ?>
		<div class="uk-alert uk-alert-danger uk-width-1-1" style="margin-left: 25px;"><?php echo $this->message->get_errors() ?></div>
	<?php } if($this->message->has_warnings()) { ?>
		<div class="uk-alert uk-alert-warning uk-width-1-1" style="margin-left: 25px;"><?php echo $this->message->get_warnings() ?></div>
	<?php } if($this->message->has_info()) { ?>
		<div class="uk-alert uk-alert-info uk-width-1-1" style="margin-left: 25px;"><?php echo $this->message->get_info() ?></div>
	<?php } if($this->message->has_success()) { ?>
		<div class="uk-alert uk-alert-success uk-width-1-1" style="margin-left: 25px;"><?php echo $this->message->get_success() ?></div>
	<?php } ?>
</div>