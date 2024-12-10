<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct(User $modelo)
    {
        parent::__construct($modelo);
    }

    public function register($request)
    {
        $data = $this->model;

        foreach ($request as $key => $value) {
            $data[$key] =  $request[$key];
        }

        $data->save();

        return $data;
    }


}
