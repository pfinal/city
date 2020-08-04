<?php

include __DIR__ . "/vendor/autoload.php";

$json = file_get_contents('./pca-code.json');

$all = json_decode($json, true);

$config = [
    'host' => '127.0.0.1',
    'database' => 'test',
    'username' => 'root',
    'password' => 'root',
];

$db = new \PFinal\Database\Builder($config);

foreach ($all as $p) {
    if (!isset($p['children'])) {
        echo 'error 1';
        exit;
    }

    //省
    $data = ['id' => $p['code'], 'name' => $p['name'], 'parent_id' => ''];
    if (!$db->table('region')->insert($data)) {
        echo 'db error 1';
        exit;
    }

    echo '<div style="color:red">' . $p['code'] . ' ' . $p['name'] . '</div>';

    foreach ($p['children'] as $c) {
        //市
        $data = ['id' => $c['code'], 'name' => $c['name'], 'parent_id' => $p['code']];
        if (!$db->table('region')->insert($data)) {
            echo 'db error 2';
            exit;
        }

        if (!isset($c['children'])) {
            echo 'error 2';
            exit;
        }

        //区
        foreach ($c['children'] as $a) {
            $data = ['id' => $a['code'], 'name' => $a['name'], 'parent_id' => $c['code']];
            if (!$db->table('region')->insert($data)) {
                echo 'db error 3';
                exit;
            }
        }
    }

}


