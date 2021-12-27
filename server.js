//tutorial
const express = require('express');
const app = express();
const server = require('http').createServer(app);
const io = require('socket.io')(server, {
    cors : { origin : "*"}
});

var users = [];

server.listen(8009, function(){
    console.log('listening to port 8009, (auf englisch)');
});

io.on('connection', (socket) => {
    socket.on("user_connected", (user_id) => {
        users[user_id] = socket.id;
        io.emit('updateUserStatus', users);
        console.log("user connected "+ user_id);
    });

    socket.on("disconnect", () => {
        var i = users.indexOf(socket.id);
        users.splice(i, 1, 0);
        io.emit('updateUserStatus', users);
        console.log(users);
    });
});






