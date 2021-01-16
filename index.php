<?php

$contentType = isset($_SERVER["CONTENT_TYPE"]) ? trim($_SERVER["CONTENT_TYPE"]) : '';

if ($contentType === "application/json") {
    //Receive the RAW post data.
    $content = trim(file_get_contents("php://input"));

    $decoded = json_decode($content, true);

    switch ($decoded["method"]) {
        case 'userLog':
            if (is_array($decoded)) {
                $redis = new Redis();
                $redis->connect("127.0.0.1", "6379");
                $login = $decoded[params][username];
                if ($redis->exists($login)) {
                    $login = $decoded[params][username];
                    $password = $redis->hGet($login, "password");
                    $salt = $redis->hGet($login, "salt");
                    if ($password == hash("sha256", $decoded[params][password] . $salt)) {
                        $redis->close();
                        header('Content-Type: application/json');
                        echo json_encode($decoded['params']['username']);
                        break;
                    } else {
                        $redis->close();
                        header("HTTP/1.0 404 Not Found");
                        break;
                    }
                } else {
                    $redis->close();
                    header("HTTP/1.0 404 Not Found");
                    break;
                }
            }

        case 'ownerLog':
            if (is_array($decoded)) {
                $redis = new Redis();
                $redis->connect("127.0.0.1", "6379");
                $login = $decoded[params][username];
                if ($redis->exists($login)) {
                    $login = $decoded[params][username];
                    $password = $redis->hGet($login, "password");
                    $salt = $redis->hGet($login, "salt");
                    if ($password == hash("sha256", $decoded[params][password] . $salt)) {
                        $redis->close();
                        header('Content-Type: application/json');
                        echo json_encode($decoded['params']['username']);
                        break;
                    } else {
                        $redis->close();
                        header("HTTP/1.0 404 Not Found");
                        break;
                    }
                } else {
                    $redis->close();
                    header("HTTP/1.0 404 Not Found");
                    break;
                }
            }

        case 'adminLog':
            if (is_array($decoded)) {
                $redis = new Redis();
                $redis->connect("127.0.0.1", "6379");
                $login = $decoded[params][username];
                if ($redis->exists($login)) {
                    $login = $decoded[params][username];
                    $password = $redis->hGet($login, "password");
                    $salt = $redis->hGet($login, "salt");
                    if ($password == hash("sha256", $decoded[params][password] . $salt)) {
                        $redis->close();
                        header('Content-Type: application/json');
                        echo json_encode($decoded['params']['username']);
                        break;
                    } else {
                        $redis->close();
                        header("HTTP/1.0 404 Not Found");
                        break;
                    }
                } else {
                    $redis->close();
                    header("HTTP/1.0 404 Not Found");
                    break;
                }
            }

        case 'userReg':
            $redis = new Redis();
            $redis->connect("127.0.0.1", "6379");
            $login = $decoded[params][username];
            if (!($redis->exists($login))) {
                $min = PHP_INT_MIN;
                $max = PHP_INT_MAX;
                $salt = random_int($min, $max);
                $password = hash("sha256", $decoded[params][password] . $salt);
                $food = hash("sha256", $decoded[params][food] . $salt);
                $tmp_array = array(
                    "password" => $password,
                    "food" => $food,
                    "salt" => $salt
                );
                $redis->hMSet($login, $tmp_array);
                $redis->close();
                header('Content-Type: application/json');
                echo json_encode($decoded['params']['username']);
                break;
            } else {
                $redis->close();
                header("HTTP/1.0 404 Not Found");
                break;
            }

        case 'ownerReg':
            if (is_array($decoded)) {
                $redis = new Redis();
                $redis->connect("127.0.0.1", "6379");
                $login = $decoded[params][username];
                if (!($redis->exists($login))) {
                    $login = $decoded[params][username];
                    $min = PHP_INT_MIN;
                    $max = PHP_INT_MAX;
                    $salt = random_int($min, $max);
                    $password = hash("sha256", $decoded[params][password] . $salt);
                    $food = hash("sha256", $decoded[params][food] . $salt);
                    $tmp_array = array(
                        "password" => $password,
                        "food" => $food,
                        "salt" => $salt
                    );
                    $redis->hMSet($login, $tmp_array);
                    $redis->close();
                    header('Content-Type: application/json');
                    echo json_encode($decoded['params']['username']);
                    break;
                } else {
                    $redis->close();
                    header("HTTP/1.0 404 Not Found");
                    break;
                }
            }

        case 'readReviews':
            $redis = new Redis();
            $redis->connect("127.0.0.1", "6379");
            $restName = $decoded[params][name];
            $reviewsNumber = $redis->hGet($restName, "reviewsNumber");
            $tmp_array = array();
            for ($i = 1; $i <= $reviewsNumber; $i++) {
                $tmp_array[] = $redis->hGet($restName, $i);
            }
            $redis->close();
            header('Content-Type: application/json');
            echo json_encode($tmp_array);
            break;
    }
}


