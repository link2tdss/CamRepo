<script>
$(document).ready(function() {
    $('#users').DataTable( {
        "ajax": 'users/user_data.php'
    } );
} );

</script>

<table id="users" class="display" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Name</th>
                <th>email</th>
                <th>userID</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Name</th>
                <th>email</th>
                <th>userID</th>
            </tr>
        </tfoot>
    </table>