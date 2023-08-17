<?php
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class MockRoleMiddleware extends RoleMiddleware {
    public static function authorizeRoles($allowedRoles) {
        // Bypass the authorization logic by returning early
        return;
    }
}
