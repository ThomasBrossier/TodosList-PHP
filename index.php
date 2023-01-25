<?php
const ERROR_TOO_SHORT = 'Votre todo doit faire au moins 5 caractères';
const ERROR_REQUIRED = 'Veuillez saisir une todo';
$error = '';
$filename = __DIR__ . "/data/todos.json";
$todos = [];

if(file_exists($filename)){
    $data = file_get_contents($filename);
    $todos = json_decode($data, true) ?? [];
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $_POST = filter_input_array(INPUT_POST, [
        "todo" => [
            "filter" => FILTER_SANITIZE_FULL_SPECIAL_CHARS,
            "flags" => FILTER_FLAG_NO_ENCODE_QUOTES | FILTER_FLAG_STRIP_LOW | FILTER_FLAG_STRIP_BACKTICK
        ]
    ]);

    $todo = $_POST['todo'] ?? '';
    if(!$todo){
        $error = ERROR_REQUIRED;
    }else if (mb_strlen($todo) < 5){
        $error = ERROR_TOO_SHORT;
    }
    if(!$error){
        $todos = [...$todos,
                ['name'=> $todo,
                'id' => time(),
                'done' => false]
        ];
        file_put_contents($filename, json_encode($todos, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));
    }
}

?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="public/css/styles.css">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;700&display=swap" rel="stylesheet">
    <script async src="public/js/index.js"></script>
    <title>Todo</title>
</head>

<body>
<div class="container">
    <?php require_once 'includes/header.php' ?>
    <div class="content">
        <div class="todo-container">
            <h1>Ma Todo</h1>
            <form class="todo-form" action="/" method="post">
                <input type="text" name="todo">
                <button class="btn btn-primary">Ajouter</button>
            </form>
            <?php if ($error) : ?>
                <p class="text-danger"><?= $error ?></p>
            <?php endif; ?>
            <div class="todo-list">
                <ul class="todo-list">
                    <?php foreach($todos as $t): ?>

                        <li class="todo-item">
                            <span class="todo-name"><?= $t['name'] ?></span>
                            <button class="btn btn-primary btn-small">Valider</button>
                            <button class="btn btn-danger btn-small">Supprimer</button>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>

    </div>
    <?php require_once 'includes/footer.php' ?>
</div>
</body>

</html>