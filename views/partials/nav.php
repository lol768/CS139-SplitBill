<?php
// Partial view for navigation bar
/** @var $brand string */
?>
<nav>
    <div class="container">
        <span class="brand"><?php se($brand); ?></span>
        <ul class="left">
            <li class="active"><a href="#">Home</a></li>
            <li><a href="#">Groups</a></li>
            <li><a href="#">Bills</a></li>
            <li><a href="#">Settings</a></li>
        </ul>
        <ul class="right">
            <li class="with-dropdown notifications-dropdown">
                <a href="#" class="notifications-link"><i class="fa fa-inbox"></i></a>
                <div class="menu">
                    <ul class="alerts"></ul>
                </div>
            </li>
            <li class="with-dropdown profile-dropdown active">
                <a href="#"><img src="https://avatars2.githubusercontent.com/u/2552726?v=3&s=100" class="avatar"> Adam  <i class="fa fa-caret-down"></i></a>
                <div class="menu">
                    <ul>
                        <li><a href="#"><i class="fa fa-fw fa-pencil"></i> Edit profile</a></li>
                        <li><a href="#"><i class="fa fa-fw fa-sign-out"></i> Logout</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</nav>
