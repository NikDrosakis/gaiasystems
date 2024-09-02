// index.js
const express = require('express');
const path = require('path'); // Import the path module
const config = require('./config.json');
const { setupWebSocket } = require('./ws');  // Import WebSocket setup function
const timetableRouter = require('./services/timetable/timetableRouter');
//const openaiRouter = require('./services/openai/start');
const aistudio = require('./services/aistudio/start');
//const huggingface = require('./services/huggingface/start');
const app = express();
const fs = require("fs"),{promisify} = require("util"),https = require('https'),cors = require("cors"),cookieParser = require('cookie-parser'),compression = require('compression'),bodyParser = require("body-parser");
const privateKey = fs.readFileSync('/etc/letsencrypt/live/'+config.domain+'/privkey.pem', 'utf8'),certificate = fs.readFileSync( '/etc/letsencrypt/live/'+config.domain+'/fullchain.pem', 'utf8'),credentials = {key: privateKey, cert: certificate};

const apiRouter = require('./api');	
const fun = require("./functions");
// Import the API routes
app.use(express.static("public"));
app.use(cookieParser());
app.use(bodyParser.urlencoded({limit: '300mb', extended: true}));
app.use(cors({credentials: true, origin: config.whitelist}));
app.use((err, req, res, next) => {
    console.error(err.stack);
    res.status(500).send('Something broke!');
});
app.use('/api/v1/gaia',apiRouter);
// Middleware to parse JSON request bodies
app.use(express.json());
// Use timetableRouter for Gantt tasks
app.use('/api/v1/timetable', timetableRouter);
//app.use('/api/v1/openai', openaiRouter);
app.use('/api/v1/aistudio', aistudio);
//app.use('/api/v1/huggingface', huggingface);

const server = https.createServer(credentials, app);
setupWebSocket(server);
server.listen(config.httpsport, function () {
    console.log('Server listening on port ' + config.httpsport);
});
