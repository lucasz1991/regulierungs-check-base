<?php

namespace App\Services\Auth;

use App\Models\Customer;
use App\Models\SocialAccount;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use RuntimeException;

final class CustomerAccountService
{
    public function registerPassword(string $email, string $username, string $password): User
    {
        return DB::transaction(function () use ($email, $username, $password): User {
            $email = Str::lower(trim($email));
            $team = $this->customerTeam();

            $user = User::query()->create([
                'name' => trim($username),
                'email' => $email,
                'password' => Hash::make($password),
                'current_team_id' => $team->getKey(),
                'role' => 'guest',
                'status' => true,
            ]);

            $this->completeCustomerProfile($user, trim($username), $team);

            return $user;
        }, 3);
    }

    public function loginOrRegisterSocial(
        string $provider,
        string $providerUserId,
        string $email,
        string $displayName,
    ): User {
        return DB::transaction(function () use ($provider, $providerUserId, $email, $displayName): User {
            $provider = Str::lower(trim($provider));
            $providerUserId = trim($providerUserId);
            $email = Str::lower(trim($email));

            $identity = SocialAccount::query()
                ->where('provider', $provider)
                ->where('provider_user_id', $providerUserId)
                ->lockForUpdate()
                ->first();

            if ($identity) {
                $user = User::query()->lockForUpdate()->findOrFail($identity->user_id);
                $user = $this->assertAndNormalizeParticipant($user);

                return $user;
            }

            $user = User::query()->where('email', $email)->lockForUpdate()->first();
            if ($user) {
                $user = $this->assertAndNormalizeParticipant($user);
            } else {
                $team = $this->customerTeam();
                $username = $this->uniqueUsername($displayName !== '' ? $displayName : Str::before($email, '@'));
                $user = User::query()->create([
                    'name' => $username,
                    'email' => $email,
                    'email_verified_at' => now(),
                    'password' => Hash::make(Str::random(64)),
                    'current_team_id' => $team->getKey(),
                    'role' => 'guest',
                    'status' => true,
                ]);
                $user->forceFill(['email_verified_at' => now()])->save();
                $this->completeCustomerProfile($user, $username, $team);
            }

            SocialAccount::query()->create([
                'user_id' => $user->getKey(),
                'provider' => $provider,
                'provider_user_id' => $providerUserId,
                'provider_email' => $email,
            ]);

            return $user;
        }, 3);
    }

    public function assertAndNormalizeParticipant(User $user, bool $requireVerifiedEmail = true): User
    {
        return DB::transaction(function () use ($user, $requireVerifiedEmail): User {
            $user = User::query()->lockForUpdate()->findOrFail($user->getKey());
            $this->assertActive($user);

            if ($user->role !== 'guest' || ($requireVerifiedEmail && ! $user->hasVerifiedEmail())) {
                throw new RuntimeException('Dieses Konto ist nicht fuer die Teilnehmer-Anmeldung freigegeben.');
            }

            $team = Team::query()->where('name', 'Benutzer')->lockForUpdate()->first();
            $hasCustomer = Customer::query()->where('user_id', $user->getKey())->exists();
            if (! $team || ! $hasCustomer) {
                throw new RuntimeException('Dieses Konto ist nicht vollstaendig als Teilnehmerkonto eingerichtet.');
            }

            if ($user->current_team_id !== null && (int) $user->current_team_id !== (int) $team->getKey()) {
                throw new RuntimeException('Dieses Konto ist keinem gueltigen Teilnehmer-Team zugeordnet.');
            }

            if ($user->current_team_id === null) {
                $user->forceFill(['current_team_id' => $team->getKey()])->save();
            }
            $user->teams()->syncWithoutDetaching([$team->getKey() => ['role' => 'guest']]);

            return $user->fresh();
        }, 3);
    }

    private function customerTeam(): Team
    {
        $team = Team::query()->where('name', 'Benutzer')->lockForUpdate()->first();

        if (! $team) {
            throw new RuntimeException('Das Benutzer-Team ist nicht eingerichtet.');
        }

        return $team;
    }

    private function completeCustomerProfile(User $user, string $username, Team $team): void
    {
        Customer::query()->create([
            'user_id' => $user->getKey(),
            'first_name' => '',
            'last_name' => '',
            'username' => $username,
            'phone_number' => '',
            'street' => '',
            'city' => '',
            'state' => '',
            'postal_code' => '',
            'country' => '',
        ]);

        $user->teams()->syncWithoutDetaching([$team->getKey() => ['role' => 'guest']]);
    }

    private function uniqueUsername(string $candidate): string
    {
        $base = trim(preg_replace('/\s+/u', ' ', $candidate) ?? '');
        $base = mb_substr($base !== '' ? $base : 'Teilnehmer', 0, 230);
        $username = $base;
        $suffix = 1;

        while (Customer::query()->where('username', $username)->exists()) {
            $suffix++;
            $username = mb_substr($base, 0, 230).'-'.$suffix;
        }

        return $username;
    }

    private function assertActive(User $user): void
    {
        if (! $user->isActive()) {
            throw new RuntimeException('Dieses Benutzerkonto ist deaktiviert.');
        }
    }
}
