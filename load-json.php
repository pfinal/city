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
    if (!isset($p['childs'])) {
        echo 'error 1';
        exit;
    }

    //省
    $data = ['code' => $p['code'], 'name' => $p['name'], 'parent_code' => 0];
    if (!$db->table('region')->insert($data)) {
        echo 'db error 1';
        exit;
    }

    echo '<div style="color:red">' . $p['code'] . ' ' . $p['name'] . '</div>';

    foreach ($p['childs'] as $c) {
        //echo '<div style="color:blue;">' . $c['code'] . ' ' . $c['name'] . '</div>';

        //市
        $data = ['code' => $c['code'], 'name' => $c['name'], 'parent_code' => $p['code']];
        if (!$db->table('region')->insert($data)) {
            echo 'db error 2';
            exit;
        }

        if (!isset($c['childs'])) {
            echo 'error 2';
            exit;
        }

        //区
        foreach ($c['childs'] as $a) {
            //echo $a['code'] . ' ' . $a['name'] . '<br>';

            $data = ['code' => $a['code'], 'name' => $a['name'], 'parent_code' => $c['code']];
            if (!$db->table('region')->insert($data)) {
                echo 'db error 3';
                exit;
            }
        }
    }

}


