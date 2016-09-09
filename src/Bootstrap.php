<?php

date_default_timezone_set('Asia/Jakarta');

if (version_compare(phpversion(), '5.4.0', '<')) {
    throw new Exception(json_encode(['status' => false, 'message' => "PHP version isn't high enough"]));
}
if (array_search('json', get_loaded_extensions()) === false) {
    throw new Exception(json_encode(['status' => false, 'message' => 'Extension json not exist']));
}
if (array_search('curl', get_loaded_extensions()) === false) {
    throw new Exception(json_encode(['status' => false, 'message' => 'Extension curl not exist']));
}
if (file_exists(dirname(__FILE__).'/.env') === false) {
    throw new Exception(json_encode(['status' => false, 'message' => 'File .env not exist']));
}

$dotenv = new Dotenv\Dotenv(__DIR__);
$dotenv->load();
