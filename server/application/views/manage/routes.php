
<h3>Route List</h3>
<br/>
<button id="add-element-button" class="uk-button uk-button-primary">Add Route</button>
<hr/>
<table id="route_list" class="display">
    <thead>
        <tr>
            <th>Number</th>
            <th>Name</th>
            <th>Wall</th>
            <th>Rating</th>
            <th>Trad</th>
            <th>Height</th>
            <th>Edit</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($route_list as $route) { ?>
            <tr>
                <th><?php echo $route->number; ?></th>
                <th><?php echo $route->name; ?></th>
                <th><?php echo $route->wall_name; ?></th>
                <th><?php echo $route->rating_name . ' ' . $route->safety_rating; ?></th>
                <th><?php echo $route->trad == 1 ? 'Yes' : 'No'; ?></th>
                <th><?php echo $route->height; ?></th>
                <th><button class="edit-element-button" data-element="<?php echo $route->id; ?>"><i class="uk-icon-edit"></i></button></th>
            </tr>
        <?php } ?>
    </tbody>
</table>

<div id="route-add-modal" class="uk-modal">
    <div class="uk-modal-dialog">
        <a href="" class="uk-modal-close uk-close"></a>
        <h1>Add New Route</h1>
        
        <form action='<?php echo base_url("/manage/routes/add")?>' method='post' accept-charset='utf-8' class="uk-form uk-form-horizontal">
            <div class="uk-form-row">
                <label class="uk-form-label" for="name">Route Name</label>
                <input type="text" name="name" id="name" class="uk-form-control " placeholder="Route Name" required autofocus data-uk-tooltip="{delay:1000}" title="The name of this route">
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="number">Route Number</label>
                <input type="text" name="number" id="number" class="uk-form-control" placeholder="Route Number" data-uk-tooltip="{delay:1000}" title="The number next to the route in the guide book">
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="wall">Area/Wall</label>
                <select name="wall" id="wall" class="uk-form-control" data-uk-tooltip="{delay:1000}" title="The wall of area this route belongs to">
                    <?php foreach($wall_list as $wall) { ?>
                        <option value="<?php echo $wall->id; ?>" <?php if($wall->id == 7){ echo 'selected'; } ?>><?php echo $wall->name ?></option>
                    <?php } ?>
                    <option value="0">Other</option>
                </select>
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="rating">Rating</label>
                <select name="rating" id="rating" class="uk-form-control" data-uk-tooltip="{delay:1000}" title="The difficulty rating of this route">
                    <?php foreach($rating_list as $rating) { ?>
                        <option value="<?php echo $rating->id; ?>" <?php if($rating->id == 12){ echo 'selected'; } ?>><?php echo $rating->name ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="stars">Stars</label>
                <select name="stars" id="stars" class="uk-form-control" data-uk-tooltip="{delay:1000}" title="The quality rating of the route">
                    <option value="0"></option>
                    <option value="1">*</option>
                    <option value="2" selected>**</option>
                    <option value="3">***</option>
                    <option value="4">****</option>
                </select>
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="trad">Is Trad?</label>
                <select name="trad" id="trad" class="uk-form-control" data-uk-tooltip="{delay:1000}" title="Select Yes if this is a trad or mixed climb">
                    <option value="0" selected>No</option>
                    <option value="1">Yes</option>
                </select>
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="safety_rating">Safety Rating</label>
                <select name="safety_rating" id="safety_rating" class="uk-form-control" data-uk-tooltip="{delay:1000}" title="The safety rating of this climb. Select G for no rating">
                    <option value="" selected>G</option>
                    <option value="PG">PG</option>
                    <option value="R">R</option>
                    <option value="X">X</option>
                </select>
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="height">Route Height</label>
                <input type="text" name="height" id="height" class="uk-form-control" placeholder="Route Height" data-uk-tooltip="{delay:1000}" title="The height of the route, in feet">
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="draws">Draws</label>
                <input type="text" name="draws" id="draws" class="uk-form-control" placeholder="Draws" data-uk-tooltip="{delay:1000}" title="Number of draws on the route (0 for trad routes)">
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="year">Year Climbed</label>
                <input type="text" name="year" id="year" class="uk-form-control" placeholder="Year Climbed" data-uk-tooltip="{delay:1000}" title="The first year this route was climbed">
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="first_ascent">First Ascent</label>
                <input type="text" name="first_ascent" id="first_ascent" class="uk-form-control" placeholder="First Ascent" data-uk-tooltip="{delay:1000}" title="The name(s) of the first ascent party">
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="description">Description</label>
                <textarea cols="" rows="" name="description" id="description" class="uk-form-control" placeholder="Description Text" data-uk-tooltip="{delay:1000}" title="A short description of the route"></textarea>
            </div>
            <hr/>
            <div class="uk-form-row uk-clear-fix">
                <div class="uk-form-controls uk-float-right">                    
                    <div id='add-cancel-button' class="uk-button" >Cancel</div>
                    <button id='add-submit-button' class="uk-button uk-button-primary" type="submit" name="submit" value="Submit">Add Route</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="route-edit-modal" class="uk-modal">
    <div class="uk-modal-dialog">
        <a href="" class="uk-modal-close uk-close"></a>
        <h1 id="edit-route-title">Edit Route - </h1>
        
        <form id='edit-form' action='<?php echo base_url("/manage/routes/edit")?>' method='post' accept-charset='utf-8' class="uk-form uk-form-horizontal">
            <input type="hidden" name="id" id="id" >
            <div class="uk-form-row">
                <label class="uk-form-label" for="name">Route Name</label>
                <input type="text" name="name" id="name" class="uk-form-control " placeholder="Route Name" required autofocus data-uk-tooltip="{delay:1000}" title="The name of this route">
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="number">Route Number</label>
                <input type="text" name="number" id="number" class="uk-form-control" placeholder="Route Number" data-uk-tooltip="{delay:1000}" title="The number next to the route in the guide book">
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="wall">Area/Wall</label>
                <select name="wall" id="wall" class="uk-form-control" data-uk-tooltip="{delay:1000}" title="The wall of area this route belongs to">
                    <?php foreach($wall_list as $wall) { ?>
                        <option value="<?php echo $wall->id; ?>" <?php if($wall->id == 7){ echo 'selected'; } ?>><?php echo $wall->name ?></option>
                    <?php } ?>
                    <option value="0">Other</option>
                </select>
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="rating">Rating</label>
                <select name="rating" id="rating" class="uk-form-control" data-uk-tooltip="{delay:1000}" title="The difficulty rating of this route">
                    <?php foreach($rating_list as $rating) { ?>
                        <option value="<?php echo $rating->id; ?>" <?php if($rating->id == 12){ echo 'selected'; } ?>><?php echo $rating->name ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="stars">Stars</label>
                <select name="stars" id="stars" class="uk-form-control" data-uk-tooltip="{delay:1000}" title="The quality rating of the route">
                    <option value="0"></option>
                    <option value="1">*</option>
                    <option value="2" selected>**</option>
                    <option value="3">***</option>
                    <option value="4">****</option>
                </select>
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="trad">Is Trad?</label>
                <select name="trad" id="trad" class="uk-form-control" data-uk-tooltip="{delay:1000}" title="Select Yes if this is a trad or mixed climb">
                    <option value="0" selected>No</option>
                    <option value="1">Yes</option>
                </select>
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="safety_rating">Safety Rating</label>
                <select name="safety_rating" id="safety_rating" class="uk-form-control" data-uk-tooltip="{delay:1000}" title="The safety rating of this climb. Select G for no rating">
                    <option value="" selected>G</option>
                    <option value="PG">PG</option>
                    <option value="R">R</option>
                    <option value="X">X</option>
                </select>
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="height">Route Height</label>
                <input type="text" name="height" id="height" class="uk-form-control" placeholder="Route Height" data-uk-tooltip="{delay:1000}" title="The height of the route, in feet">
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="draws">Draws</label>
                <input type="text" name="draws" id="draws" class="uk-form-control" placeholder="Draws" data-uk-tooltip="{delay:1000}" title="Number of draws on the route (0 for trad routes)">
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="year">Year Climbed</label>
                <input type="text" name="year" id="year" class="uk-form-control" placeholder="Year Climbed" data-uk-tooltip="{delay:1000}" title="The first year this route was climbed">
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="first_ascent">First Ascent</label>
                <input type="text" name="first_ascent" id="first_ascent" class="uk-form-control" placeholder="First Ascent" data-uk-tooltip="{delay:1000}" title="The name(s) of the first ascent party">
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="description">Description</label>
                <textarea cols="" rows="" style="width:350px; height:200px;" name="description" id="description" class="uk-form-control" placeholder="Description Text" data-uk-tooltip="{delay:1000}" title="A short description of the route"></textarea>
            </div>
            <hr/>
            <div class="uk-form-row uk-clear-fix">
                <div class="uk-form-controls uk-float-right">                    
                    <div id='add-cancel-button' class="uk-button" >Cancel</div>
                    <button id='add-submit-button' class="uk-button uk-button-primary" type="submit" name="submit" value="Submit">Save Route</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    var routes = [];
    <?php foreach($route_list as $route) { 
        echo 'routes['.$route->id.'] = {id:"'.$route->id.'", number:"'.$route->number.'", name:"'.$route->name.'", wall:"'.$route->wall.'", rating:"'.$route->rating.'", trad:"'.$route->trad.'", height:"'.$route->height.'", safety_rating:"'.$route->safety_rating.'", year:"'.$route->year.'", first_ascent:"'.$route->first_ascent.'", description:"'.$route->description.'", stars:"'.$route->stars.'", draws:"'.$route->draws.'"};';
    } ?>
    $(document).ready( function () {
        $('#route_list').DataTable();

        $('#add-element-button').on('click', function(e) {
            $.UIkit.modal("#route-add-modal").show();
        });
        $('#add-cancel-button').on('click', function(e) {
            $.UIkit.modal("#route-add-modal").hide();
        });


        $('.edit-element-button').on('click', function(e) {
            var route = routes[$(e.currentTarget).data("element")];
            $('#edit-route-title').html("Edit Route - " + route.name);

            $('#edit-form #id').val(route.id);
            $('#edit-form #name').val(route.name);
            $('#edit-form #number').val(route.number);
            $('#edit-form #wall').val(route.wall).change();
            $('#edit-form #rating').val(route.rating).change();
            $('#edit-form #trad').val(route.trad).change();
            $('#edit-form #stars').val(route.stars).change();
            $('#edit-form #safety_rating').val(route.safety_rating).change();
            $('#edit-form #height').val(route.height);
            $('#edit-form #year').val(route.year);
            $('#edit-form #draws').val(route.draws);
            $('#edit-form #first_ascent').val(route.first_ascent);
            $('#edit-form #description').val(route.description);

            $.UIkit.modal("#route-edit-modal").show();
        });
    } );
</script>