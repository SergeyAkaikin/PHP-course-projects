<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\Auth\AuthInfo;
use App\Models\Permissions\UserRole;
use App\Repositories\UserRepository;
use App\Services\PermissionService\RolePermissionService;
use App\Utils\EnumBitmapEncoder;
use App\Utils\RedisConnection;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use JsonMapper;
use stdClass;

class AuthService
{
    public function __construct(
        private readonly UserRepository        $userRepository,
        private readonly RolePermissionService $permissionService
    )
    {
    }

    public function authorizeUser(string $user_name, string $password): ?string
    {
        $user = $this->userRepository->getByUserName($user_name);

        if ($user === null || !Hash::check($password, $user->password)) {
            return null;
        }

        $authInfo = new AuthInfo();
        $authInfo->user_id = $user->id;
        $authInfo->permissions = EnumBitmapEncoder::decode(UserRole::cases(), $user->roles)
            ->map(fn(int $role): Collection => $this->permissionService->getPermissionsForRole(UserRole::from($role)))
            ->flatten()
            ->toArray();


        return $this->saveAuthInfo($authInfo);
    }

    public function saveAuthInfo(AuthInfo $authInfo): string
    {
        $now = Carbon::now();
        $expiresAt = Carbon::now()->addDay();
        $authValues = new stdClass();
        $authValues->permissions = $authInfo->permissions;
        RedisConnection::appCache()->set(
            "auth-tokens:{$authInfo->user_id}",
            json_encode($authValues),
            'EX',
            $expiresAt->diffInSeconds($now)
        );

        $tokenData = (object)[
            'user_id' => $authInfo->user_id,
            'expiresAt' => $expiresAt,
            'salt' => $this->randomSalt()
        ];
        return Crypt::encrypt($tokenData);
    }


    private function randomSalt(): string
    {
        return substr(str_shuffle(MD5(microtime())), 0, 10);
    }

    public function getAuthInfo(string $token): ?AuthInfo
    {
        $tokenData = Crypt::decrypt($token);

        if (!isset($tokenData->user_id, $tokenData->expiresAt) || Carbon::now()->gte($tokenData->expiresAt)) {
            return null;
        }

        $authInfoJson = RedisConnection::appCache()->get("auth-tokens:{$tokenData->user_id}");
        $authInfo = new AuthInfo();
        $authInfo->user_id = $tokenData->user_id;

        return $authInfoJson !== null ? (new JsonMapper())->map(json_decode($authInfoJson), $authInfo) : null;
    }
}
