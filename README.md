# auth

### 引入代码包

```
composer require amsterdan5\auth;
```

### 使用方法
```
$secret = 'uwwb0EfVj7Ox17T6YIC8GaMqqmHxNEstSD8cWIr3';
$session = new Amsterdan\Auth\Session($secret);

$en = $session->setUid(1)->setUserName('Job')->encrypt();
var_dump($en);

$de = $session->decrypt($en);
var_dump($de);
```