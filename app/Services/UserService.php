<?php
namespace App\Services;

use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserService
{
    public function register(array $validParams):JsonResponse
    {
        try {
            $user = User::create($validParams);
            return send_response('Usuário cadastrado com sucesso!', $user, 201);
        }catch(\Exception $exception){
            return send_error('Erro ao cadastrar o usuário', $exception->getMessage(), $exception->getCode());
        }
    }

}
