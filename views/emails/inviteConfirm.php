<?php /** @var $name string */ /** @var $confirmationUrl string */ /** @var $role string */ /** @var $group string */ ?>
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
        You've been sent an invite to the '<?php se($group); ?>' group for the '<?php se($role); ?>' role! You can accept it below:
    </p>

    <table cellspacing="10" style="background-color: #e7325c; border-radius: 5px;"><tr><td><a href="<?php se($confirmationUrl); ?>" style="display:inline-block;color:#fff;text-decoration:none;">Accept</a></td></tr></table>

    <p>
        Alternatively you can manually copy and paste the URL below into your browser:
    </p>

    <pre><?php se($confirmationUrl); ?></pre>

    <p>
        If you aren't interested in joining this group, please ignore the invite.
    </p>

    <p>
        Thanks!
    </p>
</body>
</html>
