<p>To login, please supply your email and password:</p>
<form class="vertical-form">
    <?php csrf_input(); ?>
    <label>
        E-mail: <input type="email" name="email">
    </label>
    <label>
        Password: <input type="password" name="password">
    </label>

    <input type="submit" class="button" value="Login">


</form>

<p>Alternatively, login with your <a href="startIts.php">ITS account</a>.</p>
