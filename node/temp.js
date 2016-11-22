var express = require('express');
var app = express();
var http = require('http').Server(app);
var io = require('socket.io')(http);

var socket_ids = [];

io.on('connection', function(socket){

  console.log('Connection socket.id: ' + socket.id);

  socket.on('set_id', function(id) {
    socket_ids[id] = socket.id;
    console.log(socket.id);
  });

  socket.on('disconnect', function () {
      //logger.info('SocketIO > Disconnected socket ' + socket.id);
  });

  socket.on('success', function (id) {
    console.log(str);
    socket.broadcast.emit('receiverId', id);
  });
});

http.listen('3000', function(){
  console.log("server on!");
});
