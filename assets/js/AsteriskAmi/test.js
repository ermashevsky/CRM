var nc = require("node-cat");
var express = require('express');

var app = express();
// Создаем HTTP-сервер с помощью модуля HTTP, входящего в Node.js. 
// Связываем его с Express и отслеживаем подключения к порту 8580. 

var server = require('http').createServer(app).listen(8383);
var io = require('socket.io').listen(server, {log: true});

var client = nc.createClient('192.168.0.91', 6003);

client.start();
