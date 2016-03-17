<?php
/** @var $confirmationDestination string|null */
use SplitBill\Entity\User;

/** @var $user User */
?>
<?php require("partials/pageBegin.php"); ?>
<?php require("partials/nav.php"); ?>
<main>
    <div class="container profile">
        <h1>Edit profile</h1>
        <?php require("partials/errors.php"); ?>
        <p>On this page you can edit details relating to your SplitBill user profile.</p>

        <h2>Basic details</h2>
        <div class="groupBox">
            <form class="vertical-form" method="POST" action="edit_profile.php">
                <?php csrf_input(); ?>
                <label>
                    Full name: <input type="text" name="name" value="<?php se($user->getName()); ?>">
                </label>
                <label>
                    Email: <input type="email" name="email" value="<?php se($user->getEmail()); ?>">
                </label>
                <input type="submit" class="button" value="Save">

            </form>
        </div>

        <h2>Change password</h2>
        <?php if ($user->getItsUsername() !== null): ?>
            <p>
                This account is associated with an ITS account (<?php se($user->getItsUsername()); ?>) and cannot have a password set. Please login via ITS.
            </p>
        <?php else: ?>
            <div class="groupBox">
                <form class="vertical-form" method="POST" action="update_password.php">
                    <?php csrf_input(); ?>
                    <label>
                        Current password: <input type="password" name="current_password">
                    </label>
                    <label>
                        New password: <input type="password" name="new_password">
                    </label>
                    <label>
                        New password (confirm): <input type="password" name="new_password_confirm">
                    </label>
                    <input type="submit" class="button" value="Save">
                </form>
            </div>
        <?php endif; ?>

        <h2>Upload avatar</h2>
        <div class="groupBox">
            <?php if ($user->getHasAvatar()): ?>
                <img src="assets/avatars/<?php se($user->getUserId()); ?>.png" alt="User's current avatar" class="current-avatar">
            <?php endif; ?>
            <form class="vertical-form" method="POST" action="upload_avatar.php" enctype="multipart/form-data">
                <?php csrf_input(); ?>
                <label>
                    Image: <input type="file" name="avatar" accept="image/png,image/jpg,image/jpeg">
                </label>
                <span class="explanation">Images must be at least 200x200 and use either JPEG or PNG format.</span>
                <input type="submit" class="button" value="Upload">

            </form>
        </div>
    </div>
</main>

<?php require("partials/footer.php"); ?>
<?php require("partials/scripts.php"); ?>
<?php require("partials/pageEnd.php"); ?>

