
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
		  $("#"+key).parent().after("");
			  $("#"+key).parent().after("<span style='color:red'> *" + value + "</span>");
			});
		  });
	});


  	var selectes = [];
	var unselectes = [];
	$( "#selectable" ).selectable({
		unselecting: function(event, ui) {
			console.log('calling unselecting');
			$( ".ui-unselecting", this ).each(function(index) {
				var id = $(this).attr('id');
				if(selectes.indexOf(id) == -1){
					selectes.push(id);
					console.log('adding ' + id  + ' to selecting array');
				}			
			});
		},
		stop: function(){
			console.log('calling stop');
			selectes.forEach(function(item, index){
					$('#'+item).addClass('ui-selected');
				});
			unselectes.forEach(function(item, index){
				$('#'+item).removeClass('ui-selected');
			});
			selectes = [];
			unselectes = [];
		},
		selecting: function(){
			console.log('calling selecting');
			console.log('potential selected elements ' + selectes.toString());
			$( ".ui-selecting", this ).each(function() {
				var id = $(this).attr('id');
				console.log('checkng if ' + id + ' needs to be removed');
				index = selectes.indexOf(id);
				console.log(' Index of ' + id + ' in selecting array is ' + index );
				if(index > -1){
					console.log('removing ' + id + ' from selecting array');
					unselectes.push(id);
				}
			});
			console.log('potential selected elements now ' + selectes.toString());
		}
	});
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
<input type="hidden" id="camsSelected" name="camsSelected" />
<span> Select the Camera(s) the user is allowed to access. </span>
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

			//echo '<ol id="selectable"  value="WWW">';
			echo '<div id="selectable">';
			/* fetch values */
			while ($stmt->fetch()) {
				//echo '<li class="ui-widget-content" value="' . $cameraID . '">' . $cameraDescription . '</li>';
				echo '<span class="ui-widget-content" value = '. $cameraID . ' id = ' . $cameraID . '>' . $cameraDescription . '</span>';
			}
			//echo '</ol>';
			echo '</div>';
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