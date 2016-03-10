<?php $frontendModules = array("AlertManager", "WebSockets", "Modals", "FlashMessages"); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>SplitBill</title>
    <link href="app.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php csrf_meta(); ?>
</head>
<body>
<noscript>
    <div class="flash flash-error"><p>It appears you have JavaScript disabled. This site requires JavaScript to operate.</p></div>
</noscript>
