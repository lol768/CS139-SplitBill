<p>Each group must have a name. Groups can be either be open (anyone can join without an invitation) or closed
    (members must be invited by the group owner).</p><p>Groups can also either be secret (do not appear in the group listing) or
public (shown in the group listing).</p>
<?php require(__DIR__ . "/errors.php"); ?>
<form class="vertical-form" method="POST" action="add_group.php">
    <?php csrf_input(); ?>
    <label>
        Group name: <input type="text" name="name" value="<?php old_input("name"); ?>">
    </label>
    <label>
        Visibility: <select name="visibility"><option value="secret">Secret</option><option value="public">Public</option></select>
    </label>
    <label>
        Invitations: <select name="invitationMode"><option value="closed">Require invitation to join</option><option value="open">Allow anyone to join</option></select>
    </label>

    <input type="submit" class="button" value="Create group">
</form>

