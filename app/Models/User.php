<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Sanctum\HasApiTokens;

class User extends Model
{
    use HasApiTokens;
    protected $guarded = [];

    /**
     * @return string
     */
    public function newToken(): string
    {
        return $this->createToken('userToken')->plainTextToken;
    }

    /**
     * @return HasMany
     */
    public function chats(): HasMany
    {
        return $this->hasMany(Chat::class, 'user_id');
    }

    /**
     * @return HasMany
     */
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'user_id');
    }


    /**
     * @return BelongsToMany
     */
    private function subjects(): BelongsToMany
    {
        return $this->belongsToMany(Subject::class, 'subject_user');
    }
}
