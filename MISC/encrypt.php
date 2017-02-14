<html>
<body>

Welcome <?php echo $_POST["name"]; ?><br>
Your encrypted email address is: <?php echo $_POST["email"]; ?>
<br>
Your encrypted email address is: <?php echo password_hash($_POST["email"], PASSWORD_BCRYPT) ?>

</body>
</html> 

