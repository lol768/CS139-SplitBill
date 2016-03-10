<?php if (isset($errors)): ?>
    <ul>
        <?php foreach($errors as $error): ?>
            <li><?php se($error); ?></li>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>
