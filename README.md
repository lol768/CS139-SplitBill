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

DCS Plagiarism Policy
---------------------

Midway through the final course year within the Department of Computer Science, the handbook was updated with the following policy (~6 months after it was electronically signed):

> It is becoming increasingly common for students to use repositories (such as GitHub and GitLab) to store and manage their coursework. If you do this, you must make sure that your repositories are marked as "private" (and remain so, even after you have left the University), since by default they may be public and may be seen by other students. If you make your coursework public, and it is viewed or copied by other students, you may be investigated for abetting plagiarism (just as if you had deliberately handed your work to another student to copy).

> If you need to make a "portfolio" visible to potential employers, then the above still holds - either give the employer individual access (if the repository allows it), or make sure no source code is included.

To the best of my knowledge, no intellectual property in this repository is owned by the Department of Computer Science. It is unfortunate that an attempt was made to introduce such an all-encompassing policy without consultation.

In the case of this repository, a 'common sense' approach has been taken when open sourcing it in spite of this policy: the risk of plagiarism seems reasonably low because the module has not run since the academic year 17/18 and it would be reasonably straight-forward to modify the coursework task.
