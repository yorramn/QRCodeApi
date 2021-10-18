<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\QRCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use LaravelQRCode\Facades\QRCode;

class QRCodeController extends Controller
{
    private QRCodeService $qrCodeService;

    /**
     * QRCodeController constructor.
     * @param QRCodeService $qrCodeService
     */
    public function __construct(QRCodeService $qrCodeService)
    {
        $this->qrCodeService = $qrCodeService;
    }

    public function index(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'type' => 'string',
        ]);
        if ($validator->fails()) {
            return send_error('Erro ao listar QRCodes', $validator->errors(), 422);
        } else {
            $request->type != null ? $parameters = ['type' => $request->type] : $parameters = null;
            return $this->qrCodeService->list($parameters);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     * @return JSonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|max:100',
            'subtitle' => 'string|max:100',
            'type' => 'required|string',
            'contents' => 'required'
        ]);
        if ($validator->fails()) {
            return send_error('Erro ao validar o cadastro do QR', $validator->errors(), 422);
        } else {
            $params = [
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'type' => $request->type,
                'content' => $request->contents
            ];
            try {
                return $this->qrCodeService->store($params, $params['type']);
            } catch (\Exception $exception) {
                return send_error('Erro ao cadastrar o QR', $exception->getMessage(), 422);
            }
        }
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
