<div class="uk-grid" data-uk-grid-margin="">
    <div class="uk-width-medium-2-10 uk-hidden-small">
        <div class="uk-panel uk-panel-box">
            <ul class="uk-nav uk-nav-side" data-uk-nav="">
                <li class=""><a href="<?php echo base_url('/manage/areas'); ?>">Areas</a></li>
                <li class="uk-active"><a href="<?php echo base_url('/manage/routes'); ?>">Routes</a></li>
                <li class=""><a href="<?php echo base_url('/manage/teams'); ?>">Teams</a></li>
                <li class=""><a href="<?php echo base_url('/manage/events'); ?>">Events</a></li>
            </ul>
        </div>
    </div>
    <div class="uk-width-medium-8-10 uk-width-small-1-1">
        <h3>Route List</h3>

        <table id="route_list" class="display">
            <thead>
                <tr>
                    <th>Number</th>
                    <th>Name</th>
                    <th>Wall</th>
                    <th>Rating</th>
                    <th>Trad</th>
                    <th>Height</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($route_list as $route) { ?>
                    <tr>
                        <th><?php $route->number ?></th>
                        <th><?php $route->name ?></th>
                        <th><?php $route->wall_name ?></th>
                        <th><?php $route->rating_name ?></th>
                        <th><?php $route->trad == 1 ? 'Yes' : 'No' ?></th>
                        <th><?php $route->height ?></th>
                    </tr>
                <?php } ?>
            </tbody>
        </table>

    </div>
    <div class="uk-width-1-1 uk-visible-small">
        <div class="uk-panel uk-panel-box">
            <ul class="uk-nav uk-nav-side" data-uk-nav="">
                <li class=""><a href="<?php echo base_url('/manage/areas'); ?>">Areas</a></li>
                <li class="uk-active"><a href="<?php echo base_url('/manage/routes'); ?>">Routes</a></li>
                <li class=""><a href="<?php echo base_url('/manage/teams'); ?>">Teams</a></li>
                <li class=""><a href="<?php echo base_url('/manage/events'); ?>">Events</a></li>
            </ul>
        </div>
    </div>
</div>


<script>
    $(document).ready( function () {
        $('#route_list').DataTable();
    } );
</script>