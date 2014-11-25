client-php-pinoccio
===================

php client library for interacting with your scouts via the pinoccio api


```php
<?
require("./client-php-pinoccio/pinoccio.php");

// you can get your api token from https://hq.pinocc.io/settings
$api = new \pinoccio\Pinoccio($token);

// if you dont have a token and dont mind hardcoding credentials in a script =(.
$api->login("user","password"));

// if you have a token already skip login and set the token
$troopState = $api->rest("get","/v1/sync",array("tail"=>0));

var_dump($troopState);

```
this logs in, and print's the current state of all of the scouts in all of your troops.


the returned object would look something like this.

```php
array(26) {
  [0]=>
  array(1) {
    ["data"]=>
    array(5) {
      ["account"]=>
      string(4) "1971"
      ["troop"]=>
      string(1) "5"
      ["type"]=>
      string(10) "connection"
      ["value"]=>
      array(1) {
        ["status"]=>
        string(7) "offline"
      }
      ["time"]=>
      float(1401372795670)
    }
  }
  [1]=>
  array(1) {
    ["data"]=>
    array(5) 
.....
```



