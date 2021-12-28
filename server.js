//tutorial
var express = require('express');
var app = express();
var server = require('http').createServer(app);
var io = require('socket.io')(server, {
    cors : { origin : "*"}
});

require('dotenv').config();

var users = [];

server.listen(process.env.BROADCAST_PORT, function(){
    console.log(`listening to port ${process.env.BROADCAST_PORT}, (auf englisch)`);
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
    });
});

//redis configuraitons
var redis = require('ioredis');
var subscriber = new redis();
var privateChannel = `${process.env.APP_NAME}_database_private-channel`;

subscriber.subscribe(privateChannel, () => {
    console.log('abonniert zu allen kanals');
});

subscriber.on('message', (channel, message) => {
    if(channel == privateChannel){
        let decodedMsg= JSON.parse(message);
        let data = decodedMsg.data.data;
        let receiver_id = data.receiver_id;
        let event = decodedMsg.event;

        io.to(`${users[receiver_id]}`).emit(`${channel}:${event}`, data);
    }
});

