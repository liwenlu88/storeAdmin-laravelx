<?php

namespace App\Http\Controllers;

use App\Models\Recycle;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RecycleController extends Controller
{
    /**
     * 获取回收站列表
     * @param Request $request
     * @param string $name -- 搜索名称
     * @param string $type -- 搜索类型
     * @param int $pageSize -- 每页数量
     * @return false|string
     */
    public function getRecycleList(Request $request, string $name = '', string $type = '', int $pageSize = 10): false|string
    {
        $startAtAndEndAt = [
            date('Y-m-d h:i:s', strtotime(date('Y-m-d h:i:s', time()) . '- 1 month')),
            date('Y-m-d h:i:s')
        ];
        $name = $request->input('name', $name);
        $type = $request->input('type', $type);
        $start_at = $request->input('start_at', $startAtAndEndAt[0]);
        $end_at = $request->input('end_at', $startAtAndEndAt[1]);
        $pageSize = $request->input('page_size', $pageSize);

        try {
            $res = Recycle::where([
                ['name', 'like', '%' . $name . '%'],
                ['type', 'like', '%' . $type . '%'],
            ])
                ->whereBetween('created_at', [$start_at, $end_at])
                ->orderBy('created_at', 'desc')
                ->paginate($pageSize)
                ->toArray();

            return statusJson(200, true, 'success', $res);
        } catch (Exception $e) {
            return statusJson(400, false, $e->getMessage());
        }
    }

    /**
     * 获取回收站类型列表
     * @return false|string
     */
    public function getRecycleTypeList(): false|string
    {
        try {
            $res = Recycle::groupBy('type')
                ->pluck('type')
                ->toArray();

            return statusJson(200, true, 'success', $res);
        } catch (Exception $e) {
            return statusJson(400, false, $e->getMessage());
        }
    }

    /**
     * 恢复回收站数据
     * @param Request $request
     * @return false|string
     */
    public function restoreRecycle(Request $request): false|string
    {
        $res = [
            'id' => 0,
            'label' => '',
            'item_id' => 0,
        ];

        $data = $request->input('data', $res);

        try {
            foreach ($data as $item) {
                Recycle::where(['id' => $item['id']])->delete();

                DB::table($item['label'])
                    ->where(['id' => $item['item_id']])
                    ->update([
                        'deleted_at' => null,
                        'updated_at' => date('Y-m-d H:i:s'),
                    ]);
            }

            return statusJson(200, true, 'success');
        } catch (Exception $e) {
            return statusJson(400, false, $e->getMessage());
        }
    }

    /**
     * 删除回收站数据
     * @param Request $request
     * @return false|string
     */
    public function deleteRecycle(Request $request): false|string
    {
        $res = [
            'id' => 0,
            'label' => '',
            'item_id' => 0,
        ];

        $data = $request->input('data', $res);

        try {
            foreach ($data as $item) {
                Recycle::where(['id' => $item['id']])->delete();

                DB::table($item['label'])
                    ->where(['id' => $item['item_id']])
                    ->delete();
            }

            return statusJson(200, true, 'success');
        } catch (Exception $e) {
            return statusJson(400, false, $e->getMessage());
        }
    }
}
