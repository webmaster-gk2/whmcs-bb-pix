<?php

namespace Lkn\BBPix\Helpers;

use WHMCS\Authentication\CurrentUser;

final class Auth
{
    public static function isAdminLogged(array $permissions = []): bool
    {
        $authState = new CurrentUser();

        $adminLogged = $authState->admin();

        if ($adminLogged && count($permissions) > 0) {
            $adminDetails = localAPI('GetAdminDetails', ['adminid' => $adminLogged->id]);
            $adminPermissions = explode(',', $adminDetails['allowedpermissions']);

            $hasPermissions = !array_diff($permissions, $adminPermissions);

            if (!$hasPermissions) {
                return false;
            }
        }

        return (bool) $adminLogged;
    }
}
