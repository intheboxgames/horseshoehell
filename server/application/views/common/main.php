<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<?php echo '<meta title="Horseshoe Hell Scoring - '.$title.'">' ?>
        <meta http-equiv="Content-type" content="text/html; charset=UTF-8">

		<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script src="<?php echo base_url('/static/js/uikit.js') ?>"></script>
		<script src="<?php echo base_url('/static/js/addons/notify.js') ?>"></script>
		<script src="<?php echo base_url('/static/js/addons/sticky.js') ?>"></script>
		<script src="<?php echo base_url('/static/js/jquery.dataTables.min.js') ?>"></script>
		<link rel="stylesheet" href="<?php echo base_url('/static/css/uikit.gradient.min.css') ?>"/>
		<link rel="stylesheet" href="<?php echo base_url('/static/css/addons/uikit.gradient.min.css') ?>"/>
		<link rel="stylesheet" href="<?php echo base_url('/static/css/jquery.dataTables.min.css') ?>"/>

		<!--[if gte IE 9]>
		  	<style type="text/css">
			    .gradient {
			       filter: none;
			    }
		  	</style>
		<![endif]-->

		<?php if(!(isset($skip_extra_javascript) && $skip_extra_javascript)) { ?>
			<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>
		<?php } ?>

		<?php if(isset($using_facebook) && $using_facebook) { ?>
			<script>
				function FacebookLoginResponse(response){
				    if (response.status === 'connected') {
				    	window.location = window.location;
				    }
				    else{
				      	window.location = "<?php echo base_url('/login') ?>";
				    }
				}
				function FacebookLogin(){
					FB.login(FacebookLoginResponse, {scope: 'email,publish_actions,user_games_activity,friends_games_activity'});
				}
				window.fbAsyncInit = function() {
					console.log('FB init');
					FB.init({
						appId	: '1374370459488680',
						status	: true,
						cookie	: true,
						xfbml	: true,
					});

					$('#fb-login-wrapper').show();

	  				FB.Event.subscribe('auth.authResponseChange', function(response) {
					    // Here we specify what we do with the response anytime this event occurs. 
					    if (response.status === 'connected') {
				      		// Don't do anything if we are already connected
				      		console.log('Good to see you, ' + response.name + '.');
					    } else {
					    	FacebookLogin();
					    }
				  	});
			  	};

			  	(function(d){
				   	var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
				   	if (d.getElementById(id)) {return;}
				   	js = d.createElement('script'); js.id = id; js.async = true;
				   	js.src = "//connect.facebook.net/en_US/all.js";
				   	ref.parentNode.insertBefore(js, ref);
			  	}(document));

			</script>
		<?php } ?>
		<?php if(isset($using_google) && $using_google) { ?>
			<script>
			  	(function(){
				   	var id = 'google-js';
				   	if (document.getElementById(id)) {return;}
				   	js = document.createElement('script'); 
				   	js.id = id; 
				   	js.async = true;
				   	js.src = "https://apis.google.com/js/client:plusone.js";
				   	var ref = document.getElementsByTagName('script')[0];
				   	ref.parentNode.insertBefore(js, ref);
			  	})();
			</script>
		<?php } ?>
	</head>
	<body>
		<div id="container" class="uk-container uk-container-center relative">
			<?php 
				$this->load->view('common/header');
				if(!isset($hide_alerts)) { 
					$this->load->view('common/alert');
				} 
				echo $content;
			?>
		</div> <!-- End of 'container'-->
	</body>
	<?php $this->load->view('common/footer'); ?>
</html>