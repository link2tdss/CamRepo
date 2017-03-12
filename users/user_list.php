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
                text: "EDIT",
                action: function ( e, dt, node, config ) {
                    // Immediately add `250` to the value of the salary and submit
                    //console.log(table.row( { selected: true } ).data());
                    getUserData();
                }
            }
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
		
    	//console.log($("#userId").val());
    	$.post( "users/edit_user.php", $( "#editUserForm" ).serialize()).done(function() {
    		//console.log('finished');
  			table.ajax.reload();
  			dialog.dialog( "close" );
		});
    }
    
    function getUserData(){
    	var userId = "link2tdss";
    	$.ajax({
  			url: "users/edit_user.php",
  			data: {userId: userId}
		}).done(function(data) {
			//console.log(data);
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
	  			$("#"+key).val(value);
			});
			dialog.dialog( "open" );
	  	});
		
		
    }
    
    $( "#selectable" ).selectable({
      stop: function() {
        var result = "";
        $( ".ui-selected", this ).each(function(index) {
        	if (index > 0) {
        		result = result + "," + $(this).attr('value');
        	}else{
        		result = $(this).attr('value');
        	}
        });
        $( "#camsSelected" ).val(result);
      }
    });
 
} );

</script>

<table id="users" class="display" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Name</th>
                <th>email</th>
                <th>userId</th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th>Name</th>
                <th>email</th>
                <th>userId</th>
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
			<input type="submit" tabindex="-1" style="position:absolute; top:-1000px">
		</form>
	</div>