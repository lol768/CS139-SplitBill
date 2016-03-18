<?php require("partials/pageBegin.php"); ?>
<?php /** @var $brand string */ ?>
<?php $frontendModules[] = "HomepageAuthModals"; ?>
<?php require("partials/nav.php"); ?>
<main>
    <div class="container container-no-pad">
        <div class="hero">
            <h1>Bill management made easy</h1>
            <?php if ($user === null): ?>
                <p><a href="register.php" data-selector=".registration-modal" class="button modal-trigger"><i class="fa fa-pencil"></i> Register now</a> <a href="startIts.php" class="button"><img src="assets/warwick.svg" class="warwick-logo"> Login via ITS account</a></p>
            <?php else: ?>
                <p><a href="groups.php" class="button"><i class="fa fa-users"></i> View groups</a> <a href="bills.php" class="button"><i class="fa fa-money"></i> View bills</a></p>

            <?php endif; ?>
        </div>
        <div class="overview">
            <h1>How does it work?</h1>
            <p>
                <?php se($brand); ?> is a web application for managing shared bills. It's easy to get started with SplitBill, simply follow the steps below:
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
                    <img src="assets/simplearrow.svg" alt="Arrow pointing at next step">
                </div>
                <div class="step">
                    <h2><i class="fa fa-users"></i> Step 2 &mdash; Create group</h2>
                    <p>Create a group and invite other users to it. You can create as many groups as you like with
                    an unlimited number of members. Once members confirm, they'll be added to the group.</p>
                </div>
                <div class="arrow-wrapper">
                    <img src="assets/simplearrow.svg" alt="Arrow pointing at next step">
                </div>
                <div class="step">
                    <h2><i class="fa fa-money"></i> Step 3 &mdash; Manage bills</h2>
                    <p>
                        As you receive bills, create them and assign them to the group. Bills are split equally between
                        users in the group.
                        Members will receive a notification when bills are created.
                    </p>
                </div>
            </div>
            <h1>Features</h1><p></p>
            <h2><i class="fa fa-lock"></i> Secure</h2>
            <p>
                <?php se($brand); ?> has been designed from the start to be a secure web application. As far as is
                possible given outside constraints, I've tried to ensure the system is secure. Passwords are stored using
                BCrypt with a high cost-factor. Pages are served with restrictive <code>Content-Security-Policy</code>
                and <code>X-Frame-Options</code> headers to prevent cross-site scripting (XSS) and clickjacking attacks.
                Requests that modify state (e.g. POST requests) make use of a 64 byte unpredictable security token to
                prevent cross-site request forgery (CSRF) attacks. Cookies are set with the HttpOnly flag, Warwick
                OAuth tokens are never stored on the system and all dynamic data is passed through <code>htmlentities</code>
                before being output to the page.
            </p>

            <h2><i class="fa fa-wheelchair"></i> Accessible</h2>
            <p>
                All emails sent by the system are provided in both HTML and plaintext form (multipart mail). I've tried
                to make all pages make use of semantic markup elements (e.g. <code>&lt;nav&gt;</code>) and provide
                a "skip to main content" link on all pages accessible to users with a screen-reader. Images make use
                of the <code>alt</code> attribute where possible to provide a contextual explanation of the image.
                Colour blind individuals have been directly involved in the design and development of the site.
            </p>

            <h2><i class="fa fa-cogs"></i> Maintainable</h2>
            <p>
                The entire <?php se($brand); ?> application makes use of dependency injection to try and attempts to
                follow some of the SOLID object oriented design principles by depending on abstractions instead
                of concrete implementations. For example, all of the controllers in the application make use of
                interfaces to access data from the database. Behind the scenes, a simple inversion of control container
                implementation resolves these interfaces based on a set of bindings (which can be seen
                <a href="ioc_debug.php">here</a>) and instantiates the necessary concrete classes required for the
                classes to work.
            </p>

            <p>
                In this way, it's possible to switch implementations throughout the application from one configuration
                file. For example, if the SQLite was found to not scale appropriately a set of new interface implementations
                using MySQL could be developed and the entire application could be switched over to use them without
                having to change any code provided the interface contracts were met. Similarly, for testing, it's
                possible to swap in "dummy" or "stub" implementations of contracts (this was used to test the
                email service without resulting in sending a large number of emails).
            </p>
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
<div class="login-modal modal">
    <div class="modal-inner">
        <div class="topbar">
            <h1>Login</h1>
            <a href="#" class="exit"><i class="fa fa-times"></i></a>
        </div>
        <?php require("partials/loginInner.php"); ?>
    </div>
</div>
<?php require("partials/scripts.php"); ?>
<?php require("partials/pageEnd.php"); ?>

