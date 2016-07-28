<?php


class RightsMap
{
    /*
     *	Doletic rights system :
     *		A user is composed of two values, its rights and its group :
     *			+ the right value that way :
     *				Module creators specify for each action a minimum right level required using RMASKs consts.
     *				This value is tested against current user rights when user require an action : (urgc & xxx_RMASK) !== 0
     *			+ the group is used that way :
     *				Module creators specify which groups are allowed to use the action : (xxa_G | xxb_G | xxc_G)
     *				This value is tested against current user group when user require an action :
     *
     */
    // -- consts
    // --- rights values
    const SA_R = 0x0F    /*bindec('00001111')*/
    ; // SA <=> Super Admin
    const A_R = 0x07    /*bindec('00000111')*/
    ; // A <=> Admin
    const U_R = 0x03    /*bindec('00000011')*/
    ; // U <=> User
    const G_R = 0x01    /*bindec('00000001')*/
    ; // G <=> Guest
    // --- rights masks
    const SA_RMASK = 0x08    /*bindec('00001000')*/
    ;
    const A_RMASK = 0x04    /*bindec('00000100')*/
    ;
    const U_RMASK = 0x02    /*bindec('00000010')*/
    ;
    const G_RMASK = 0x01    /*bindec('00000001')*/
    ;
    // --- groups values
    const D_G = 0x10    /*bindec('000000010000')*/
    ;
    const A_G = 0x20    /*bindec('000000100000')*/
    ;
    const M_G = 0x40    /*bindec('000001000000')*/
    ;
    const C_G = 0x80    /*bindec('000010000000')*/
    ;
    const I_G = 0x100    /*bindec('000100000000')*/
    ;
    // --- return values
    const OK = "OK";
    const KO = "KO";

    // -- attributes
    private $groups = null;    // allowed groups its a OR combination of groups values
    private $rules = null;        // rules <=> map[<action>] = rights_mask

    // -- functions
    public function __construct($groups = RightsMap::D_G, $rules = array())
    {
        $this->rules = $rules;        // default value is empty map which means only Super Admin can use functions
        $this->groups = $groups;    // default value is default group
    }

    public function SetGroups()
    {
        $this->groups = $groups;
    }

    public function AddRule($action, $mask)
    {
        $this->rules[$action] = $mask;
    }

    public function AddRules($rules)
    {
        $this->rules = array_merge($this->rules, $rules);
    }

    public function Check($rgcode, $action, $debug = false)
    {
        if ($debug) {
            $this->debug($rgcode, $action);
        }
        // check group first
        $allowed = (($rgcode & $this->groups) !== 0);
        if ($allowed) {    // if action has been added
            if (array_key_exists($action, $this->rules)) {
                // check rights against map specified mask for given action
                $allowed = (($rgcode & $this->rules[$action]) !== 0);
            } else {
                // default behaviour if action mask has not been specified is SUPER_ADMIN_RIGHTS required
                $allowed = (($rgcode & RightsMap::SA_RMASK) !== 0);
            }
        }
        // return allowed
        return ($allowed ? RightsMap::OK : RightsMap::KO);
    }

# PROTECTED & PRIVATE ########################################################

    // DEBUG ------------------------------------------------------------------
    private function debug($rgcode, $action)
    {
        echo "RightsMap::Check : --------------------------------\n";
        echo "RightsMap::Check : RGCode = 0b" . decbin($rgcode) . "\n";
        echo "RightsMap::Check : Groups = 0b" . decbin($this->groups) . "\n";
        $mask = null;
        echo "RightsMap::Check : Action = $action\n";
        if (array_key_exists($action, $this->rules)) {
            echo "RightsMap::Check : ActionMask = 0b" . decbin($this->rules[$action]) . "\n";
            $mask = $this->rules[$action];
        } else {
            echo "RightsMap::Check : ActionMask = 0b" . decbin(RightsMap::SA_RMASK) . "\n";
            $mask = RightsMap::SA_RMASK;
        }
        echo "RightsMap::Check : Group Allowed = 0b" . decbin($rgcode & $this->groups) . " (allowed if not 0)\n";
        echo "RightsMap::Check : Action Allowed = 0b" . decbin($rgcode & $mask) . " (allowed if not 0)\n";
        echo "RightsMap::Check : --------------------------------\n";
    }
    // DEBUG ------------------------------------------------------------------

}