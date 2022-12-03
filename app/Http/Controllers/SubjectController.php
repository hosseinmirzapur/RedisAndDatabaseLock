<?php

namespace App\Http\Controllers;

use App\Exceptions\CustomException;
use App\Http\Requests\AddSubjectToUserRequest;
use App\Models\Subject;
use App\Models\User;
use App\Models\UserSubject;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class SubjectController extends Controller
{
    /**
     * @param AddSubjectToUserRequest $request
     * @return JsonResponse
     */
    public function addSubject(AddSubjectToUserRequest $request): JsonResponse
    {
        $data = $request->validated();
        DB::transaction(function () use ($data) {
            $subject = Subject::query()->where('id', $data['subject_id'])->lockForUpdate()->first();
            if ($subject->capacity == 0) {
                throw new CustomException('this subject is full');
            }
            $user = User::query()->find($data['user_id']);
            sleep(2); // Testing DB locking for 2 concurrent requests
            UserSubject::query()->create([
                'user_id' => $user->getAttribute('id'),
                'subject_id' => $subject->getAttribute('id')
            ]);
            $subject->update([
                'capacity' => $subject->getAttribute('capacity') - 1
            ]);
        });

        return successResponse();
    }
}
