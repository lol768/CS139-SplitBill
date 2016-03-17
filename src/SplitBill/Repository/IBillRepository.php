<?php

namespace SplitBill\Repository;

use SplitBill\Entity\Bill;

interface IBillRepository {
    /**
     * @param Bill $bill
     * @return Bill
     */
    public function add(Bill $bill);

    /**
     * @param Bill $bill
     * @return Bill
     */
    public function update(Bill $bill);
}
