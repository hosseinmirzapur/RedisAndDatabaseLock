<?php

use App\Exceptions\CustomException;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (!function_exists('exists')) {
    /**
     * @param $item
     * @return bool
     */
    function exists($item): bool
    {
        return isset($item) && $item != null && $item != '';
    }
}

if (!function_exists('filterData')) {
    /**
     * @param array $data
     * @return array
     * @throws CustomException
     */
    function filterData(array $data): array
    {
        foreach ($data as $key => $value) {
            if (!exists($value)) {
                unset($data[$key]);
            }
        }
        if (empty($data)) {
            throw new CustomException('Sent Data cannot be empty or without values.');
        }
        return $data;
    }
}

if (!function_exists('handleFile')) {
    /**
     * @param $file
     * @param string $path
     * @return string
     */
    function handleFile($file, string $path = ''): string
    {
        $filename = time() . '_' . Str::random(5) . '.' . $file->getClientOriginalExtension();
        Storage::putFileAs($path, $file, $filename);
        return $path . '/' . $filename;
    }
}

if (!function_exists('successResponse')) {
    /**
     * @param array $response
     * @param int $statusCode
     * @return JsonResponse
     */
    function successResponse(array $response = [], int $statusCode = 200): JsonResponse
    {
        return empty($response) ? response()->json([
            'success' => true,
            'message' => 'Operation successful'
        ]) : response()->json([
            'success' => true,
            'data' => $response
        ], $statusCode);
    }
}

if (!function_exists('currentUser')) {
    /**
     * @return Authenticatable|User|null
     */
    function currentUser(): Authenticatable|User|null
    {
        return auth('sanctum')->user();
    }
}
