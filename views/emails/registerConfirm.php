<?php /** @var $name string */ /** @var $confirmationUrl string */ ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
</head>
<body>
    <p>
        Hi <?php se($name); ?>!
    </p>

    <p>
        We've received a request to register (hopefully from you!) for a SplitBill account. Please click the link below
        to finish the registration process.
    </p>

    <table cellspacing="10" style="background-color: #e7325c; border-radius: 5px;"><tr><td><a href="<?php se($confirmationUrl); ?>" style="display:inline-block;color:#fff;text-decoration:none;">Confirm registration</a></td></tr></table>

    <p>
        Alternatively you can manually copy and paste the URL below into your browser:
    </p>

    <pre><?php se($confirmationUrl); ?></pre>

    <p>
        If you didn't register, you can just ignore this message.
    </p>

    <p>
        Thanks!
    </p>
</body>
</html>
