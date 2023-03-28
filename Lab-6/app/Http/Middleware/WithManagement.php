<?php

namespace App\Http\Middleware;

use App\Models\Permissions\ContentType;
use App\Services\PermissionService\ManagementPermission\IdProviderManager;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;

class WithManagement
{
    public function __construct(private readonly IdProviderManager $permissionService)
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
        $accessIdProvider = $this->permissionService->getAccessIdProvider($contentType);

        if ($accessIdProvider === null) {
            Log::critical(
                'Used not correct contentDefinition in route with content management access to ',
                ['path' => $request->path(), 'contentDefinition' => $contentType]
            );
            return response(status: 500);
        }

        $requestedAccessId = $accessIdProvider->requestedResourceAccessId($contentId);

        if ($requestedAccessId === null) {
            return response(status: 404);
        }

        $request->attributes->add(['requestedAccessId' => $requestedAccessId]);

        return $next($request);
    }

}
