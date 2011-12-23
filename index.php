<?php

#$configs = json_decode(file_get_contents('config.json'), true);
$configs = yaml_parse(file_get_contents('config.yml'));
require_once 'hamster.php';
Hamster::run($configs);