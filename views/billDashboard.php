<?php /** @var $myGroups array */
/** @var $billableGroups Group[] */
use SplitBill\Entity\Group; ?>
<?php require("partials/pageBegin.php"); ?>
<?php require("partials/nav.php"); ?>
<main class="bills">
    <div class="container">
        <h1>Bills
            <?php if (count($billableGroups) > 0): ?>
                <a class="button button-right modal-trigger" data-selector=".bill-add-modal">
                    <i class="fa fa-plus"></i> Create new bill
                </a>
            <?php endif; ?></h1>
        <?php require("partials/errors.php"); ?>
        <p>
            This page gives you an overview of any outstanding bills and can be used to create new bills for a group.
        </p>
        <h2>Unpaid bills</h2>

    </div>
</main>

<?php require("partials/footer.php"); ?>
<div class="bill-add-modal modal">
    <div class="modal-inner">
        <div class="topbar">
            <h1>Create a new bill</h1>
            <a href="#" class="exit"><i class="fa fa-times"></i></a>
        </div>
        <p>Bill amounts will be split equally between all members of the group.</p>
        <form class="vertical-form" method="POST" action="add_bill.php">
            <?php csrf_input(); ?>
            <label>
                Company:
                <input type="text" name="company" placeholder="British Gas">
            </label>

            <label>
                Description:
                <input type="text" name="description" placeholder="Heating">
            </label>

            <label>
                Amount:
                <input type="text" name="amount" placeholder="£5.00">
            </label>

            <label>
                Group:
                <select name="group_id">
                    <?php foreach ($billableGroups as $group): ?>
                        <option value="<?php se($group->getGroupId()); ?>"><?php se($group->getName()); ?></option>
                    <?php endforeach; ?>
                </select>
            </label>

            <input type="submit" class="button" value="Add bill">
        </form>

    </div>
</div>
<?php require("partials/scripts.php"); ?>
<?php require("partials/pageEnd.php"); ?>
