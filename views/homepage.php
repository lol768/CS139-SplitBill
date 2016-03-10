<?php require("partials/pageBegin.php"); ?>
<?php $frontendModules[] = "HomepageAuthModals"; ?>
<?php require("partials/nav.php"); ?>
<main>
    <div class="container container-no-pad">
        <div class="hero">
            <h1>Bill management made easy</h1>
            <p><a href="#" class="button"><i class="fa fa-pencil"></i> Register now</a> <a href="startIts.php" class="button"><img src="assets/warwick.svg" class="warwick-logo"> Login via ITS account</a></p>
        </div>
        <div class="overview">
            <h1>How does it work?</h1>
            <p>
                SplitBill is a web application for managing shared bills. It's easy to get started with SplitBill, simply follow the steps below:
            </p>

            <div class="steps-overview">
                <div class="step">
                    <h2><i class="fa fa-pencil"></i> Step 1 &mdash; Register</h2>
                    <p>
                        Register with an email address and password to get started. Alternatively you can save time by
                        logging in with your Warwick IT Services account which will pre-fill your account info.
                    </p>
                </div>
                <div class="arrow-wrapper">
                    <img src="assets/simplearrow.svg">
                </div>
                <div class="step">
                    <h2><i class="fa fa-users"></i> Step 2 &mdash; Create group</h2>
                    <p>Create a group and invite other users to it. You can create as many groups as you like with
                    an unlimited number of members. Once members confirm, they'll be added to the group.</p>
                </div>
                <div class="arrow-wrapper">
                    <img src="assets/simplearrow.svg">
                </div>
                <div class="step">
                    <h2><i class="fa fa-money"></i> Step 3 &mdash; Manage bills</h2>
                    <p>
                        As you receive bills, create them and assign them to the group. Bills can be split equally or
                        in a custom ratio. Members will receive a notification when bills are created.
                    </p>
                </div>
            </div>
            <h1>Feature Tour</h1>
            <p>TODO</p>
        </div>
    </div>

</main>

<?php require("partials/footer.php"); ?>
<div class="registration-modal modal">
    <div class="modal-inner">
        <div class="topbar">
            <h1>Register</h1>
            <a href="#" class="exit"><i class="fa fa-times"></i></a>
        </div>
        <?php require("partials/registrationInner.php"); ?>
    </div>
</div>
<?php require("partials/scripts.php"); ?>
<?php require("partials/pageEnd.php"); ?>

