<?php

namespace SplitBill\Database;

use SplitBill\Entity\Bill;
use SplitBill\Entity\Group;
use SplitBill\Entity\GroupRelationEntry;
use SplitBill\Entity\Payment;
use SplitBill\Entity\User;

interface IEntityMapper {

    /**
     * @param array $data
     * @return User
     */
    public function mapUserFromArray(array $data);

    /**
     * @param array $data
     * @return Group
     */
    public function mapGroupFromArray(array $data);

    /**
     * @param array $data
     * @return Bill
     */
    public function mapBillFromArray(array $data);

    /**
     * @param array $data
     * @return Payment
     */
    public function mapPaymentFromArray(array $data);

    /**
     * @param array $data
     * @return GroupRelationEntry
     */
    public function mapGroupRelationEntryFromArray(array $data);

}
