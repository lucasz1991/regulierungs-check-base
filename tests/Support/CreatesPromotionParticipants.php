<?php

namespace Tests\Support;

use App\Models\User;
use App\Services\Auth\CustomerAccountService;
use Illuminate\Support\Str;

trait CreatesPromotionParticipants
{
    /**
     * Create the same complete guest account that the public registration flow creates.
     *
     * @param  array<string, mixed>  $attributes
     */
    protected function createPromotionParticipant(array $attributes = []): User
    {
        $email = (string) ($attributes['email'] ?? fake()->unique()->safeEmail());
        $name = (string) ($attributes['name'] ?? 'Teilnehmer '.Str::upper(Str::random(8)));
        $plainPassword = (string) ($attributes['plain_password'] ?? 'Sicheres!Passwort1');

        unset($attributes['plain_password']);

        $user = app(CustomerAccountService::class)->registerPassword($email, $name, $plainPassword);
        $user->forceFill(array_merge([
            'email_verified_at' => now(),
        ], $attributes))->save();

        return $user->fresh();
    }
}
