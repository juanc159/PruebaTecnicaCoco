<?php

namespace App\Http\Controllers;

use App\Constants\MessagesAlert;
use App\Http\Controllers\Controller;
use App\Http\Requests\Reservation\ReservationStoreRequest;
use App\Repositories\ReservationRepository;
use App\Repositories\ResourceRepository;
use Illuminate\Support\Facades\DB;
use Throwable;

class ReservationController extends Controller
{
    public function __construct(
        protected ReservationRepository $reservationRepository,
        protected ResourceRepository $resourceRepository,
    ) {}

    public function store(ReservationStoreRequest $request)
    {
        try {
            DB::beginTransaction();

            $conflict = $this->reservationRepository->searchOneExists($request);

            if ($conflict) {
                return response()->json(['error' => MessagesAlert::ERROR_MESSAGE_001], 400);
            }

            $reservation = $this->reservationRepository->store([
                'resource_id' => $request->resource_id,
                'reserved_at' => $request->reserved_at,
                'duration' => $request->duration,
                'status' => 'pending',
            ]);

            DB::commit();
            return response()->json(["reservation" => $reservation], 201);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json([
                'code' => 500,
                'mess' => $th->getMessage(),
                'message' => MessagesAlert::API_ERROR
            ]);
        }
    }

    public function cancelledReservation($id)
    {
        try {
            DB::beginTransaction();
            $reservation = $this->reservationRepository->findOrFail($id);
            if ($reservation) {
                $reservation->status = 'cancelled';
                $reservation->save();

                $msg = MessagesAlert::CACELLED_MESSAGE_001;
            } else {
                $msg = 'El registro no existe';
            }
            DB::commit();

            return response()->json(['code' => 200, 'message' => $msg]);
        } catch (Throwable $th) {
            DB::rollBack();

            return response()->json([
                'code' => 500,
                'message' => MessagesAlert::API_ERROR,
            ], 500);
        }
    }
}
