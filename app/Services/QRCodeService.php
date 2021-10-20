<?php


namespace App\Services;


use App\Models\QR;
use Illuminate\Support\Facades\Storage;
use LaravelQRCode\Facades\QRCode;
use Illuminate\Http\JsonResponse;

class QRCodeService
{
    public function list(array $parameters = null): JsonResponse
    {
        foreach ($parameters as $key => $parameter){
            if(is_null($parameter)){
                unset($parameters[$key]);
            }
        }
        try {
            $parameters != null ?
                $qrCode = QR::where($parameters)->where('user_id', auth('api')->user()->id)->get() :
                $qrCode = QR::where('user_id', auth('api')->user()->id)->get();
            return send_response('', $qrCode, 200);
        } catch (\Exception $exception) {
            return send_error($exception->getMessage(), '', $exception->getCode());
        }
    }

    public function store($content, $type): JsonResponse
    {
        $output_file = md5($content['title'] . auth('api')->user()->name) . strtotime('now') . '.png';
        $response = null;
        switch ($type) {
            case 'text':
                $image = QRCode::text($content['content'])->png();
                Storage::disk('local')->put($output_file, $image);
                $qr = QR::create([
                    'title' => $content['title'],
                    'subtitle' => $content['subtitle'],
                    'type' => $type,
                    'user_id' => auth('api')->user()->id,
                    'content' => $output_file
                ]);
                $response = send_response('QRCode criado com sucesso!', $qr, 201);
                break;
            case 'email':
                $image = QRCode::email($content['content'][0], $content['content'][1], $content['content'][2])->png();
                Storage::disk('local')->put($output_file, $image);
                $qr = QR::create([
                    'title' => $content['title'],
                    'subtitle' => $content['subtitle'],
                    'type' => $type,
                    'user_id' => auth('api')->user()->id,
                    'content' => $output_file
                ]);
                $response = send_response('QRCode criado com sucesso!', $qr, 201);
                break;
            case 'url':
                $image = QRCode::url($content['content'])->png();
                Storage::disk('local')->put($output_file, $image);
                $qr = QR::create([
                    'title' => $content['title'],
                    'subtitle' => $content['subtitle'],
                    'type' => $type,
                    'user_id' => auth('api')->user()->id,
                    'content' => $output_file
                ]);
                $response = send_response('QRCode criado com sucesso!', $qr, 201);
                break;
            case 'phone':
                $image = QRCode::phone($content['content'])->png();
                Storage::disk('local')->put($output_file, $image);
                $qr = QR::create([
                    'title' => $content['title'],
                    'subtitle' => $content['subtitle'],
                    'type' => $type,
                    'user_id' => auth('api')->user()->id,
                    'content' => $output_file
                ]);
                $response = send_response('QRCode criado com sucesso!', $qr, 201);
                break;
            case 'sms':
                $image = QRCode::sms($content['content'][0], $content['content'][1])->png();
                Storage::disk('local')->put($output_file, $image);
                $qr = QR::create([
                    'title' => $content['title'],
                    'subtitle' => $content['subtitle'],
                    'type' => $type,
                    'user_id' => auth('api')->user()->id,
                    'content' => $output_file
                ]);
                $response = send_response('QRCode criado com sucesso!', $qr, 201);
                break;
            case 'wifi':
                $image = QRCode::wifi($content['content'][0], $content['content'][1], $content['content'][2], $content['content'][3])->png();
                Storage::disk('local')->put($output_file, $image);
                $qr = QR::create([
                    'title' => $content['title'],
                    'subtitle' => $content['subtitle'],
                    'type' => $type,
                    'user_id' => auth('api')->user()->id,
                    'content' => $output_file
                ]);
                $response = send_response('QRCode criado com sucesso!', $qr, 201);
                break;
            case 'calendar':
                $image = QRCode::calendar(
                    new \DateTime($content['content'][0]), new \DateTime($content['content'][1]), $content['content'][2],
                    $content['content'][3], $content['content'][4]
                )->png();
                Storage::disk('local')->put($output_file, $image);
                $qr = QR::create([
                    'title' => $content['title'],
                    'subtitle' => $content['subtitle'],
                    'type' => $type,
                    'user_id' => auth('api')->user()->id,
                    'content' => $output_file
                ]);
                $response = send_response('QRCode criado com sucesso!', $qr, 201);
                break;
            case 'contato':
                $image = QRCode::meCard($content['content'][0], $content['content'][1], $content['content'][2], $content['content'][3])->png();
                Storage::disk('local')->put($output_file, $image);
                $qr = QR::create([
                    'title' => $content['title'],
                    'subtitle' => $content['subtitle'],
                    'type' => $type,
                    'user_id' => auth('api')->user()->id,
                    'content' => $output_file
                ]);
                $response = send_response('QRCode criado com sucesso!', $qr, 201);
                break;
            default:
                $response = send_error('Erro ao selecionar o tipo de QRCode!', '', 404);
        }
        return $response;
    }

    public function show(int $id): JsonResponse
    {
        try {
            $qr = QR::findOrFail($id);
            return send_response('', $qr, 200);
        } catch (\Exception $exception) {
            return send_error('Não foi possível encontrar este QRCode!', $exception->getMessage(), 404);
        }
    }

    public function update($content, $id): JsonResponse
    {
        $qr = QR::findOrFail($id);
        if(isset($qr)){
            try {
                $qr->update($content);
                return send_response('QRCode atualizado com sucesso', $qr, 200);
            } catch (\Exception $exception) {
                return send_error('Não foi possível atualizar este QRCode!', $exception->getMessage(), 406);
            }
        }else{
            return send_error('Erro ao atualizar o QRCode', '', 422);
        }

    }

    public function delete(int $id): JsonResponse
    {
        try {
            $qr = QR::find($id);
            abort_if(!$qr, 404, 'Não foi possível encontrar este QRCode');
            $qr->delete();
            return send_response('QRCode excluído com sucesso', null, 200);
        } catch (\Exception $exception) {
            return send_error('Não foi possível encontrar este QRCode!', $exception->getMessage(), 404);
        }
    }
}
