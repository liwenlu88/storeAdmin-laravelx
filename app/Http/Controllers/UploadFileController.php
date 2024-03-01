<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UploadFileController extends Controller
{
    /**
     * 上传头像
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function uploadAvatar(Request $request): JsonResponse
    {
        if ($request->hasFile('file')) {
            try {
                // 获取文件保存目录
                $fileDirectory = $request->header('file-directory');
                // 获取用户ID
                $id = $request->header('user_id');
                // 获取文件
                $file = $request->file('file');
                // 获取文件后缀
                $fileExtension = strtolower($file->getClientOriginalExtension()) ?: 'png';
                // 重命名文件 生成新文件名 格式：用户ID_时间戳_保存目录.后缀
                $newFileName = $id . '_' . time() . '_' . $fileDirectory . '.' . $fileExtension;
                // 保存文件 生成文件路径
                $path = Storage::disk('images')->putFileAs($fileDirectory, $file, $newFileName);
                // 返回文件路径
                return statusResponse(200, true, 'success', [
                    'path' => '/uploads/images/' . $path,
                ]);
            } catch (Exception $e) {
                return statusResponse(400, false, $e->getMessage());
            }
        } else {
            return statusResponse(400, false, 'error');
        }
    }
}