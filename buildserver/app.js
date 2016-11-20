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
    var method = req.body.proto.substr(0, req.body.proto.indexOf('('));
    testcases = testcases.split("#");
    testcases.forEach(function(test) {
        if(test.length != 0) {
            var split = test.split("^");
            var args = split[0].split(",");
            var innerargsString = "";
            args.forEach(function(innerarg) {
                innerargsString += innerarg +',';
            });
            innerargsString = innerargsString.slice(0, -1);
            if(req.body.return_type.includes("[]")) {
                main += "System.out.println(Arrays.toString("+method+"("+innerargsString+")));";
            } else {
                main += "System.out.println("+method+"("+innerargsString+"));";
            }
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
                console.log(stderr);
                console.log('Child process exited with error code', err.code);
    			res.send("*" + stderr);
                return;
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
                var response = "<b>Your output:</b><br/>" + stdout + "<br/><b>Expected:</b><br/>" + expected_out;
                res.send(response);
                }
            });
        });
    }); 
})

app.listen(3000, function () {
  console.log('Example app listening on port 3000!')
})