

<script src="<?php echo base_url('/static/js/highcharts.js') ?>"></script>

<h2> Event Scores for <?php echo $event->name . ($event->is_current ? ' (Currently Active)' : ''); ?></h2>
<div class="uk-grid" data-uk-grid-margin="">
    <div class="uk-width-medium-1-2 uk-width-small-1-1 uk-grid">
        <div class="uk-width-1-2">Climber</div>
        <div class="uk-width-1-2"><?php echo $climber->first_name . ' ' . $climber->last_name; ?></div>
        <div class="uk-width-1-2">Team</div>
        <div class="uk-width-1-2">No Team</div>
        <div class="uk-width-1-2">Category</div>
        <div class="uk-width-1-2"><?php echo $event_climber->category_name; ?></div>
        <div class="uk-width-1-2">Event Start Time</div>
        <div class="uk-width-1-2"><?php echo date("n/j/Y g:i A", strtotime($event->start_time.' UTC')); ?></div>
        <div class="uk-width-1-2">Event Type</div>
        <div class="uk-width-1-2"><?php echo $event->event_length .' Hour'; ?></div>
    </div>
</div>
<br/>
<br/>
<select id="chart-type-select" class="uk-width-1-2">
        <option value="routes">Routes Each Hour</option>
        <option value="total_routes">Total Routes Over Time</option>
        <option value="score">Score Each Hour</option>
        <option value="total_score" selected>Total Score Over Time</option>
        <option value="rating">Average Rating Each Hour</option>
        <option value="total_rating">Overall Average Rating</option>
        <option value="points_per_route">Average Points Per Route Each Hour</option>
        <option value="total_points_per_routes">Total Average Points Per Route</option>
        <option value="trad">Trad Climbs Each Hour</option>
        <option value="total_trad">Total Trad Climbs Over Time</option>
        <option value="height">Height Climbed Each Hour</option>
        <option value="total_height">Total Height Climbed Over Time</option>
</select>
<div id="score-container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>
<br/>
<br/>
<br/>
<h2>Route Details</h2>
<table id="route_list" class="display">
    <thead>
        <tr>
            <th>Lap Number</th>
            <th>Name</th>
            <th>Wall</th>
            <th>Rating</th>
            <th>Trad</th>
            <th>Pink Point</th>
            <th>Height</th>
            <th>Score</th>
            <th>Hour</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($sends as $key => $send) { ?>
            <tr>
                <th><?php echo ($key + 1); ?></th>
                <th><?php echo $send->name; ?></th>
                <th><?php echo $send->wall_name; ?></th>
                <th><?php echo $send->rating_name . ' ' . $send->safety_rating; ?></th>
                <th><?php echo $send->trad == 1 ? 'Yes' : 'No'; ?></th>
                <th><?php echo $send->pink_point == 1 ? 'Yes' : 'No'; ?></th>
                <th><?php echo $send->height; ?></th>
                <th><?php echo $send->score; ?></th>
                <th><?php echo $send->hour; ?></th>
            </tr>
        <?php } ?>
    </tbody>
</table>

<script>
    var hourly_data = [];
    hourly_data[0] = {routes:"0", total_routes:"0", score:"0", total_score:"0", rating:"0", total_rating:"0", points_per_route:"0", total_points_per_routes:"0", trad:"0", total_trad:"0", height:"0", total_height:"0"};
    <?php foreach($hourly_data as $data) {
        echo 'hourly_data['.$data->hour.'] = {routes:"'.$data->routes.'", total_routes:"'.$data->total_routes.'", score:"'.$data->score.'", total_score:"'.$data->total_score.'", rating:"'.$data->rating.'", total_rating:"'.$data->total_rating.'", points_per_route:"'.$data->points_per_route.'", total_points_per_routes:"'.$data->total_points_per_routes.'", trad:"'.$data->trad.'", total_trad:"'.$data->total_trad.'", height:"'.$data->height.'", total_height:"'.$data->total_height.'"};';
    }?>

    var chart_titles = {
        routes: "Routes Each Hour",
        total_routes: "Total Routes Over Time",
        score: "Score Each Hour",
        total_score: "Total Score Over Time",
        rating: "Average Rating Each Hour",
        total_rating: "Overall Average Rating",
        points_per_route: "Average Points Per Route Each Hour",
        total_points_per_routes: "Total Average Points Per Route",
        trad: "Trad Climbs Each Hour",
        total_trad: "Total Trad Climbs Over Time",
        height: "Height Climbed Each Hour",
        total_height: "Total Height Climbed Over Time",
    };
    var chart_labels = {
        routes: "Routes",
        total_routes: "Total Routes",
        score: "Score",
        total_score: "Total Score",
        rating: "Average Rating",
        total_rating: "Overall Average Rating",
        points_per_route: "Average Points Per Route",
        total_points_per_routes: "Average Points Per Route",
        trad: "Trad Climbs",
        total_trad: "Total Trad Climbs",
        height: "Height Climbed",
        total_height: "Total Height Climbed",
    };

    function show_chart(type) { 
        chart_data = [];
        for(var i = 0; i < hourly_data.length; ++i) {
            chart_data[i] = parseInt(hourly_data[i][type]);
        }
        $('#score-container').highcharts({
            chart: {
                type: 'area'
            },
            title: {
                text: chart_titles[type],
            },
            subtitle: {
                text: ''
            },
            xAxis: {
                allowDecimals: false,
                labels: {
                    formatter: function () {
                        return 'Hour ' + this.value;
                    }
                }
            },
            yAxis: {
                title: {
                    text: chart_labels[type]
                },
                labels: {
                    formatter: function () {
                        return this.value;
                    }
                }
            },
            xAxis: {
                title: {
                    text: 'Hour'
                },
                labels: {
                    formatter: function () {
                        return this.value;
                    }
                }
            },
            plotOptions: {
                area: {
                    pointStart: 0,
                    marker: {
                        enabled: false,
                        symbol: 'circle',
                        radius: 2,
                        states: {
                            hover: {
                                enabled: true
                            }
                        }
                    }
                }
            },
            series: [{
                name: chart_labels[type],
                data: chart_data,
            }]
        });
    }
    $(document).ready(function() {
        show_chart('total_score');

        $('#chart-type-select').on('change', function() {
            show_chart($('#chart-type-select').val());
        });

        $('#route_list').dataTable();
    });
</script>
