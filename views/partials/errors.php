<?php if (isset($errors)): ?>
    <div class="alert alert-error">
        <ul>
            <?php foreach($errors as $error): ?>
                <li><?php se($error); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
