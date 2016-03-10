<p>To login, please supply your email and password:</p>
<form class="vertical-form">
    <?php csrf_input(); ?>
    <label>
        E-mail: <input type="text">
    </label>
    <label>
        Password: <input type="password">
    </label>

    <input type="submit" class="button" value="Login">


</form>

<p>Alternatively, login with your <a href="startIts.php">ITS account</a>.</p>
