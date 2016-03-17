<?php
// Partial view for navigation bar
/** @var $brand string */
/** @var $leftNav \SplitBill\Frontend\NavigationItem[] */
/** @var $rightNav \SplitBill\Frontend\NavigationItem[] */
/** @var $user \SplitBill\Entity\User */
?>
<nav>
    <div class="container">
        <span class="brand"><a href="index.php"><?php se($brand); ?></a></span>
        <ul class="left">
            <?php foreach ($leftNav as $item): ?>
                <li class="<?php se($item->getClass()); ?>"><a href="<?php se($item->getLink()); ?>"><?php se($item->getText()); ?></a></li>
            <?php endforeach; ?>
        </ul>
        <ul class="right">
            <?php if ($user === null): ?>
                <?php foreach ($rightNav as $item): ?>
                    <li class="<?php se($item->getClass()); ?>"><a href="<?php se($item->getLink()); ?>"><?php se($item->getText()); ?></a></li>
                <?php endforeach; ?>
            <?php else: ?>
                <?php $avatarUrl = $user->getHasAvatar() ? "assets/avatars/{$user->getUserId()}_t.png" : "assets/avatars/unknown.svg"; ?>
                <li class="with-dropdown notifications-dropdown">
                    <a href="#" class="notifications-link"><i class="fa fa-inbox"></i></a>
                    <div class="menu">
                        <span class="no-alerts">You have no alerts.</span>
                        <ul class="alerts"></ul>
                    </div>
                </li>
                <li class="with-dropdown profile-dropdown">
                    <a href="#"><img src="<?php se($avatarUrl); ?>" class="avatar"> <?php se($user->getFirstName()); ?>  <i class="fa fa-caret-down"></i></a>
                    <div class="menu">
                        <ul>
                            <li><a href="edit_profile.php"><i class="fa fa-fw fa-pencil"></i> Edit profile</a></li>
                            <li><a href="logout.php?csrf=<?php se($csrfToken); ?>"><i class="fa fa-fw fa-sign-out"></i> Logout</a></li>
                        </ul>
                    </div>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
