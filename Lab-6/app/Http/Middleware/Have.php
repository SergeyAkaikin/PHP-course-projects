<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use App\Models\Auth\AuthInfo;
use App\Models\Permissions\PermissionCode;
use App\Services\PermissionService\ManagementPermissionService;
use Closure;
use Illuminate\Http\Request;

class Have
{
    public function __construct(private readonly ManagementPermissionService $managementPermissionService)
    {
    }

    public static function permissions(PermissionCode ...$permissionCodes): string
    {
        $permissionStr = implode(',', array_map(fn(PermissionCode $code): int => $code->value, $permissionCodes));
        return self::class . ':' . $permissionStr;
    }

    public function handle(Request $request, Closure $next, ...$permissionCodes)
    {
        /** @var AuthInfo|null $authInfo */
        $authInfo = $request->attributes->get('authInfo');
        if ($authInfo === null && count($permissionCodes) > 0) {
            return response(status: 403);
        }

        foreach ($permissionCodes as $permissionCode) {
            if (in_array($permissionCode, $authInfo->permissions, true)) {

                if ($this->managementPermissionService->isRegularPermission(PermissionCode::from(intval($permissionCode)))) {
                    $accessId = $request->attributes->get('requestedAccessId');

                    if ($authInfo->user_id === $accessId) {
                        return $next($request);
                    }

                } else {
                    return $next($request);
                }

            }
        }


        return response(status: 403);
    }
}
