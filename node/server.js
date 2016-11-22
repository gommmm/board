var express = require('express');
var app = express();
var http = require('http').Server(app);
var io = require('socket.io')(http);

app.get("/", function(req, res) {
  res.sendfile("client.html");
});

io.on('connection', function(socket) {
  var room_id = '';
  var my = '';
  var you = '';
  var my_room = '';
  var your_room = '';

  console.log('user connected: ', socket.id);

  // 1:1 대화처리 웹소켓
  socket.on('join', function(id, receiverId) {
    my = id + receiverId;
    you = receiverId + id;
    my_room = io.sockets.adapter.rooms[my];
    your_room = io.sockets.adapter.rooms[you];

    if((my_room === undefined && your_room === undefined) || my_room !== undefined) {
      socket.join(my);
      room_id = my;
    } else {
      socket.join(you);
      room_id = you;
    }

    //console.log(io.sockets.adapter.rooms);
  });

  socket.on('send message', function(id, receiverId, text) {
    if((my_room === undefined && your_room === undefined) || my_room !== undefined) { // 방 생성자가 나 자신이면 나 자신이 만든 방의 참여자에게 메시지를 보냄
      io.to(my).emit('message', text, id);
    } else { // 방 생성자가 상대방이면 상대방이 만든 방의 참여자에게 메시지를 보냄
      io.to(you).emit('message', text, id);
    }
  });

  // 실시간 쪽지 처리 웹소켓
  socket.on('send success', function (id) {
    socket.broadcast.emit('send id', id);
  });

  socket.on('disconnect', function() {
    console.log('user disconnected: ', socket.id);
    socket.leave(room_id);
    //console.log(io.sockets.adapter.rooms);
  });
});

http.listen('3000', function() {
  console.log("server on!");
});
