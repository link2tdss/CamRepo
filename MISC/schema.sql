mysql> create database cam_schema
    -> ;
Query OK, 1 row affected (0.00 sec)

mysql> create user 'cam_schema_admin' identified by 'Pass$123';
Query OK, 0 rows affected (0.00 sec)

mysql> grant cam_schema.* to cam_schema_Admin;
ERROR 1064 (42000): You have an error in your SQL syntax; check the manual that corresponds to your MySQL server version for the right syntax to use near 'cam_schema.* to cam_schema_Admin' at line 1
mysql> GRANT ALL PRIVILEGES ON cam_schema. * TO 'cam_schema_admin';
Query OK, 0 rows affected (0.00 sec)

mysql> flush privileges;
Query OK, 0 rows affected (0.00 sec)

CREATE TABLE cam_users (
	userID		VARCHAR(20), 
	userName	VARCHAR(20),
    password	VARCHAR(50), 
    firstName	VARCHAR(50
);)

insert into cam_users values('user1', 'user_one', 'password1', 'user_one');