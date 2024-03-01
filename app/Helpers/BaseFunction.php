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

/**
 *  构建列表树
 *
 * @param $listItems
 * @param int $parentId
 * @return array
 */
function buildListTree($listItems, int $parentId = 0): array
{
    $tree = [];

    foreach ($listItems as $menu) {
        if ($menu['parent_id'] == $parentId) {
            $children = buildListTree($listItems, $menu['id']);
            if (!empty($children)) {
                $menu['children'] = $children;
            }
            $tree[] = $menu;
        }
    }

    // 一级 按照 order 字段升序排序
    usort($tree, function ($a, $b) {
        return $a['order'] - $b['order'];
    });

    // 二级 按照 order 字段升序排序
    foreach ($tree as $key => $item) {
        if ($item['children'] != null) {
            usort($item['children'], function ($a, $b) {
                return $a['order'] - $b['order'];
            });
            $tree[$key]['children'] = array_values($item['children']);
        }
    }

    return $tree;
}