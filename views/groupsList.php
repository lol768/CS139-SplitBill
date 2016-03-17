<?php /** @var $myGroups array */
use SplitBill\Entity\Group; ?>
<?php require("partials/pageBegin.php"); ?>
<?php $frontendModules[] = "UserSearchResults"; ?>
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
        <?php foreach ($myGroups as $groupEntry): ?>
            <?php
            $group = $groupEntry['group'];
            $relations = $groupEntry['relations'];
            $myRelation = $groupEntry['myRelation'];
            /** @var $relations \SplitBill\Entity\GroupRelationEntry[] */
            /** @var $myRelation \SplitBill\Entity\GroupRelationEntry */
            /** @var $group Group */ ?>
            <div class="groupBox">
                <h1><i class="fa fa-users"></i> <?php se($group->getName()); ?></h1>
                <ul>
                    <?php foreach ($relations as $relation): ?>
                        <li><?php se($relation->getUser()->getName()); ?> (<?php se($relation->getRelationType()); ?>) </li>
                    <?php endforeach; ?>
                </ul>
                <?php if ($myRelation->getRelationType() === "owner"): ?>
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
                <?php endif; ?>
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
