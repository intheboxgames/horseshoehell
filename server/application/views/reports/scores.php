
<div class="uk-grid" data-uk-grid-margin="">
    <div class="uk-width-medium-1-1 uk-margin-large-left">
        <h1 class="uk-heading-large">Scores</h1>
        <div class="uk-width-7-10 uk-alert uk-alert-warning">
            Warning! This page is still under construction and may function different in the future.</br>
            Currently you can only look at a single climber's scores but eventually scores will be available for climbers, teams, event, lifetime, and for directly comparing multiple climbers.
        </div>
    </div>
</div>

<div class="uk-grid uk-margin-large-left" >
    <div class="uk-panel uk-panel-box uk-width-medium-4-10 uk-width-small-1-1 uk-panel-header">
        <div class="uk-panel-title">
            Climber Search
        </div>
        <div class="uk-text-small uk-text-primary">Start typing a climber's name to search for climbers</div>
        <div class="uk-form-row">
            <input type="hidden" name="climber" id="climber_input">
            <div class="uk-width-1-1" id="climber"></div>
        </div>
        <hr/>
        <div class="uk-form-row uk-clear-fix">
            <div class="uk-form-controls uk-float-right">                    
                <button id='submit-button' class="uk-button uk-button-primary" disabled>Search</button>
            </div>
        </div>
    </div>
    <div class="uk-panel uk-panel-box uk-width-medium-4-10 uk-width-small-1-1 uk-panel-header uk-margin-large-left">
        <div class="uk-panel-title">
            Team Search
        </div>
        This feature doesn't work yet. Please try again later.
    </div>
</div>

<script>
    $(document).ready(function() {

        var selected_climber = -1;

        $('#climber').select2({
            placeholder: "Search for a climber",
            minimumInputLength: 2,
            ajax: {
                url: "<?php echo base_url('reports/climber/search'); ?>",
                datatype: 'json',
                data: function(term, page) {
                    return {
                        term: term,
                        page: page,
                        page_limit: 10,
                    };
                },
                results: function(data, page) {
                    return {results: data.climbers};
                },
            },
            /*initSelection: function(element, callback) {

            },*/
            formatResult: function(climber) {
                return climber.first_name + " " + climber.last_name;
            },
            formatSelection: function(climber) {
                return climber.first_name + " " + climber.last_name;
            },
            dropdownCssClass: "bigdrop",
            escapeMarkup: function(m) { return m; },
        });

        $('#climber').on('select2-selecting', function(e) {
            selected_climber = e.val;
            $('#submit-button').removeAttr('disabled');
            $('#submit-button').off('click');
            $('#submit-button').on('click', function() {
                window.location.href =  "<?php echo base_url('reports/climber/view') . '?climber=' ?>" + selected_climber;
            });
        });
    });
</script>
