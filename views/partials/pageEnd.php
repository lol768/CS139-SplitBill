<div class="masquerade-modal modal">
    <div class="modal-inner">
        <div class="topbar">
            <h1>Masquerade</h1>
        </div>
        <p>If you are authorised, you can masquerade as another user for testing purposes:</p>
        <form action="masquerade.php" method="POST" class="vertical-form">
            <?php csrf_input(); ?>
            <label>
                UID:
                <input type="text" name="uid">
            </label>
            <input type="submit" class="button" value="Masquerade">
        </form>
    </div>
</div>
<script type="application/json" id="appState"><?php echo json_encode(array("modules" => $frontendModules)); ?></script>
</body>
</html>
