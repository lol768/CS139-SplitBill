<?php if (isset($errors)): ?>
    <div class="alert alert-error">
        <p>Sorry, the following errors were encountered:</p>
        <i class="fa fa-warning"></i>
        <ul>
            <?php foreach($errors as $error): ?>
                <li><?php se($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
