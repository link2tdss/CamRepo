<script>
  $( function() {
    $( "#tabs" ).tabs({
      beforeLoad: function( event, ui ) {
        ui.jqXHR.fail(function() {
          ui.panel.html(
            "Couldn't load this tab. We'll try to fix this as soon as possible. " +
            "If this wouldn't be a demo." );
        });
      }
    });
  } );

</script>
<div id="tabs">
  <ul>
    <li><a href="users/user_list.php">Users</a></li>
    <li><a href="users/new_user.php">New User</a></li>
  </ul>
 
  
