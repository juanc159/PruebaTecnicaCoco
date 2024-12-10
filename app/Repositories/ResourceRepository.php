<?php

namespace App\Repositories;

use App\Models\Resource;

class ResourceRepository extends BaseRepository
{
    public function __construct(Resource $modelo)
    {
        parent::__construct($modelo);
    }


    public function list($request = [], $with = [], $select = ['*'])
    {
        $data = $this->model->select($select)->with($with)->where(function ($query) use ($request) {});
        $data = $data->get();
        return $data;
    }
}
