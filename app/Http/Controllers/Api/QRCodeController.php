<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\QR;
use App\Services\QRCodeService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            'title' => 'string',
            'subtitle' => 'string',
            'type' => 'string',
            'created_at' => 'date',
            'updated_at' => 'date',
        ]);
        if ($validator->fails() || !in_array($request->type,['phone','text','email','url','sms','wifi','contato','calendar',null])) {
            return send_error('Erro ao listar QRCodes! Não há este tipo de QRCode cadastrado.', $validator->errors(), 422);
        } else {
            $request->all() != null  ? $parameters = [
                'type' => $request->type,
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'created_at' => $request->created_at,
                'updated_at' => $request->updated_at,
                ] : $parameters = null;
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
            $count = count(QR::where([
                ['user_id', auth('api')->user()->id],
                ['title', $request->title],
                ['subtitle', $request->subtitle],
            ])->get());
            if ($count >= 1) {
                return send_error('Já existe um QRCode com nome de ' . $request->title, '', 422);
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
                    return send_error('Erro ao cadastrar o QRCode', $exception->getMessage(), 422);
                }
            }
        }
    }


    public function show($id): JsonResponse
    {
        return $this->qrCodeService->show($id);
    }


    public function update(int $id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'max:100',
            'subtitle' => 'string|max:100',
            'type' => 'string',
            'contents' => 'required'
        ]);
        if ($validator->fails()) {
            return send_error('Erro ao validar a edição do QR', $validator->errors(), 422);
        } else {
            $params = [
                'title' => $request->title,
                'subtitle' => $request->subtitle,
                'type' => $request->type,
                'content' => $request->contents,
                'user_id' => auth('api')->user()->id
            ];
            try {
                return $this->qrCodeService->update($params,$id);
            } catch (\Exception $exception) {
                return send_error('Não foi possível encontrar este QRCode!', $exception->getMessage(), 404);
            }
        }
    }

    public function destroy($id): JsonResponse
    {
        return $this->qrCodeService->delete($id);
    }
}
