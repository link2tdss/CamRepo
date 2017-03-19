  <style>
   
    .ui-button-slim { padding: .3em; }
  </style>
<script>
$(document).ready(function() {
	var dialog, form, table;
    table = $('#users').DataTable( {
        "ajax": 'users/user_data.php',
        select: true,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'copyHtml5',
                exportOptions: {
                 columns: ':contains("Office")'
                }
            },
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5',
            {
                extend: "selectedSingle",
                text: "Edit",
                action: function ( e, dt, node, config ) {
                    // Get the userID from the data array.
                    userId = table.row( { selected: true } ).data()[0];
                    getUserData(userId);
                }
            },
            {
                extend: "selectedSingle",
                text: "Disable",
                action: function ( e, dt, node, config ) {
                    // Get the userID from the data array.
                    userId = table.row( { selected: true } ).data()[0];
                    disableUser(userId);
                }
            }
        ],
        "columnDefs": [
    		{ "visible": false, "targets": 0 }
  		]
    } );
    
    dialog = $( "#dialog-form" ).dialog({
    	autoOpen: false,
		height: 400,
		width: 350,
		modal: true,
		buttons: [
			{
			text: "Edit User",
			click: editUser,
			classes: {
				"ui-button" : "ui-button-slim"	
				}	
			},
			{
			text: "Cancel",
			click: function() {
						dialog.dialog( "close" );
					}
			}
		],
		close: function() {
			form[ 0 ].reset();
			//allFields.removeClass( "ui-state-error" );
		}
	});
 
    form = dialog.find( "form" ).on( "submit", function( event ) {
      event.preventDefault();
      editUser();
    });
    
    function editUser(){
		$("#overlay").show();
    	//console.log($("#userId").val());
    	$.post( "users/edit_user.php", $( "#editUserForm" )
    	.serialize())
    	.done(function() {
    		//console.log('finished');
  			table.ajax.reload();
  			dialog.dialog( "close" );
  			$("#overlay").hide();
		})
		.fail(function( data ) {
			$("#overlay").hide();
		  });
		
    }
    
     function disableUser(userId){
    	//console.log($("#userId").val());
    	$.post( "users/disable_user.php", {userId : userId}).done(function() {
    		//console.log('finished');
  			table.ajax.reload();
		});
    }
    
    function getUserData(userId){
    	$("#overlay").show();
    	
		$.ajax({
  			url: "users/edit_user.php",
  			data: {userId: userId}
		}).done(function(data) {
  			var result = jQuery.parseJSON(data);
  			$.each(result, function(key, value){
  				//console.log("setting value for " + key + " : " + value);
  				if(key == 'camsSelected'){
  					value.split(",").forEach(function(selectedCam){
  						//console.log("setting selected for cam " + selectedCam);
  						$( ".ui-selectee").each(function(index) {
  							//console.log("checking if selected " + $(this).attr('value'));	
							if ($(this).attr('value') == selectedCam) {
								//console.log("succeded in marking selected for " + $(this).attr('value'));	
								$(this).addClass("ui-selected");
							}
						});
  					});
  				}
  				var elem = $("#"+key);
  				if(elem.is(":checkbox") && value == 'true'){
  					elem.prop('checked',true);
  				}else{
  					elem.val(value)
  				}
			});
			$("#overlay").hide();
			dialog.dialog( "open" );
	  	});
		
		
    }
    
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
	

} );

</script>

<table id="users" class="display" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>userId</th>
                <th>Name</th>
                <th>email</th>
                <th>userName</th>
                <th>Active</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>userId</th>
                <th>Name</th>
                <th>email</th>
                <th>userName</th>
                <th>Active</th>
            </tr>
        </tfoot>
    </table>
    <div id="dialog-form" title="Edit user">
		<form method="POST" id="editUserForm">
			<table>
				<tr>
					<td><label>First Name:</label></td>
					<td id="firstNameID"><input type="text" id="firstName" name="firstName"/></td>
				</tr>
				<tr>
					<td><label>Last Name:</label></td>
					<td><input type="text" id="lastName" name="lastName"/></td>
				</tr>
				<tr>
					<td><label>Email:</label></td>
					<td><input type="text" id="email" name="email"/></td>
				</tr>
			</table>
			<input type="hidden" id="userId" name="userId" />
			
			<p>
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
							echo '<span class="ui-widget-content" value= '. $cameraID . '>' . $cameraDescription . '</span>';
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
			
			
				<p>
				<label for="active">Active
				<input class="userOption" type="checkbox" name="userActive" id="userActive">
				</label>
				<br>
				<br>
				<label for="sendVerification">Send Verification Email
				<input class="userOption" type="checkbox" name="sendVerification" id="sendVerification">
				</label>
				</p>
			<input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
		</form>
	</div>
	