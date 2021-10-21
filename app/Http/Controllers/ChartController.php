<?php

namespace App\Http\Controllers;

use App\Services\QRCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ChartController extends Controller
{
    private QRCodeService $qrCodeService;

    /**
     * ChartController constructor.
     * @param QRCodeService $qrCodeService ;
     */
    public function __construct(QRCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }


    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'initial' => 'date',
            'final' => 'date',
        ]);
        if ($validator->fails()) {
            return send_error('Selecione um tipo de data', $validator->errors(), 422);
        } else if ($request->final < $request->initial) {
            return send_error('Selecione uma data maior ou a ' . $request->initial, '', 422);
        } else {
            return $this->qrCodeService->listCount($request->initial, $request->final);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
