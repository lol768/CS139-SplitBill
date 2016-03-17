<?php
/** @var array $bindings */
/** @var array $singletons */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>IoC Container</title>
    <link rel="stylesheet" href="app.css">
</head>
<body class="ioc"><div id="inner">
    <header>IoC Debug Page</header>
    <p>The following interface bindings are registered in the IoC container:</p>

    <table>
        <thead>
            <tr>
                <th>Abstract (interface)</th>
                <th></th>
                <th>Concrete (implementation)</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($bindings as $interface => $class): ?>
                <tr>
                    <td><?php se($interface); ?></td>
                    <td>&mdash; &gt;</td>
                    <td><?php se($class); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <p>The following singletons are registered in the IoC container:</p>
    <ul>
        <?php foreach($singletons as $item): ?>
            <li>\<?php se($item); ?></li>
        <?php endforeach; ?>
    </ul>
</div></body>
</html>
