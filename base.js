function registerMenus(){
	registerHomeMenu();
	registerAdminMenu();
	registerCamMenu();
	registerUsersMenu();
}

function redrawMenu() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}

function registerHomeMenu(){
	$('a[href="#home"]').click(function(){
		unloadMenus();
	  $("#bodyContainer").load("home.php");

	});
}

function registerAdminMenu(){
	$('a[href="#admin"]').click(function(){
		unloadMenus();
	  $("#bodyContainer").load("admin/admin.php");
	});
}

function registerCamMenu(){
	$('a[href="#cams"]').click(function(){
		unloadMenus();
	  $("#bodyContainer").load("cams/cams.php");
	});
}

function registerUsersMenu(){
	$('a[href="#users"]').click(function(){
		unloadMenus();
	  $("#bodyContainer").load("users/users.php");
	});
}

function unloadMenus(){
	$("#homePage").remove();
	$("#adminPage").remove();
	$("#camPage").remove();
	$("#userPage").remove();
	window.stop();
}