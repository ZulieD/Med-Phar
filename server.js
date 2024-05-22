
/*
const mariadb = require("mariadb");
const pool = mariadb.createPool({
    host: "localhost", user: "root", password: "", database: "carsdb"
});
pool.getConnection().then(function(conn){
    conn.query("SELECT * FROM users").then(function(rows){ console.log(rows) });
    conn.query("SELECT * FROM cars").then(function(rows){ console.log(rows); process.exit(); });
});
return;
*/

const dotenv = require('dotenv');
dotenv.config();

const express = require('express');
const app = express();
app.set("view engine", "ejs");
app.set("views", "views");
app.listen(process.env.WEB_PORT, '0.0.0.0',
    function() { console.log("Listening on "+process.env.WEB_PORT); }
);

app.get('/', (request, response) => { // 'GET' as a HTTP VERB, not as a 'getter'!
    let clientIp = request.ip;
    response.send(`Hello, dear ${clientIp}. Tu as enfin réussi`);
    response.end(); // optional
});

// MIDDLEWARE REGISTRATIONS
// app.use(callback1, callback2, callback3)
const bodyParser = require("body-parser");
app.use(bodyParser.json(), bodyParser.urlencoded({ extended: true }));

const session = require("express-session");
app.use(session({
    secret: "SecretRandomStringDskghadslkghdlkghdghaksdghdksh",
    saveUninitialized: true,
    cookie: { maxAge: 1000 * 60 * 60 * 24 }, // 1 day in msec
    resave: true
}));

const auth = require("./Repository/users.auth");
auth.initialization(app);

app.use("/CSS", express.static(__dirname + '/CSS'));
app.use("/image", express.static(__dirname + '/image'));
app.use("/main_page", require("./controllers/mainpage.route"));
app.use("/CreateAccount", require("./controllers/CreateAccountpage.route"));
