
<script type="text/javascript">
// Attach a submit handler to the form
$( "#newUsersForm" ).submit(function( event ) {
 
  // Stop form from submitting normally
  event.preventDefault();
 
  // Get some values from elements on the page:
  var $form = $( this );
    var url = $form.attr( "action" );
  console.log( $( this ).serialize() );
  // Send the data using post
  var posting = $.post( url, $( "#newUsersForm" ).serialize()).done(function( data ) {
    //var content = $( data ).find( "#content" );
   // alert(" " + content);
    //$( "#result" ).empty().append(" " + content );
    alert("Success");
    $("#bodyContainer").load("users/users.php");
  }).fail(function( data ) {
	    var result = jQuery.parseJSON(data.responseText);
	  $.each(result, function(key, value){
		  $("#"+key).parent().after("<span style='color:red'> *" + value + "</span>");
		});
	  });
});


  $( function() {
    $( "#selectable" ).selectable({
      stop: function() {
        var result = $( "#select-result" ).empty();
        $( ".ui-selected", this ).each(function() {
        	console.log('Elem selected ' + this);
        	console.log($(this).attr('value'));
          //var val = $( "#selectable li" ).index( this );
          //result.append( " #" + ( index + 1 ) );
        });
      }
    });
  } );
</script>


	<form action="users/register_new_user.php" method="POST" id="newUsersForm">
		<table>
			<tr>
				<td><label>First Name:</label></td>
				<td id="fnameid"><input type="text" id="fname" name="fname"/></td>
			</tr>
			<tr>
				<td><label>Last Name:</label></td>
				<td><input type="text" id="lname" name="lname"/></td>
			</tr>
			<tr>
				<td><label>Email:</label></td>
				<td><input type="text" id="email" name="email"/></td>
			</tr>
			<tr>
				<td><label>UserID:</label></td>
				<td><input type="text" id="uid" name="uid"/></td>
			</tr>
			<tr>
				<td><label>Password:</label></td>
				<td><input type="password" id="password" name="password" /></td>
			</tr>
		</table>
		
		<p id="feedback">
<span>You've selected:</span> <span id="select-result">none</span>.
<span id="appended-elem"> --- </span>
</p>

<?php
	include $_SERVER["DOCUMENT_ROOT"] . '/private/conn_db.php'; 

	$mysqli = getDbConn ();
	if(is_null($mysqli)){
		echo  "<br>". 'Cant do it boss';
	}else {
		
		if ($stmt = $mysqli->prepare("SELECT cameraID, cameraDescription FROM cameras ")) {

   			 /* execute statement */
			$stmt->execute();

			/* bind result variables */
			$stmt->bind_result($cameraID, $cameraDescription);

			echo '<ol id="selectable"  value="WWW">';
			/* fetch values */
			while ($stmt->fetch()) {
				echo '<li class="ui-widget-content" value="' . $cameraID . '">' . $cameraDescription . '</li>';
			}
			echo '</ol>';
			/* close statement */
			$stmt->close();
		}

		/* close connection */
		$mysqli->close();
	}

?>

		
		</br></br>
		<div class="rowElem"><input type="submit" value="Create user" /> <input type="reset" value="Clear" /></div>
				
	</form>