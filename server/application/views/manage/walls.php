
<h3>Wall List</h3>
<br/>
<button id="add-element-button" class="uk-button uk-button-primary">Add Wall</button>
<hr/>
<table id="wall_list" class="display">
    <thead>
        <tr>
            <th>Wall</th>
            <th>Canyon Side</th>
            <th>Number of Routes</th>
            <th>Edit</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($wall_list as $wall) { ?>
            <tr>
                <th><?php echo $wall->name; ?></th>
                <th><?php echo ucwords($wall->side); ?></th>
                <th><?php echo $wall->route_count; ?></th>
                <th><button class="edit-element-button" data-element="<?php echo $wall->id; ?>"><i class="uk-icon-edit"></i></button></th>
            </tr>
        <?php } ?>
    </tbody>
</table>

<div id="wall-add-modal" class="uk-modal">
    <div class="uk-modal-dialog">
        <a href="" class="uk-modal-close uk-close"></a>
        <h1>Add New Wall</h1>
        
        <form action='<?php echo base_url("/manage/walls/add")?>' method='post' accept-charset='utf-8' class="uk-form uk-form-horizontal">
            <div class="uk-form-row">
                <label class="uk-form-label" for="name">Wall Name</label>
                <input type="text" name="name" id="name" class="uk-form-control " placeholder="Wall Name" required autofocus data-uk-tooltip="{delay:1000}" title="The name of this wall">
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="side">Canyon Side</label>
                <select name="side" id="side" class="uk-form-control" data-uk-tooltip="{delay:1000}" title="Is this wall on the east or west side of the canyon">
                    <option value="west" selected>West</option>
                    <option value="east">East</option>
                </select>
            </div>
            <hr/>
            <div class="uk-form-row uk-clear-fix">
                <div class="uk-form-controls uk-float-right">                    
                    <div id='add-cancel-button' class="uk-button" >Cancel</div>
                    <button id='add-submit-button' class="uk-button uk-button-primary" type="submit" name="submit" value="Submit">Add Wall</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="wall-edit-modal" class="uk-modal">
    <div class="uk-modal-dialog">
        <a href="" class="uk-modal-close uk-close"></a>
        <h1 id="edit-wall-title">Edit Wall - </h1>
        
        <form id='edit-form' action='<?php echo base_url("/manage/walls/edit")?>' method='post' accept-charset='utf-8' class="uk-form uk-form-horizontal">
            <input type="hidden" name="id" id="id" >
            <div class="uk-form-row">
                <label class="uk-form-label" for="name">Wall Name</label>
                <input type="text" name="name" id="name" class="uk-form-control " placeholder="Wall Name" required autofocus data-uk-tooltip="{delay:1000}" title="The name of this wall">
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="side">Canyon Side</label>
                <select name="side" id="side" class="uk-form-control" data-uk-tooltip="{delay:1000}" title="Is this wall on the east or west side of the canyon">
                    <option value="west" selected>West</option>
                    <option value="east">East</option>
                </select>
            </div>
            <hr/>
            <div class="uk-form-row uk-clear-fix">
                <div class="uk-form-controls uk-float-right">                    
                    <div id='add-cancel-button' class="uk-button" >Cancel</div>
                    <button id='add-submit-button' class="uk-button uk-button-primary" type="submit" name="submit" value="Submit">Save Wall</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    var walls = [];
    <?php foreach($wall_list as $wall) { 
        echo 'walls['.$wall->id.'] = {id:"'.$wall->id.'", name:"'.$wall->name.'", side:"'.$wall->side.'"};';
    } ?>
    $(document).ready( function () {
        $('#wall_list').DataTable();

        $('#add-element-button').on('click', function(e) {
            $.UIkit.modal("#wall-add-modal").show();
        });
        $('#add-cancel-button').on('click', function(e) {
            $.UIkit.modal("#wall-add-modal").hide();
        });


        $('.edit-element-button').on('click', function(e) {
            var wall = walls[$(e.currentTarget).data("element")];
            $('#edit-wall-title').html("Edit Wall - " + wall.name);

            $('#edit-form #id').val(wall.id);
            $('#edit-form #name').val(wall.name);
            $('#edit-form #side').val(wall.side).change();

            $.UIkit.modal("#wall-edit-modal").show();
        });
    } );
</script>