Repo for CS139 coursework
=========================

Relevant links:

http://www2.warwick.ac.uk/fac/sci/dcs/teaching/material/cs139/coursework/
http://www2.warwick.ac.uk/fac/sci/dcs/teaching/material/cs139/coursework/coursework-16.pdf

Introduction
------------

This repository contains code submitted as coursework for the (now defunct) CS139 Web Development Technologies module at Warwick.

The application is a bill splitting system, called SplitBill. This was written in 2016 with some particularly restrictive constraints:

* Requirement to run under PHP version 5.3.3 (current version at time of coursework was 7, web server system was just outdated).
* Requirement to not use any server-side libraries.
* Limited set of front-end libraries permissable.

It was also not possible to use a `.htaccess` override. As a result, I wrote a simple MVC framework and IoC container for the project.
