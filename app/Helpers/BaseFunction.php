<?php

/**
 * 返回结果处理
 *
 * @param $status
 * @param $success
 * @param $message
 * @param array $content
 * @return false|string
 */
function statusResponse($status, $success, $message, array $content = []): false|string
{
    return json_encode([
        'status' => $status,
        'success' => $success,
        'message' => $message,
        'content' => $content
    ]);
}