
<h3>User List</h3>
<br/>
<button id="add-element-button" class="uk-button uk-button-primary">Add User</button>
<hr/>
<table id="user_list" class="display">
    <thead>
        <tr>
            <th>First Name</th>
            <th>Last Name</th>
            <th>Email</th>
            <th>Role</th>
            <th>Edit</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($user_list as $user) { ?>
            <tr>
                <th><?php echo $user->first_name; ?></th>
                <th><?php echo $user->last_name; ?></th>
                <th><?php echo $user->email; ?></th>
                <th><?php echo ucwords($user->role); ?></th>
                <th><button class="edit-element-button" data-element="<?php echo $user->id; ?>"><i class="uk-icon-edit"></i></button></th>
            </tr>
        <?php } ?>
    </tbody>
</table>

<div id="user-add-modal" class="uk-modal">
    <div class="uk-modal-dialog">
        <a href="" class="uk-modal-close uk-close"></a>
        <h1>Add New User</h1>
        
        <form action='<?php echo base_url("/users/account/add")?>' method='post' accept-charset='utf-8' class="uk-form uk-form-horizontal">
            <div class="uk-form-row">
                <label class="uk-form-label" for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" class="uk-form-control " placeholder="First Name" required autofocus data-uk-tooltip="{delay:1000}" title="The user's first name">
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="last_name">Last Name</label>
                <input type="text" name="last_name" id="last_name" class="uk-form-control " placeholder="Last Name" required data-uk-tooltip="{delay:1000}" title="The user's last name">
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="email">Email</label>
                <input type="text" name="email" id="email" class="uk-form-control " placeholder="Email" required data-uk-tooltip="{delay:1000}" title="The user's email">
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="role">Role</label>
                <select name="role" id="role" class="uk-form-control" data-uk-tooltip="{delay:1000}" title="This user's role">
                    <option value="volunteer" selected>Volunteer</option>
                    <option value="reports">Reports</option>
                    <option value="content">Content</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="password">Password</label>
                <input type="password" name="password" id="password" class="uk-form-control" placeholder="Password" required data-uk-tooltip="{delay:1000}" title="A password for this user">
            </div>   
            <div id="password_confirm_wrapper" class="uk-form-row">
                <label class="uk-form-label" for="password">Password Confirm</label>
                <input type="password" name="password_confirm" id="password_confirm" class="uk-form-control" placeholder="Password Confirm" required data-uk-tooltip="{delay:1000}" title="This must match the user's password to save changes">
            </div>   
            <hr/>
            <div class="uk-form-row uk-clear-fix">
                <div class="uk-form-controls uk-float-right">                    
                    <div id='add-cancel-button' class="uk-button" >Cancel</div>
                    <button id='add-submit-button' class="uk-button uk-button-primary" type="submit" name="submit" value="Submit">Add User</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="user-edit-modal" class="uk-modal">
    <div class="uk-modal-dialog">
        <a href="" class="uk-modal-close uk-close"></a>
        <h1 id="edit-user-title">Edit User - </h1>
        
        <form id='edit-form' action='<?php echo base_url("/users/account/edit")?>' method='post' accept-charset='utf-8' class="uk-form uk-form-horizontal">
            <input type="hidden" name="id" id="id" >
            <div class="uk-form-row">
                <label class="uk-form-label" for="first_name">First Name</label>
                <input type="text" name="first_name" id="first_name" class="uk-form-control " placeholder="First Name" required autofocus data-uk-tooltip="{delay:1000}" title="The user's first name">
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="last_name">Last Name</label>
                <input type="text" name="last_name" id="last_name" class="uk-form-control " placeholder="Last Name" required data-uk-tooltip="{delay:1000}" title="The user's last name">
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="email">Email</label>
                <input type="text" name="email" id="email" class="uk-form-control " placeholder="Email" required data-uk-tooltip="{delay:1000}" title="The user's email">
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="role">Role</label>
                <select name="role" id="role" class="uk-form-control" data-uk-tooltip="{delay:1000}" title="This user's role">
                    <option value="volunteer" selected>Volunteer</option>
                    <option value="reports">Reports</option>
                    <option value="content">Content</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
            <div class="uk-form-row">
                <label class="uk-form-label" for="password">Password</label>
                <input type="password" name="password" id="password" class="uk-form-control" placeholder="Password" required data-uk-tooltip="{delay:1000}" title="A password for this user">
            </div>   
            <div id="password_confirm_wrapper" class="uk-form-row">
                <label class="uk-form-label" for="password">Password Confirm</label>
                <input type="password" name="password_confirm" id="password_confirm" class="uk-form-control" placeholder="Password Confirm" required data-uk-tooltip="{delay:1000}" title="This must match the user's password to save changes">
            </div>  
            <hr/>
            <div class="uk-form-row uk-clear-fix">
                <div class="uk-form-controls uk-float-right">                    
                    <div id='add-cancel-button' class="uk-button" >Cancel</div>
                    <button id='add-submit-button' class="uk-button uk-button-primary" type="submit" name="submit" value="Submit">Save User</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script>
    var users = [];
    <?php foreach($user_list as $user) { 
        echo 'users['.$user->id.'] = {id:"'.$user->id.'", first_name:"'.$user->first_name.'", last_name:"'.$user->last_name.'", email:"'.$user->email.'", role:"'.$user->role.'"};';
    } ?>
    $(document).ready( function () {
        $('#user_list').DataTable();

        $('#add-element-button').on('click', function(e) {
            $.UIkit.modal("#user-add-modal").show();
        });
        $('#add-cancel-button').on('click', function(e) {
            $.UIkit.modal("#user-add-modal").hide();
        });


        $('.edit-element-button').on('click', function(e) {
            var user = users[$(e.currentTarget).data("element")];
            $('#edit-user-title').html("Edit User - " + user.first_name + " " + user.last_name);

            $('#edit-form #id').val(user.id);
            $('#edit-form #first_name').val(user.first_name);
            $('#edit-form #last_name').val(user.last_name);
            $('#edit-form #email').val(user.email);
            $('#edit-form #role').val(user.role).change();
            $('#edit-form #password').val("");
            $('#edit-form #password_confirm').val("");

            $.UIkit.modal("#user-edit-modal").show();
        });
    } );
</script>