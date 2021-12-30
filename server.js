//tutorial
var express = require('express');
var app = express();
var server = require('http').createServer(app);
var io = require('socket.io')(server, {
    cors : { origin : "*"}
});

require('dotenv').config();

var users = [];
var groups = [];

server.listen(process.env.BROADCAST_PORT, function(){
    console.log(`listening to port ${process.env.BROADCAST_PORT}, (auf englisch)`);
});


io.on('connection', (socket) => {
    socket.on("user_connected", (user_id) => {
        users[user_id] = socket.id;
        io.emit('updateUserStatus', users);
        console.log(`user ${user_id} connected with socket ${socket.id}`);
    });

    socket.on("joinGroup", (data) => {
        data["socket_id"] = socket.id;
        let group_id = data.group_id;
        let user_id = data.user_id;

        console.log(`user ${user_id} joined with socket ${data.socket_id}`);

        if (groups[group_id]) {
            console.log("group already exist");
            var userExist = checkIfUserExistInGroup(user_id, group_id);
            if (!userExist) {
                groups[group_id].push(data);
                socket.join(data.room);
            } else {
                var index = groups[group_id].map(function(o) {
                    return o.user_id;
                }).indexOf(user_id);
                groups[group_id][index] = data;
                socket.join(data.room);
            }
        } else {
            console.log("new group");
            groups[group_id] = [data];
            socket.join(data.room);
        }
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
var groupChannel = `${process.env.APP_NAME}_database_group-channel`;

subscriber.subscribe(privateChannel, () => {
    console.log('abonniert zu allen kanals');
});

subscriber.subscribe(groupChannel, () => {
    console.log('abonniert zu gruppe');
});

subscriber.on('message', (channel, message) => {
    if(channel == privateChannel){
        let decodedMsg= JSON.parse(message);
        let data = decodedMsg.data.data;
        let receiver_id = data.receiver_id;
        let event = decodedMsg.event;

        io.to(`${users[receiver_id]}`).emit(`${channel}:${event}`, data);
    }

    if(channel == groupChannel){
        let decodedMsg= JSON.parse(message);
        let data = decodedMsg.data.data;
        let event = decodedMsg.event;

        if (data.type == 2) {
            let socket_id = getSocketIdOfUserInGroup(data.sender_id, data.group_id);
            let socket = io.sockets.sockets.get(socket_id);
            let room = getRoomID(data.group_id);
            socket.to(room).emit(`${channel}:${event}`, data);
        }
    }
});


function checkIfUserExistInGroup(user_id, group_id) {
    var group = groups[group_id];
    if(group){
        return group.some(member => member.user_id == user_id);
    }
}


function getSocketIdOfUserInGroup(user_id, group_id) {
    var group = groups[group_id];
    if(group){
        return group.filter(member => member.user_id == user_id).map(member => member.socket_id).shift();
    }
}

function getRoomID(group_id) {
    var group = groups[group_id];
    if(group){
        return group.map(member => member.room).shift();
    }
}
