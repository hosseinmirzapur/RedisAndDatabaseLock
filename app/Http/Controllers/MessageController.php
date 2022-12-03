<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\MessageRequest;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    /**
     * @param MessageRequest $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function store(MessageRequest $request): JsonResponse
    {
        $data = filterData($request->validated());
        $user = currentUser();
        $this->checkAuthority($data, $user);
        if ($request->hasFile('file')) {
            $data['file'] = handleFile($data['file'], '/ChatFiles');
        }
        Message::query()->create($data + ['user_id' => $user->getAttribute('id')]);
        return successResponse();
    }

    /**
     * @param $data
     * @param $user
     * @return void
     * @throws CustomException
     */
    protected function checkAuthority($data, $user): void
    {
        $chat = Chat::query()->findOrFail($data['chat_id']);
        if ($user->getAttribute('id') != $chat->getAttribute('user_id') && $user->getAttribute('id') != $chat->getAttribute('other_user_id')) {
            throw new CustomException('Unauthorized to create this message');
        }
    }

    /**
     * @param Message $message
     * @param MessageRequest $request
     * @return JsonResponse
     * @throws CustomException
     */
    public function update(Message $message, MessageRequest $request): JsonResponse
    {
        $data = filterData($request->validated());
        $message->update($data);
        return successResponse();
    }

    /**
     * @param Message $message
     * @return JsonResponse
     */
    public function destroy(Message $message): JsonResponse
    {
        $message->delete();
        return successResponse();
    }
}
