<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Validator;

class CurrencyController extends Controller
{
    protected $currencies = [
        'currencies' => [
            'TWD' => [
                'TWD' => 1,
                'JPY' => 3.669,
                'USD' => 0.03281,
            ],
            'JPY' => [
                'TWD' => 0.26956,
                'JPY' => 1,
                'USD' => 0.00885,
            ],
            'USD' => [
                'TWD' => 30.444,
                'JPY' => 111.801,
                'USD' => 1,
            ]
        ]
    ];

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        return JsonResource::collection($this->currencies);
    }

    /**
     * Display the specified resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function exchange(Request $request)
    {
        $validator = Validator::make(
            $request->only(['currency', 'payment', 'amount']),
            [
                'currency' => ['required', 'in:USD,TWD,JPY'],
                'payment' => ['required', 'in:USD,TWD,JPY'],
                'amount' => ['required', 'numeric', 'gt:0'],
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 400);
        }

        return response()->json([
            'data' => [
                'amount' => number_format(
                    $request->amount * $this->currencies['currencies'][$request->payment][$request->currency],
                    2,
                    '.',
                    ','
                )
            ]
        ]);
    }
}
