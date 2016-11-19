require('dotenv').config()
var child_process = require('child_process');
var fs = require('fs');
var express = require('express');
var bodyParser = require('body-parser');
var app = express();
app.use(bodyParser.json());
app.use(bodyParser.urlencoded({ extended: true }));
var Stopwatch = require('timer-stopwatch');
var exec = child_process.exec;
var options = {
  timeout: 10000,
  killSignal: 'SIGKILL'
}

app.get('/', function (req, res) {
  res.send('Build Server')
})

app.post('/', function (req, res) {
    console.log(req.body.code);
	fs.writeFile("Practice.java", req.body.code, function(err) {
		if(err) {
			return console.log(err);
		}
		exec('javac Practice.java', options, function(err,stdout,stderr) {
  			if (err) {
                console.log('Child process exited with error code', err.code);
    			return
  			}
            var timer = new Stopwatch();
            timer.start();
            exec('java Practice', options, function(err,stdout,stderr) {
                timer.stop();
                console.log("took " + timer.ms + "ms");
                var expected = fs.readFileSync('expected').toString();
                if(stdout === expected) {
                    res.send("ok");
                } else {
                // console.log(stdout);
                // console.log(expected);
                res.send(stdout);
                }
            });
        });
    }); 
})

app.listen(3000, function () {
  console.log('Example app listening on port 3000!')
})