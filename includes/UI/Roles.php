<?php

namespace RRZE\Video\UI;

defined('ABSPATH') || exit;

/**
 * Class Roles
 * @package RRZE\Video
 */
class Roles
{
    protected static function getRolesArgs()
    {
        return [
            'administrator' => [
                'cpts' => array_keys(Capabilities::getCurrentCptArgs()),
                'exceptions' => []
            ],
            'editor' => [
                'cpts' => array_keys(Capabilities::getCurrentCptArgs()),
                'exceptions' => []
            ]
        ];
    }

    public static function addRoleCaps()
    {
        foreach (self::getRolesArgs() as $role => $args) {
            foreach ($args['cpts'] as $cpt) {
                self::addRoleCptCaps($role, $cpt, $args['exceptions']);
            }
        }
    }

    public static function removeRoleCaps()
    {
        foreach (self::getRolesArgs() as $role => $args) {
            foreach ($args['cpts'] as $cpt) {
                self::removeRoleCptCaps($role, $cpt);
            }
        }
    }

    protected static function addRoleCptCaps(string $role, string $cpt, array $exceptions = [])
    {
        $roleObj = get_role($role);
        if (is_null($roleObj)) {
            return;
        }

        $capsObj = Capabilities::getCptCaps($cpt);
        foreach ($capsObj as $key => $cap) {
            if (!$roleObj->has_cap($cap) && !in_array($key, $exceptions)) {
                $roleObj->add_cap($cap);
            }
        }
    }

    protected static function removeRoleCptCaps(string $role, string $cpt)
    {
        $roleObj = get_role($role);
        if (is_null($roleObj)) {
            return;
        }

        $capsObj = Capabilities::getCptCaps($cpt);
        foreach ($capsObj as $cap) {
            if ($cap != 'read' && $roleObj->has_cap($cap)) {
                $roleObj->remove_cap($cap);
            }
        }
    }
}
