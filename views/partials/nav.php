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
                <li class="with-dropdown notifications-dropdown">
                    <a href="#" class="notifications-link"><i class="fa fa-inbox"></i></a>
                    <div class="menu">
                        <ul class="alerts"></ul>
                    </div>
                </li>
                <li class="with-dropdown profile-dropdown active">
                    <a href="#"><img src="https://avatars2.githubusercontent.com/u/2552726?v=3&s=100" class="avatar"> <?php se($user->getFirstName()); ?>  <i class="fa fa-caret-down"></i></a>
                    <div class="menu">
                        <ul>
                            <li><a href="#"><i class="fa fa-fw fa-pencil"></i> Edit profile</a></li>
                            <li><a href="#"><i class="fa fa-fw fa-sign-out"></i> Logout</a></li>
                        </ul>
                    </div>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
