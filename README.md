# WildCode
This application was built at WildHacks Fall 2016. The goal of this application is to allow users to create and share programming problems with their peers.

## Setup
To install WildCode locally, you need a LAMP stack, Composer, NodeJS, and NPM.
1
An easy to use web interface allows students to use a web editor to write Java code, and then compile programs from the web!

This project has two parts, the web application and the "build server." The web application provides all of the usual website components, such as user authentication, sessions, and challenge CRUD.
The build server then receives code submitted by users, compiles the code, runs it in a protected environment (under work), and finally diffs it with the testcases. This is all done in the background, and the end user just sees a "Challenge solved" message or "Test cases failed" message, followed by the failed tests.
This project is not mature yet, and still lacks some basic features and flexibility.

WildCode is built with Laravel and NodeJS.
