<?php

use Illuminate\Http\JsonResponse;

/**
 * 返回结果处理 JsonResponse
 *
 * @param $status
 * @param $success
 * @param $message
 * @param array $content
 * @return JsonResponse
 */
function statusResponse($status, $success, $message, array $content = []): JsonResponse
{
    $data = [
        'status' => $status,
        'success' => $success,
        'message' => $message,
        'content' => $content,
    ];
    return response()->json($data);
}

/**
 * 返回结果处理 json
 * @param $status
 * @param $success
 * @param $message
 * @param array $content
 * @return false|string
 */
function statusJson($status, $success, $message, array $content = []): false|string
{
    $data = [
        'status' => $status,
        'success' => $success,
        'message' => $message,
        'content' => $content,
    ];
    return json_encode($data);
}
