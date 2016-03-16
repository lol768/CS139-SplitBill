<?php
/** @var $confirmationDestination string|null */
?>
<?php require("partials/pageBegin.php"); ?>
<?php require("partials/nav.php"); ?>
<main>
    <div class="container">
        <h1>You're almost done</h1>
        <?php if ($confirmationDestination !== null): ?>
            <p>We've sent an confirmation email to <strong><?php se($confirmationDestination); ?> (<a href="resend_email_confirmation.php?email=<?php se($confirmationDestination); ?>">resend</a>)</strong>.</p>
        <?php else: ?>
            <p>We've sent an confirmation email to the address you supplied during registration.</p>
        <?php endif; ?>

        <p>Please check your email and click the provided link to complete the sign-up process.</p>
    </div>
</main>

<?php require("partials/footer.php"); ?>
<?php require("partials/scripts.php"); ?>
<?php require("partials/pageEnd.php"); ?>

