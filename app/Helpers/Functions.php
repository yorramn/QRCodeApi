<?php
function send_error($message, $messages = [], $code = 404):\Illuminate\Http\JsonResponse
{
    $response = [
      'status' => false,
      'message' => $message
    ];
    !empty($messages) ? $response['errors'] = $messages : null;
    return response()->json([$response],$code);
}

function send_response($message, $data = [], $code = 200):\Illuminate\Http\JsonResponse
{
    $response = [
      'status' => true,
      'message' => $message
    ];
    !empty($data) ? $response['data'] = $data : null;
    return response()->json([$response],$code);
}
