<?php


namespace App\Services;


use App\Models\QR;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use LaravelQRCode\Facades\QRCode;


class QRCodeService
{
    public function list(array $parameters = null): JsonResponse
    {
        try {
            if ($parameters != null) {
                foreach ($parameters as $key => $parameter) {
                    if (is_null($parameter)) {
                        unset($parameters[$key]);
                    }
                }
                $qrCode = QR::where($parameters)->where('user_id', auth('api')->user()->id)->get();
                return send_response('', $qrCode, 200);
            } else {
                $qrCode = QR::where('user_id', auth('api')->user()->id)->get();
                return send_response('', $qrCode, 200);
            }
        } catch (\Exception $exception) {
            return send_error($exception->getMessage(), '', $exception->getCode());
        }
    }

    public function store($content, $type): JsonResponse
    {
        $imageName = strtotime('now') . auth('api')->user()->name . '.png';
        $response = null;
        try {
            $qr = QR::create([
                'title' => $content['title'],
                'subtitle' => $content['subtitle'],
                'type' => $type,
                'user_id' => auth('api')->user()->id,
                'content' => public_path('img/QRCodes/') . $imageName
            ]);
            switch ($type) {
                case 'text':
                    $image = QRCode::text($content['content'])->setOutfile(public_path('img/QRCodes/') . $imageName)->png();
                    $response = send_response('QRCode criado com sucesso!', $qr, 201);
                    break;
                case 'email':
                    $image = QRCode::email($content['content'][0], $content['content'][1], $content['content'][2])->setOutfile(public_path('img/QRCodes/') . $imageName)->png();
                    $response = send_response('QRCode criado com sucesso!', $qr, 201);
                    break;
                case 'url':
                    $image = QRCode::url($content['content'])->setOutfile(public_path('img/QRCodes/') . $imageName)->png();
                    $response = send_response('QRCode criado com sucesso!', $qr, 201);
                    break;
                case 'phone':
                    $image = QRCode::phone($content['content'])->setOutfile(public_path('img/QRCodes/') . $imageName)->png();
                    $response = send_response('QRCode criado com sucesso!', $qr, 201);
                    break;
                case 'sms':
                    $image = QRCode::sms($content['content'][0], $content['content'][1])->setOutfile(public_path('img/QRCodes/') . $imageName)->png();
                    $response = send_response('QRCode criado com sucesso!', $qr, 201);
                    break;
                case 'wifi':
                    $image = QRCode::wifi($content['content'][0], $content['content'][1], $content['content'][2], $content['content'][3])->setOutfile(public_path('img/QRCodes/') . $imageName)->png();
                    $response = send_response('QRCode criado com sucesso!', $qr, 201);
                    break;
                case 'calendar':
                    $image = QRCode::calendar(
                        new \DateTime($content['content'][0]), new \DateTime($content['content'][1]), $content['content'][2],
                        $content['content'][3], $content['content'][4]
                    )->setOutfile(public_path('img/QRCodes/') . $imageName)->png();
                    $response = send_response('QRCode criado com sucesso!', $qr, 201);
                    break;
                case 'contato':
                    $image = QRCode::meCard($content['content'][0], $content['content'][1], $content['content'][2], $content['content'][3])->setOutfile(public_path('img/QRCodes/') . $imageName)->png();
                    $response = send_response('QRCode criado com sucesso!', $qr, 201);
                    break;
                default:
                    $response = send_error('Erro ao selecionar o tipo de QRCode!', '', 404);
            }
        } catch (\Exception $exception) {
            $response = send_error('Erro ao gerar o QRCode!', $exception->getMessage(), 404);
        }
        return $response;
    }

    public function show(int $id): JsonResponse
    {
        try {
            return send_response('', QR::findOrFail($id), 200);
        } catch (\Exception $exception) {
            return send_error('Não foi possível encontrar este QRCode!', $exception->getMessage(), 404);
        }
    }

    public function update($content, $id): JsonResponse
    {
        $qr = QR::findOrFail($id);
        $files = File::glob(public_path('img/QRCodes/*.*'));
        $fil = null;
        foreach ($files as $file){
            if($file == $qr->content){
                $fil = $file;
                $del = File::delete($qr->content);
            }else{
            }
        }
        $response = "";
        if (isset($qr)) {
            try {
                $response = send_response('QRCode atualizado com sucesso!', $qr, 201);
                switch ($content['type']) {
                    case 'text':
                        $image = QRCode::text($content['content'])->setOutfile($fil)->png();
                        break;
                    case 'email':
                        $image = QRCode::email($content['content'][0], $content['content'][1], $content['content'][2])->setOutfile($fil)->png();
                        break;
                    case 'url':
                        $image = QRCode::url($content['content'])->setOutfile($fil)->png();
                        break;
                    case 'phone':
                        $image = QRCode::phone($content['content'])->setOutfile($fil)->png();
                        break;
                    case 'sms':
                        $image = QRCode::sms($content['content'][0], $content['content'][1])->setOutfile($fil)->png();
                        break;
                    case 'wifi':
                        $image = QRCode::wifi($content['content'][0], $content['content'][1], $content['content'][2], $content['content'][3])->setOutfile($fil)->png();
                        break;
                    case 'calendar':
                        $image = QRCode::calendar(
                            new \DateTime($content['content'][0]), new \DateTime($content['content'][1]), $content['content'][2],
                            $content['content'][3], $content['content'][4]
                        )->setOutfile($fil)->png();
                        break;
                    case 'contato':
                        $image = QRCode::meCard($content['content'][0], $content['content'][1], $content['content'][2], $content['content'][3])->setOutfile($fil)->png();
                        break;
                    default:
                        $response = send_error('Erro ao selecionar o tipo de QRCode!', '', 404);
                }
                $qr->update([
                    'title' => $content['title'],
                    'subtitle' => $content['subtitle'],
                    'type' => $content['type'],
                    'content' => $fil
                ]);
                return $response;
            } catch (\Exception $exception) {
                return send_error('Não foi possível atualizar este QRCode!', $exception->getMessage(), 406);
            }
        } else {
            return send_error('Erro ao atualizar o QRCode', '', 422);
        }

    }

    public function delete(int $id): JsonResponse
    {
        try {
            $qr = QR::find($id);
            $files = File::glob(public_path('img/QRCodes/*.*'));
            $fil = null;
            foreach ($files as $file){
                if($file == $qr->content){
                    $fil = $file;
                    File::delete($qr->content);
                }
            }
            abort_if(!$qr, 404, 'Não foi possível encontrar este QRCode');
            $qr->delete();
            return send_response('QRCode excluído com sucesso', null, 200);
        } catch (\Exception $exception) {
            return send_error('Não foi possível encontrar este QRCode!', $exception->getMessage(), 404);
        }
    }

    public function listCount($initial, $final)
    {
        return QR::where('user_id',auth()->user()->id)
            ->whereBetween('created_at',[$initial , $final])
            ->get();
    }
}
