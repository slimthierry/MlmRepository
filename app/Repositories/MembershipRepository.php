<?php

namespace App\Repositories;

use App\Models\Membership;


class MembershipRepository extends BaseRepository
{
    protected $model;
    protected $parrain;
    protected $member;

    public function __construct() {
        $this->model = new Membership;

    }

    public function saveModel($model, $data) {
        foreach ($data as $m=>$n) {
            $model->{$m} = $n;
        }
        $model->save();
        return $model;
    }

    public function store($data) {
        $model = $this->saveModel(new $this->model, $data);
        return $model;
    }

    public function update($model, $data) {
        $model = $this->saveModel($model, $data);
        return $model;
    }

    public function findById ($id) {
        return $this->model->where('id', $id)->first();
    }
    /**
     * Find children (not direct)
     * @param  App\Models\Membership $member
     * @return object
     */
    public function findChildren ($id) {

        $results = Membership::where('parrain_id', '=', $id)->orderBy('id', 'asc')->get();

        return $results;
    }

    /**
     * Add member to network tree
     *
     * @param App\Models\Membership $member
     * @return boolean
     */
    public function addNetwork ($member) {
        $id = $member->id; // id to add
        $befores = '';
        $beforeChildren = '';


        return true;
    }

}
