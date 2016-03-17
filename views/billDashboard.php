<?php /** @var $myGroups array */
/** @var $billableGroups Group[] */
/** @var $duePayments Payment[] */
use SplitBill\Entity\Group;
use SplitBill\Entity\Payment; ?>
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
            This page gives you an overview of any outstanding bills and can also be used to create new bills for a group.
        </p>
        <h2>Unpaid bills (total: <?php print_integer_money($totalDue); ?> due)</h2>
        <?php foreach ($duePayments as $payment): ?>
            <p>
                <?php print_integer_money($payment['payment']->getAmount()); ?> for <?php se($payment['bill']->getDescription()); ?>
                <?php se($payment['group']->getName()); ?>
            </p>
        <?php endforeach; ?>
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
                <input type="text" name="company" placeholder="British Gas" value="<?php old_input("company"); ?>" required>
            </label>

            <label>
                Description:
                <input type="text" name="description" placeholder="Heating" value="<?php old_input("description"); ?>" required>
            </label>

            <label>
                Amount:
                <input type="text" name="amount" placeholder="£5.00" value="<?php old_input("amount"); ?>" required>
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
