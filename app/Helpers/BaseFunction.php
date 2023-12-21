<?php

use Illuminate\Http\JsonResponse;

/**
 * 返回结果处理
 *
 * @param $status
 * @param $success
 * @param $message
 * @return JsonResponse
 */
function statusResponse($status, $success, $message, $content = []): JsonResponse
{
    return response()->json([
        'status' => $status,
        'success' => $success,
        'message' => $message,
        'content' => $content
    ]);
}