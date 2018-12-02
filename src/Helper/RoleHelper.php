<?php
namespace App\Helper;
class RoleHelper
{
    static public function getRoleByRoleName($rolesArray, $roleNameToFind) {
        $isRoleFound = false;
        foreach ($rolesArray as $role) {
            $roleName = $role->getName();
            if ($roleName == $roleNameToFind) {
                $isRoleFound = true;
                break;
            }
        }
        return $isRoleFound;
    }
}