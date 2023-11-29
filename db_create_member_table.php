<?php
        define('DB_SERVER', 'localhost');
        define('DB_USERNAME', 'jingyu');
        define('DB_PASSWORD', '1234');
        define('DB_NAME', 'test');

        $db_conn = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_NAME);

	$sql = "create table member(
			idx INT(10) NOT NULL AUTO_INCREMENT,
			user_id varchar(20) NOT NULL,
			user_pw varchar(20) NOT NULL,
			user_name varchar(20) NOT NULL,
			user_level varchar(20),
			user_info varchar(50)
			CONSTRAINT member_PK PRIMARY KEY(idx)
		);"

	$result = mysqli_query($db_conn, $sql);

	var_dump($result);
?>
