<?php
/**
 * Created by PhpStorm.
 * User: Luka
 * Date: 2018-11-26
 * Time: 21:53
 */

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