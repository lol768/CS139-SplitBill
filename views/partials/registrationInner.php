<p>To register, simply fill out the fields below:</p>
<form class="vertical-form">
    <?php csrf_input(); ?>
    <label>
        Full name: <input type="text">
    </label>
    <label>
        E-mail: <input type="text">
    </label>
    <label>
        Password: <input type="password">
    </label>

    <input type="submit" class="button" value="Register">
</form>
