<?php

include __DIR__ . "/vendor/autoload.php";

$config = [
    'host' => '127.0.0.1',
    'database' => 'test',
    'username' => 'root',
    'password' => 'root',
];

$db = new \PFinal\Database\Builder($config);

$all = $db->table('region')->findAll();
$all = array_column($all, null, 'code');

foreach ($all as $item) {

    $ak = '';
    $address = getFullName($item['code'], $all);
    echo $address . "\r\n";
    $url = 'http://api.map.baidu.com/geocoder/v2/?address=' . $address . '&output=json&ak=' . $ak;

    //http://lbsyun.baidu.com/index.php?title=webapi/guide/webservice-geocoding
    //默认 bd09ll（百度经纬度坐标）

    //lat	纬度值
    //lng	经度值

    //precise  位置的附加信息，是否精确查找。1为精确查找，即准确打点；0为不精确，即模糊打点（模糊打点无法保证准确度）。
    //confidence 可信度，描述打点准确度，大于80表示误差小于100m。该字段仅作参考，返回结果准确度主要参考precise参数。
    //level 能精确理解的地址类型，包含：UNKNOWN、国家、省、城市、区县、乡镇、村庄、道路、地产小区、商务大厦、政府机构、交叉路口、商圈、生活服务、休闲娱乐、餐饮、宾馆、购物、金融、教育、医疗 、工业园区 、旅游景点 、汽车服务、火车站、长途汽车站、桥 、停车场/停车区、港口/码头、收费区/收费站、飞机场 、机场 、收费处/收费站 、加油站、绿地、门址


    //行政区划代码 adcode映射表 http://mapopen-pub-webserviceapi.bj.bcebos.com/geocoding/%E8%A1%8C%E6%94%BF%E5%8C%BA%E5%88%92%E6%B8%85%E5%8D%95%20V3.0%209.03.xlsx

    $client = new \PFinal\Http\Client();
    $response = $client->get($url);

    if ($response->getStatusCode() != 200) {
        echo 'error';
    }

    $json = $response->getBody();

    $arr = json_decode($json, true);

    file_put_contents(' log.txt', $address . ' ' . $json . "\n", FILE_APPEND);

    if (isset($arr['status']) && $arr['status'] == 0) {
        $row = $db->table('region')
            ->wherePk($item['code'])
            ->update($arr['result']['location']);

        if ($row != 1) {
            echo 'db err';
            exit;
        }
    } else {

        echo $address;
        echo $json;
        exit;
    }

}

echo "end";

function getFullName($code, $all)
{

    $one = $all[$code];

    $name = $one['name'];

    if ($one['parent_code'] == 0) {
        return $name;
    }

    $parent = $all[$one['parent_code']];

    $name = $parent['name'] . $name;

    if ($parent['parent_code'] == 0) {
        return $name;
    }

    $pprent = $all[$parent['parent_code']];
    return $pprent['name'] . $name;
}