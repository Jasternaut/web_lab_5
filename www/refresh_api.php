<?php
// refresh_api.php
session_start();
header('Content-Type: application/json');
require_once 'ApiClient.php';

$cacheFile = 'api_cache.json';
$cacheTtl = 300; // 5 минут
$data = null;

// проверяем: существует ли кеш и не слишком ли он старый
if (file_exists($cacheFile) && (time() - filemtime($cacheFile) < $cacheTtl)) {
    $data = json_decode(file_get_contents($cacheFile), true);
} 

// если кеша нет или он просрочен - идём в API
if (!$data || isset($data['error'])) {
    $api = new ApiClient();
    $data = $api->request('https://www.themealdb.com/api/json/v1/1/random.php');

    // сохраняем в кеш, только если API вернуло данные без ошибок
    if ($data && !isset($data['error'])) {
        file_put_contents($cacheFile, json_encode($data, JSON_UNESCAPED_UNICODE));
    }
}

$_SESSION['api_data'] = $data;
echo json_encode($data);