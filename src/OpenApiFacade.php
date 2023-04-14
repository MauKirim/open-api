<?php

namespace MauKirim\OpenApi;

use Illuminate\Support\Facades\Facade;

/**
 * @see \MauKirim\OpenApi\Skeleton\SkeletonClass
 */
class OpenApiFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'open-api';
    }
}
