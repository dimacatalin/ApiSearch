<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasFactory;

    protected $table = 'users';

    const FIRST_PROVIDER = 'provider1';
    const SECOND_PROVIDER = 'provider2';
    const THIRD_PROVIDER = 'provider3';

    const PROVIDERS = [
        User::FIRST_PROVIDER,
        User::SECOND_PROVIDER,
        User::THIRD_PROVIDER
    ];

    protected $fillable = [
        'name',
        'company',
        'linkedin',
        'closing_provider',
    ];

    public function getProviders()
    {
        if (!$this->closing_provider) {
            return $this::PROVIDERS;
        }

        $index = array_search($this->closing_provider, $this::PROVIDERS);
        return array_slice($this::PROVIDERS, $index + 1);

    }

    public function emails()
    {
        return $this->hasMany(Email::class, 'user_id');
    }
}
