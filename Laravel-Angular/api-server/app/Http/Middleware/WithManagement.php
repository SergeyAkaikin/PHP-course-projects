<?php

namespace App\Http\Middleware;

use App\Models\Auth\AuthInfo;
use App\Models\Permissions\ContentType;
use App\Services\PermissionService\ManagementPermission\OwnerIdProviderResolver;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WithManagement
{
    public function __construct(private readonly OwnerIdProviderResolver $permissionService)
    {
    }

    public static function accessTo(ContentType $contentType, string $parameterName): string
    {
        return self::class . ':' . $contentType->value . ',' . $parameterName;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure(Request): (Response|RedirectResponse) $next
     * @return Response|RedirectResponse
     */
    public function handle(Request $request, Closure $next, int $contentType, string $parameterName)
    {

        $contentId = (int)$request->route($parameterName);
        $accessIdProvider = $this->permissionService->getOwnerIdProvider($contentType);

        if ($accessIdProvider === null) {
            Log::critical(
                'Used not correct contentDefinition in route with content management access to ',
                ['path' => $request->path(), 'contentDefinition' => $contentType]
            );
            return response(status: 500);
        }

        $requestedAccessId = $accessIdProvider->getOwnerId($contentId);

        if ($requestedAccessId === null) {
            return response(status: 404);
        }

        /** @var AuthInfo $authInfo */
        $authInfo = $request->attributes->get('authInfo');
        if ($authInfo->userId !== $requestedAccessId) {
            return response(status: 403);
        }

        return $next($request);
    }

}
