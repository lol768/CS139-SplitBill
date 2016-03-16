<?php /** @var $myGroups Group[] */
use SplitBill\Entity\Group; ?>
<?php require("partials/pageBegin.php"); ?>
<?php require("partials/nav.php"); ?>
<main>
    <div class="container">
        <h1>Groups <a class="button button-right modal-trigger" data-selector=".group-add-modal"><i class="fa fa-plus"></i> Create group</a></h1>
        <?php foreach ($myGroups as $group): ?>
            <div class="groupBox">
                <h1><?php se($group->getName()); ?></h1>
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


