<?php

if (empty($_SERVER['PHP_AUTH_USER']) ||
    empty($_SERVER['PHP_AUTH_PW']) ||
    $_SERVER['PHP_AUTH_USER'] != 'admin' ||
    md5($_SERVER['PHP_AUTH_PW']) != md5('admin')) {
    header('HTTP/1.1 401 Unanthorized');
    header('WWW-Authenticate: Basic realm="My site"');
    print('<h1>401 Требуется авторизация</h1>');
    exit();
}

$db_user = 'u20983';   // Логин БД
$db_pass = '3425454';  // Пароль БД

$db = new PDO('mysql:host=localhost;dbname=u20983', $db_user, $db_pass, array(
    PDO::ATTR_PERSISTENT => true
));

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $stmt = $db->prepare('DELETE FROM userProfile WHERE uid = ?');
        $stmt->execute(array(
            $_POST['remove']
        ));
        $stmt = $db->prepare('DELETE FROM users WHERE login = ?');
        $stmt->execute(array(
            $_POST['remove']
        ));
    } catch (PDOException $e) {
        echo 'Ошибка: ' . $e->getMessage();
        exit();
    }
}

try {
    $stmt = $db->query(
        'SELECT * FROM userProfile'
    );
    $stmt = $db->query(
        'SELECT (length(us.powers) - length(replace(us.powers, "tp", "")))/2 as tp
        FROM userProfile us'
    );
    ?>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Панель Администратора</title>
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.8.2/css/bulma.min.css">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <table class="table is-hoverable is-fullwidth">
            <thead>
                <tr>
                    <th>Телепортация</th>
                    <th>Ночное зрение</th>
                    <th>Левитация</th>
                    <th>Год гождения</th>
                    <th>Пол</th>
                    <th>Количество конечностей</th>
                    <th>Сверхспособности</th>
                    <th>Биография</th>
                    <th>Удалить</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {
                        print('<tr>');
                        foreach ($row as $cell) {
                            print('<td>' . $cell . '</td>');
                        }
                        print('</tr>');
                    }
                ?>
            </tbody>
        </table>
    <form action="" method="post">
        <table class="table is-hoverable is-fullwidth">
            <thead>
            <tr>
                <th>Логин</th>
                <th>Имя</th>
                <th>Email</th>
                <th>Год гождения</th>
                <th>Пол</th>
                <th>Количество конечностей</th>
                <th>Сверхспособности</th>
                <th>Биография</th>
                <th>Удалить</th>
            </tr>
            </thead>
            <tbody>
            <?php
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                print('<tr>');
                foreach ($row as $cell) {
                    print('<td>' . $cell . '</td>');
                }
                print('<td><button class="button is-info is-small is-danger is-light" name="remove" type="submit" value="' . $row['login'] . '">x</button></td>');
                print('</tr>');
            }
            ?>
            </tbody>
        </table>
    </form>
    </body>
    <?php
} catch (PDOException $e) {
    echo 'Ошибка: ' . $e->getMessage();
    exit();
}