<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return $this->isMethod('post') ? $this->postRules() : $this->putRules();
    }

    /**
     * @return array
     */
    protected function postRules(): array
    {
        return [
            'text' => 'nullable',
            'file' => 'nullable',
            'message_id' => ['nullable', Rule::exists('messages', 'id')],
            'chat_id' => ['required', Rule::exists('chats', 'id')]
        ];
    }

    /**
     * @return array
     */
    protected function putRules(): array
    {
        return [
            'text' => 'nullable',
            'file' => 'nullable',
            'message_id' => ['nullable', Rule::exists('messages', 'id')]
        ];
    }
}
