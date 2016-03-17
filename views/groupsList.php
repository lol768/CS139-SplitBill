<?php /** @var $myGroups array */
use SplitBill\Entity\Group; ?>
<?php require("partials/pageBegin.php"); ?>
<?php require("partials/nav.php"); ?>
<main class="groups">
    <div class="container">
        <h1>Groups <a class="button button-right modal-trigger" data-selector=".group-add-modal"><i class="fa fa-plus"></i> Create group</a></h1>
        <?php require("partials/errors.php"); ?>
        <p>
            Groups are used to manage collections of people subject to bills. From this page, you can create your
            own group and manage their details.
        </p>
        <h2>My groups</h2>
        <?php if (count($myGroups) == 0): ?>
            <p>There are no groups here. Why not <a href="#" class="modal-trigger" data-selector=".group-add-modal">make one</a>?</p>
        <?php endif; ?>
        <?php foreach ($myGroups as $groupEntry): ?>
            <?php
            $group = $groupEntry['group'];
            $relations = $groupEntry['relations'];
            $myRelation = $groupEntry['myRelation'];
            /** @var $relations \SplitBill\Entity\GroupRelationEntry[] */
            /** @var $myRelation \SplitBill\Entity\GroupRelationEntry */
            /** @var $group Group */ ?>
            <div class="groupBox">
                <div class="panels">
                    <div class="member-list">
                        <h1><i class="fa fa-users"></i> <?php se($group->getName()); ?> </h1>
                        <h3>Members</h3>
                        <ul>
                            <?php foreach ($relations as $relation): ?>
                                <li><?php se($relation->getUser()->getName()); ?> (<?php se($relation->getRelationType()); ?>) </li>
                            <?php endforeach; ?>
                        </ul>
                        <hr>
                        <?php foreach ($relations as $relation): ?>
                            <?php $avatarUrl = $relation->getUser()->getHasAvatar() ? "assets/avatars/" . $relation->getUser()->getUserId() . "_t.png" : "assets/avatars/unknown.svg"; ?>
                            <img class="avatar" src="<?php se($avatarUrl); ?>" title="<?php se($relation->getUser()->getName()); ?>">
                        <?php endforeach; ?>
                    </div>
                    <?php if ($myRelation->getRelationType() === "owner"): ?>
                        <div class="invites">
                            <h3>Invite a user</h3>
                            <form action="invite_user.php" method="POST" class="vertical-form">
                                <?php csrf_input(); ?>
                                <input type="hidden" name="groupId" value="<?php se($group->getGroupId()); ?>">
                                <?php require("partials/userSearchWidget.php"); ?>
                                <label>Role:
                                    <select name="role">
                                        <option value="member">Member</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                </label>
                                <input type="submit" value="Send invite" class="button">
                            </form>
                            <div class="group-add-modal-<?php se($group->getGroupId()); ?> modal">
                                <div class="modal-inner">
                                    <div class="topbar">
                                        <h1>Are you sure?</h1>
                                        <a href="#" class="exit"><i class="fa fa-times"></i></a>
                                    </div>
                                    <p>This action cannot be undone. Are you sure you want to delete this group?</p>
                                    <form action="delete_group.php" method="POST" class="vertical-form">
                                        <?php csrf_input(); ?>
                                        <input type="hidden" name="groupId" value="<?php se($group->getGroupId()); ?>">
                                        <input type="submit" value="Delete" class="button">
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="management">
                            <h3>Management</h3>
                            <a href="#" class="modal-trigger button " data-selector=".group-add-modal-<?php se($group->getGroupId()); ?>"><i class="fa fa-trash"></i> Delete group</a>
                            <h3>Status</h3>
                            <ul>
                                <li><?php se($group->getIsSecret() ? "Secret" : "Public"); ?></li>
                            </ul>
                        </div>
                    <?php else: ?>
                        <div class="permissions">
                            <h3>Permissions</h3>
                            <p>
                                Since you don't own this group, you can't manage it or add new members. Please
                                contact the owner if you'd like to change this group.
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>


    </div>
</main>

<?php require("partials/footer.php"); ?>
<div class="group-add-modal modal">
    <div class="modal-inner">
        <div class="topbar">
            <h1>Create a new group</h1>
            <a href="#" class="exit"><i class="fa fa-times"></i></a>
        </div>
        <?php require("partials/groupAddFormInner.php"); ?>
    </div>
</div>
<?php require("partials/scripts.php"); ?>
<?php require("partials/pageEnd.php"); ?>
