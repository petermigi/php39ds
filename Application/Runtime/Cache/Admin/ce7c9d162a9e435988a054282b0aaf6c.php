<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <input type="checkbox" name="test[]" value="1" checked="checked"/>足球
    <input type="checkbox" name="test[]" value="2" />篮球
    <input type="checkbox" name="test[]" value="3" />网球

    <input type="radio" name="test" value="1" />男
    <input type="radio" name="test" value="2" />女

    <select name="test">
        <option value="1">1</option>
        <option value="2">2</option>
        <option value="3">3</option>
    </select>
</body>
</html>