Security
========

Written to a future maintainer:

CSRF
----

The system does not accept POST requests from clients unless they include a valid CSRF token. This means forms
won't work unless there is a CSRF token. To add in the CSRF token input, simply use the `csrf_input` helper
function provided:

```html
<form action="somePage.php" method="POST">
    <?php csrf_input(); ?>
    <input name="name" type="text">
    <!-- more inputs... -->
</form>
```

The jQuery modifications module will ensure that a `Bearer` header is automatically set for all AJAX requests
which will include the CSRF token. There should be no need to worry about this though, it should "just work".

XSS
---

Always use the `se` helper function (secure echo) when printing variables to the page. This wraps the
`htmlentities` function and prevents malicious user input from causing JS to execute on the client etc.

If you screw up and forget to use the helper function on user input and inadvertently introduce a (reflected or stored)
XSS vulnerability, the system uses a `Content-Security-Policy` header to mitigate the risk in most modern browsers.

Session theft
-------------

The system ensures that the PHP session cookie is set to be HttpOnly. This prevents malicious JavaScript executing on
the page (e.g. via an XSS vulnerability) from compromising the session token.

Unfortunately the CS139 server does not support TLS so it would be trivial to compromise the session cookie in this
manner. This is the primary reason why the ITS authentication is proxied through another (secure) system and only
read-only access is exposed to this project.

Click-jacking
-------------

The system will set the `X-Frame-Options` header to "DENY" to mitigate the possibility of a click-jacking attack
via an `<iframe>` element.


Session deserialisation/decryption attacks
------------------------------------------

Sessions are stored entirely server-side. A unique, hopefully unpredictable identifier is used to look up data on the
filesystem internally. This entirely mitigates these sorts of attacks.

Password storage
----------------

I use bcrypt for password storage via the `crypt` function. This is better than salted `sha1` for the following
reasons:

* Resistant to FPGA/GPU cracking implementations due to memory requirements
* Allows for a configurable cost factor and can become slower as hardware improves
* SHA-1 not considered secure by cryptographers, lots of research on collision resistance
