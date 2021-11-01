<?php

require_once dirname(__DIR__) . '/src/Amsterdan/Auth/Session.php';
require_once dirname(__DIR__) . '/src/Amsterdan/Auth/Aes.php';

require_once dirname(__DIR__) . '/vendor/autoload.php';

$text = 'H7snlKbZeEJa3jqMVPgRb5eN7HtvyHD0loyizz5ZH7snlKbZeEJa3jqMVPgRb5eN7H';

$session = new Amsterdan\Auth\Session('uwwb0EfVj7Ox17T6YIC8GaMqqmHxNEstSD8cWIr3');

// 客户端参数
$config = [
    'clientSecret' => 'uwwb0EfVj7Ox17T6YIC8GaMqqmHxNEstSD8cWIr3',
];

$en = $session->setUid(1)->setUserName('Job')->encrypt();
var_dump($en);

$de = $session->decrypt($en);
var_dump($de);

$de = $session->decrypt('6An2B33nFN7qwbik1aa0racDL/ms/NP9gABRf0ISKuhz1S3yt0KRGN0iPFsWBwck');
var_dump($de);
