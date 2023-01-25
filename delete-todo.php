<?php

$filename = __DIR__ . "/data/todos.json";
$todos = [];
$id = $_GET['id'] ?? '';


if($id){
    $data = file_get_contents($filename);
    $todos = json_decode($data, true) ?? [];
    if(count($todos)){
        $todoIndex = array_search($id, array_column($todos,'id'));
        array_splice($todos, $todoIndex,1);
        file_put_contents($filename, json_encode($todos,JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
}

header('Location: /');