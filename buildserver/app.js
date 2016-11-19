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
    var main = "\npublic static void main(String args[]) {\n";
    var expected_out = "";
    var testcases = req.body.testcase;
    testcases = testcases.split("#");
    testcases.forEach(function(test) {
        if(test.length != 0) {
            var split = test.split("^");
            main += "System.out.println(reverse(\""+ split[0] +"\"));";
            expected_out += split[1] + "\n";
        }
    });
    var imports = "import java.util.*;\nimport java.io.*;\npublic class Practice {\n";
    main += "\n}\n}";
	fs.writeFile("Practice.java", imports + req.body.code + main, function(err) {
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
                console.log(stdout);
                console.log(expected_out);
                if(stdout === expected_out) {
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