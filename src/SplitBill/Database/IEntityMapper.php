<?php

namespace SplitBill\Database;

use SplitBill\Entity\Group;
use SplitBill\Entity\GroupRelationEntry;
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
     * @return GroupRelationEntry
     */
    public function mapGroupRelationEntryFromArray(array $data);

}