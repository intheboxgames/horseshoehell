
<div class="uk-grid" data-uk-grid-margin="">
    <div class="uk-width-medium-1-1 uk-margin-large-left">
        <h1 class="uk-heading">Scores for <?php echo $climber->first_name . ' ' . $climber->last_name; ?></h1>
        <div class="uk-width-7-10 uk-alert uk-alert-warning">
            Warning! This page is still under construction and may function different in the future.</br>
        </div>
    </div>
</div>

<div class="uk-grid" data-uk-grid-margin="">
    <div class="uk-width-medium-2-10 uk-hidden-small">
        <div class="uk-panel uk-panel-box">
            <div class="uk-panel-title">View Scores</div>
            <ul class="uk-nav uk-nav-side uk-nav-parent-icon" data-uk-nav="">
                <li class="uk-parent">
                    <a href="#">Current Events</a>
                    <ul class="uk-nav-sub uk-nav-side">
                        <?php 
                        if(count($current_events) > 0) { 
                            foreach($current_events as $current_event) {
                                echo '<li ' . ($current_event->id == $selected_event ? 'class="uk-active">' : '>') . '<a href="#" class="event-nav" data-event="' . $current_event->id . '">' . $current_event->name . '</a></li>';
                            } 
                        } else { ?>
                            <li><a href="#">No Current Events Found</a></li>
                        <?php } ?>
                    </ul>
                </li>
                <li class="uk-parent">
                    <a href="#">Past Event Scores</a>
                    <ul class="uk-nav-sub uk-nav-side">
                        <?php 
                        if(count($past_official) > 0) { 
                            foreach($past_official as $past_event) {
                                echo '<li ' . ($past_event->id == $selected_event ? 'class="uk-active">' : '>') . '<a href="#" class="event-nav" data-event="' . $past_event->id . '">' . $past_event->name . '</a></li>';
                            } 
                        } else { ?>
                            <li><a href="#">No Past Events Found</a></li>
                        <?php } ?>
                    </ul>
                </li>
                <li class="uk-parent">
                    <a href="#">Practice Scores</a>
                    <ul class="uk-nav-sub uk-nav-side">
                        <?php 
                        if(count($past_practice) > 0) { 
                            foreach($past_practice as $past_event) {
                                echo '<li ' . ($past_event->id == $selected_event ? 'class="uk-active">' : '>') . '<a href="#" class="event-nav" data-event="' . $past_event->id . '">' . $past_event->name . '</a></li>';
                            } 
                        } else { ?>
                            <li><a href="#">No Practice Events Found</a></li>
                        <?php } ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
    <div id="score-wrapper" class="uk-width-medium-8-10 uk-width-small-1-1">
    </div>
    <div class="uk-width-1-1 uk-visible-small">
        <div class="uk-panel uk-panel-box">
            <div class="uk-panel-title">View Scores</div>
            <ul class="uk-nav uk-nav-side uk-nav-parent-icon" data-uk-nav="">
                <li class="uk-parent">
                    <a href="#">Current Events</a>
                    <ul class="uk-nav-sub uk-nav-side">
                        <?php 
                        if(count($current_events) > 0) { 
                            foreach($current_events as $current_event) {
                                echo '<li ' . ($current_event->id == $selected_event ? 'class="uk-active">' : '>') . '<a href="#" class="event-nav" data-event="' . $current_event->id . '">' . $current_event->name . '</a></li>';
                            } 
                        } else { ?>
                            <li><a href="#">No Current Events Found</a></li>
                        <?php } ?>
                    </ul>
                </li>
                <li class="uk-parent">
                    <a href="#">Past Event Scores</a>
                    <ul class="uk-nav-sub uk-nav-side">
                        <?php 
                        if(count($past_official) > 0) { 
                            foreach($past_official as $past_event) {
                                echo '<li ' . ($past_event->id == $selected_event ? 'class="uk-active">' : '>') . '<a href="#" class="event-nav" data-event="' . $past_event->id . '">' . $past_event->name . '</a></li>';
                            } 
                        } else { ?>
                            <li><a href="#">No Past Events Found</a></li>
                        <?php } ?>
                    </ul>
                </li>
                <li class="uk-parent">
                    <a href="#">Practice Scores</a>
                    <ul class="uk-nav-sub uk-nav-side">
                        <?php 
                        if(count($past_practice) > 0) { 
                            foreach($past_practice as $past_event) {
                                echo '<li ' . ($past_event->id == $selected_event ? 'class="uk-active">' : '>') . '<a href="#" class="event-nav" data-event="' . $past_event->id . '">' . $past_event->name . '</a></li>';
                            } 
                        } else { ?>
                            <li><a href="#">No Practice Events Found</a></li>
                        <?php } ?>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
    var current_event = <?php echo $selected_event; ?>;
    $(document).ready(function() {

        function show_event(event) {
            current_event = event;
            $.ajax({
                url: "<?php echo base_url('reports/climber/event_details') . '?climber=' . $climber->id . '&event=' ?>" + current_event,
                success: function(result) {
                    $('#score-wrapper').html(result);
                },
                dataType: 'html',
            });
        }

        $('.event-nav').on('click', function(e) {
            var event_id = $(e.currentTarget).data('event');
            if(event_id == current_event) {
                return;
            }
            show_event(event_id);
            //window.location.href =  "<?php echo base_url('reports/climber/view') . '?climber=' . $climber->id . '&event=' ?>" + event_id;
        });

        show_event(current_event);
    });
</script>
