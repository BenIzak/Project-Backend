<?php
require_once("./php/sql.php");

function IsLog() {
    return GetID() != -1;
}

function GetID() {
    require("./php/sql.php");

    if (!isset($_COOKIE["Authorisation"])) return -1;
    $req = $pdo->prepare("SELECT UID FROM webtokens WHERE token = :tok");
    $req->execute([
        ":tok" => $_COOKIE["Authorisation"]
    ]);
    $t = $req->fetchAll(PDO::FETCH_ASSOC);
    if ($req->rowCount() == 0) return -1;
    return $t[0]["UID"];
}

function Context() {
    $data = [
        "Log" => IsLog(),
        "ID" => GetID(),
    ];
    if (IsLog()) {
        $data["User"] = GetUserData($data["ID"]);
    }
    return $data;
}

function GetSafeUsername($username) {
    $temp = str_replace(" ", "_", trim($username));
    return strtolower($temp);
}

function GetIDFromUsername($username) {
    require("./php/sql.php");
    
    $req = $pdo->prepare("SELECT ID FROM users WHERE username_safe = :usr");
    $req->execute([
        ":usr" => GetSafeUsername($username)
    ]);
    $t = $req->fetchAll(PDO::FETCH_ASSOC);
    if ($req->rowCount() == 0) return -1;
    return $t[0]["ID"];
}

function GetUserData($id) {
    require("./php/sql.php");

    $req = $pdo->prepare("SELECT * FROM users WHERE id = :id");
    $req->execute([
        ":id" => $id
    ]);
    return $req->fetch(PDO::FETCH_ASSOC);
}


function GetRandomString($length = 8) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}
