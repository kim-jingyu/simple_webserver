<?php
    require_once $_SERVER['DOCUMENT_ROOT'].'/vendor/autoload.php';

	use Firebase\JWT\JWT;
	use Firebase\JWT\Key;

    function createToken($userId, $userName) {
        $secret_key = 'codemysecretkey'; // secret key
	
        $payload = [
            "exp" => time() + 30*60, // 30분
            "iat" => time(),
            "user" => "$userId",
            "name" => "$userName"
        ];

        $jwt = JWT::encode($payload, $secret_key, 'HS256');

        // echo $jwt."<br>";
        // $split_token = explode('.', $jwt); // .을 기준으로 문자열을 나누고, 배열로 저장
        // foreach($split_token as $value) {
        //     echo "(before) ".$value." : (after) ".base64_decode($value)."<br>";
        // }

        return $jwt;
    }

	
	function getToken($jwt) {
        $secret_key = 'codemysecretkey'; // secret key
        $decoded_jwt = (array)JWT::decode($jwt, new Key($secret_key, 'HS256'));
	    
        return $decoded_jwt;
    }

    function checkToken() {
        try {
            if (!isset($_COOKIE['JWT'])) {
                throw new Exception;
            }
            getToken($_COOKIE['JWT'])['user'];
        } catch (Exception $e) {
            header("location:/application/view/login/login.html");
        }
    }
?>