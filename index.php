<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
  		<script src="js/base.js"></script>
  		
		<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/ju-1.11.4/jq-2.2.4/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.13/b-1.2.4/b-colvis-1.2.4/b-flash-1.2.4/b-html5-1.2.4/b-print-1.2.4/cr-1.3.2/se-1.2.0/datatables.min.css"/>
 		<script type="text/javascript" src="https://cdn.datatables.net/v/ju-1.11.4/jq-2.2.4/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.13/b-1.2.4/b-colvis-1.2.4/b-flash-1.2.4/b-html5-1.2.4/b-print-1.2.4/cr-1.3.2/se-1.2.0/datatables.min.js"></script>
		
		<link rel="stylesheet" type="text/css" href="css/base.css">
		<link rel="stylesheet" type="text/css" href="css/jquery-ui.css">
		<link rel="stylesheet" type="text/css" href="css/jqtransform.css">
		<script type="text/javascript" src="js/jquery.jqtransform.js" ></script>
      <script>
        $(document).ready(function(){
          registerMenus();
        });
      </script>
  </head>
<body>

<ul class="topnav" id="myTopnav">
  <li><a class="active" href="#home">Home</a></li>
  <li><a href="#admin">Administration</a></li>
  <li><a href="#cams">Cameras</a></li>
  <li><a href="#users">User Management</a></li>
  <li class="icon">
    <a href="javascript:void(0);" style="font-size:15px;" onclick="redrawMenu()">☰</a>
  </li>
</ul>

<div style="padding-left:16px">
  <div id="bodyContainer">
  </div>
</div>



</body>
</html>