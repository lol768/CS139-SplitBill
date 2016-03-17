<?php


namespace SplitBill\Entity;


class GroupRelationEntry {

    private $relationId;
    private $relationType;
    private $user;

    /**
     * GroupRelationEntry constructor.
     * @param $relationId
     * @param $relationType
     * @param $user
     */
    public function __construct($relationId, $relationType, $user) {
        $this->relationType = $relationType;
        $this->user = $user;
        $this->relationId = $relationId;
    }

    /**
     * @return mixed
     */
    public function getRelationId() {
        return $this->relationId;
    }

    /**
     * @param mixed $relationId
     */
    public function setRelationId($relationId) {
        $this->relationId = $relationId;
    }

    /**
     * @return mixed
     */
    public function getRelationType() {
        return $this->relationType;
    }

    /**
     * @param mixed $relationType
     */
    public function setRelationType($relationType) {
        $this->relationType = $relationType;
    }

    /**
     * @return User
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @param User $user
     */
    public function setUser($user) {
        $this->user = $user;
    }
}
