<div class="masquerade-modal modal">
    <div class="modal-inner">
        <div class="topbar">
            <h1>Masquerade</h1>
            <a href="#" class="exit"><i class="fa fa-times"></i></a>
        </div>
        <p>If you are authorised, you can masquerade as another user for testing purposes:</p>
        <form action="masquerade.php" method="POST" class="vertical-form">
            <?php csrf_input(); ?>
            <label>
                UID:
                <?php require("userSearchWidget.php"); ?>
            </label>
            <input type="submit" class="button" value="Masquerade">
        </form>
    </div>
</div>
<script type="application/json" id="appState"><?php echo json_encode(array("modules" => $frontendModules)); ?></script>
<script type="application/json" id="profiling"><?php echo json_encode(array("queries" => $queriesLogged, "time" => $timeDiff)); ?></script>
</body>
</html>
