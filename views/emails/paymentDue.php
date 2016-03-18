<?php /** @var $name string */ /** @var $billsUrl string */ ?>
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
        A new bill has been added to a group which you belong to. You need to pay your share of the bill. Please use
        the link below to access your Bills dashboard.
    </p>

    <table cellspacing="10" style="background-color: #e7325c; border-radius: 5px;"><tr><td><a href="<?php se($billsUrl); ?>" style="display:inline-block;color:#fff;text-decoration:none;">View bills</a></td></tr></table>

    <p>
        Thanks!
    </p>
</body>
</html>
