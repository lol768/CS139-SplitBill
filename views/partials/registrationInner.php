<p>To register, simply fill out the fields below:</p>
<?php require(__DIR__ . "/errors.php"); ?>
<form class="vertical-form" method="POST" action="register.php">
    <?php csrf_input(); ?>
    <label>
        Full name: <input type="text" name="name" value="<?php old_input("name"); ?>">
    </label>
    <label>
        E-mail: <input type="email" name="email" value="<?php old_input("email"); ?>" required>
    </label>
    <label>
        Password: <input type="password" name="password" required>
    </label>

    <input type="submit" class="button" value="Register">
</form>
