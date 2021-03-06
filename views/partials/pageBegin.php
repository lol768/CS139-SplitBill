<?php /** @var $brand string */
use SplitBill\Entity\User;

/** @var $user User */ /** @var $realUser User */ ?>
<?php $frontendModules = array("AlertManager", "WebSockets", "Modals", "FlashMessages", "JQueryCustomisations", "Dropdowns", "UserSearchResults"); ?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php se(((isset($title)) ? $title . " - " : "") . $brand); ?></title>
    <link href="app.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#e7325c" />
    <?php csrf_meta(); ?>
    <?php require(__DIR__ . "/../jsTemplates/ajaxError.php"); ?>
    <?php require(__DIR__ . "/../jsTemplates/userSearchResult.php"); ?>
</head>
<body>
<a href="#content" id="skip-to-content">Skip to main content</a>
<noscript>
    <div class="flash flash-error"><p>It appears you have JavaScript disabled. This site requires JavaScript to operate.</p></div>
</noscript>
<?php if (isset($flashMessage)): ?> <?php /** @var $flashType string */ ?>
    <div class="flash flash-<?php se($flashType); ?>"><a href="#" class="close"><i class="fa fa-times"></i></a><p><?php se($flashMessage); ?></p></div>
<?php endif; ?>
<?php if ($user !== null && $realUser !== $user): ?>
    <div class="flash flash-system"><p><i class="fa fa-gears"></i> Currently masquerading as <?php se($user->getName()); ?> (<a href="masquerade.php?uid=<?php se($realUser->getUserId()); ?>">quit</a>)</p></div>
<?php endif; ?>
