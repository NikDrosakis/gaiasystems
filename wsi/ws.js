// ws.js
const WebSocket = require('ws');
const Redis = require('ioredis');
const config = require("./config.json");
const uaParser = require('ua-parser-js'),fun=require("./functions");

let wss;  // WebSocket server instance
let activeConnections = 0;
let deviceStats = {};
let uniqueUserSet = new Set();
const recentlyBroadcastedActivities = new Set();
const { getCounters } = require('./mods/N');  // Import the counters

// Initialize Redis client for both subscribing and publishing
const redisClient = new Redis({
    host: 'localhost',
    port: 6379,
    password: 'yjF1f7uiHttcp'
});

// Function to broadcast a message using Redis Pub/Sub
function broadcastMessage(message) {
    // Generate a unique identifier for the message
  //  const messageId = message.id || generateUniqueId(message); // Ensure you have a unique ID

    // Check if the message has already been broadcasted
   // if (recentlyBroadcastedActivities.has(messageId)) {
     //   console.log('Message already broadcasted, skipping:', message);
       // return;
    //}

    // Add messageId to the set
    ///recentlyBroadcastedActivities.add(messageId);

    // Broadcast the message
    redisClient.publish('broadcast_channel', JSON.stringify(message));

    // Optionally, remove the messageId from the set after a timeout to allow for re-broadcasting
    //setTimeout(() => {
      //  recentlyBroadcastedActivities.delete(messageId);
    //}, 60000); // Adjust the timeout as needed
}

function generateUniqueId(message) {
    // Generate a unique ID based on message content or timestamp
    return `${message.type}-${message.timestamp || Date.now()}`;
}

function setupWebSocket(server) {
    wss = new WebSocket.Server({ server });

    // Subscribe to Redis channel for broadcasts
    const redisClient  = new Redis({
        host: 'localhost',
        port: 6379,
        password: 'yjF1f7uiHttcp'
    });

    redisClient.subscribe('broadcast_channel', (err) => {
        if (err) {
            console.error('Failed to subscribe to Redis channel:', err);
        }
    });
    redisClient.on('message', (channel, message) => {
        if (channel === 'broadcast_channel') {
            // Broadcast the message to all connected clients
            wss.clients.forEach((client) => {
                if (client.readyState === WebSocket.OPEN) {
                    client.send(message);
                }
            });
        }
    });

    wss.on('connection', async (ws, req) => {
        const urlParams = new URLSearchParams(req.url.replace('/', ''));
        const uid = urlParams.get('uid') || 'guest';
        const userAgent = req.headers['user-agent'] || 'Unknown';

        // Capture the IP address of the client
        const ip = req.headers['x-forwarded-for'] || req.connection.remoteAddress;

        activeConnections++;
     //   broadcastMessage({title:'connected',type:'console',text:`User ${uid} connected from IP ${ip}. Active connections: ${activeConnections}`});

        // Update device statistics
        updateDeviceStats(userAgent, ip);

        // Handle incoming messages
        ws.on('message', async (data) => {
            let message;

            // If the data is a buffer, convert it to a string
            if (Buffer.isBuffer(data)) {
                data = data.toString();
            }

            try {
                message = JSON.parse(data);
            } catch (err) {
                console.error('Failed to parse JSON:', err);
                return;
            }

            if (message.type === 'PING') {
                ws.send(JSON.stringify({ type: 'PONG' })); // Send PONG response
                ws.isAlive = true; // Mark this connection as alive
                return;
            }

            switch (message.cast) {
                case 'broadcast':
                    broadcastMessage(message);
                    break;
                case 'one':
                    if (mes.to) {
                        const to = `user${mes.to}`;
                        const recipientWs = Array.from(wss.clients).find(client => client.uid === message.to);
                        if (recipientWs) {
                            recipientWs.send(message);
                        }
                    }
                    break;
            }
        });
        //broadcast N
        try {
            const counters = await getCounters();
//                   console.log(counters);
broadcastMessage({ type: 'N', text:counters,class:"c_square cblue" });
        } catch (err) {
            console.error('Failed to get counters:', err);
        }

        // Handle disconnection
        ws.on('close', () => {
            activeConnections--;
            //console.log(`User ${ip} disconnected. Active connections: ${activeConnections}`);
            // Broadcast the disconnection event
        //    broadcastMessage({title:'disconnected',type:'console',text:`User ${uid} disconnected. Active connections: ${activeConnections}`});
            // Decrement device statistics
            decrementDeviceStats(userAgent, ip);

            // Broadcast updated active users
            broadcastActiveUsers();
        });

        ws.isAlive = true;
        ws.uid = uid;  // Assign UID to WebSocket instance

        // Broadcast updated active users
        broadcastActiveUsers();

    });

// Function to broadcast unique active users count
    function broadcastActiveUsers() {
        const uniqueUserCount = uniqueUserSet.size;
        const message = {
            type: 'html',
            id: 'active_users',
            html: uniqueUserCount === 0 ? '' : `<em class="c_Bottom cred">${uniqueUserCount}</em>`,
            title:'broadcastActiveUsers'
        };
        broadcastMessage(message);
    }

// Interval to check active connections
    const interval = setInterval(() => {
        wss.clients.forEach(ws => {
            if (ws.isAlive === false) {
                return ws.terminate();
            }
            ws.isAlive = false;
        });

        // Log device statistics periodically
        console.log('Device Statistics:', deviceStats);
        console.log('Unique Users:', uniqueUserSet.size);
    }, 40000);

    wss.on('close', () => {
        clearInterval(interval);
    });

}

function updateDeviceStats(userAgent, ip) {
    const deviceInfo = uaParser(userAgent);
    const { browser, os, device } = deviceInfo;

    const key = `${browser.name || 'Unknown Browser'} - ${os.name || 'Unknown OS'} - ${device.type || 'Unknown Device'}`;
    const uniqueUserKey = `${ip}-${browser.name}-${device.type || 'web'}`;

    if (!deviceStats[key]) {
        deviceStats[key] = 0;
    }
    deviceStats[key]++;

    uniqueUserSet.add(uniqueUserKey);
}

function decrementDeviceStats(userAgent, ip) {
    const deviceInfo = uaParser(userAgent);
    const { browser, os, device } = deviceInfo;

    const key = `${browser.name || 'Unknown Browser'} - ${os.name || 'Unknown OS'} - ${device.type || 'Unknown Device'}`;
    const uniqueUserKey = `${ip}-${browser.name}-${device.type || 'web'}`;

    if (deviceStats[key]) {
        deviceStats[key]--;
        if (deviceStats[key] === 0) {
            delete deviceStats[key];  // Remove key if count reaches zero
        }
    }

    uniqueUserSet.delete(uniqueUserKey);
}

module.exports = {
    setupWebSocket,
    broadcastMessage
};
