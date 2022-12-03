<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatRequest;
use App\Models\Chat;
use Illuminate\Http\JsonResponse;

class ChatController extends Controller
{
    /**
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $user = currentUser();
        $chats = $user->chats()->with(['otherUser', 'messages'])->get();
        return successResponse([
            'chats' => $chats
        ]);
    }

    /**
     * @param ChatRequest $request
     * @return JsonResponse
     */
    public function store(ChatRequest $request): JsonResponse
    {
        $user = currentUser();
        $data = $request->validated();
        $user->chats()->updateOrCreate(['other_user_id' => $data['other_user_id']], $data);
        return successResponse();
    }

    /**
     * @param Chat $chat
     * @return JsonResponse
     */
    public function show(Chat $chat): JsonResponse
    {
        return successResponse([
            'chat' => $chat->load(['messages', 'otherUser'])
        ]);
    }

    /**
     * @param Chat $chat
     * @return JsonResponse
     */
    public function destroy(Chat $chat): JsonResponse
    {
        $chat->delete();
        return successResponse();
    }
}
