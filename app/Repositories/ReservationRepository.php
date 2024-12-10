<?php

namespace App\Repositories;

use App\Models\Reservation;
use Illuminate\Support\Carbon;

class ReservationRepository extends BaseRepository
{
    public function __construct(Reservation $modelo)
    {
        parent::__construct($modelo);
    }


    public function list($request = [], $with = [], $select = ['*'])
    {
        $data = $this->model->select($select)->with($with)->where(function ($query) use ($request) {});
        $data = $data->get();
        return $data;
    }

    public function store($request)
    {
        $request = $this->clearNull($request);

        if (! empty($request['id'])) {
            $data = $this->model->find($request['id']);
        } else {
            $data = $this->model::newModelInstance();
        }

        foreach ($request as $key => $value) {
            $data[$key] =  $request[$key];
        }
        $data->save();

        return $data;
    }

    public function searchOneExists($request, $with = [], $select = ['*'])
    {
        $reserved_at = Carbon::parse($request->reserved_at);
        $end_time = $reserved_at->copy()->addMinutes($request->duration);

        $data = $this->model->where(function ($query) use ($reserved_at, $end_time, $request) {
            $query->where(function ($subQuery) use ($reserved_at, $end_time, $request) {

                $subQuery->where('reserved_at', '<', $end_time)
                         ->whereRaw('datetime(reserved_at, "+" || duration || " minutes") > ?', [$reserved_at]);
            })
            ->where('resource_id', $request->resource_id);
        });

        $data = $data->first();

        return $data;
    }


}
