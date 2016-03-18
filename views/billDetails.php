<?php /** @var $payments array */ ?>
<div class="bill-modal modal">
    <div class="modal-inner">
        <div class="topbar">
            <h1>Bill details</h1>
            <a href="#" class="exit"><i class="fa fa-times"></i></a>
        </div>
        <strong>This bill was created on <?php se($bill->getCreatedAt()->format("r")); ?> and involves the following payments (total amount <?php print_integer_money($bill->getAmount()); ?>):</strong>
        <ul>
            <?php foreach ($payments as $payment): ?>
                <li><?php se($payment['user']->getName()); ?> for the amount of <?php print_integer_money($payment['payment']->getAmount()); ?>: <?php se($payment['payment']->isCompleted() ? "Paid" : "Owed"); ?></li>
            <?php endforeach; ?>
        </ul>

        <strong>The bill was for <?php se($bill->getDescription()); ?> and charged by <?php se($bill->getCompany()); ?>. The bill belongs to the '<?php se($group->getName()); ?>' group.</strong>


    </div>
</div>
