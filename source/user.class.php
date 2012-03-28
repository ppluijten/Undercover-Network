<?php

class User {

    private static $loggedin = false;
    private static $userid = 0;
    private static $userdata = array();

    public static function checkUser() {
        self::checkLogin();
        self::checkUserData();
        self::checkUserGroup();
        self::checkCrewData();
        self::checkCrewFunction();
    }

    private static function checkLogin() {
        //TODO: Deze regel weghalen als helemaal is overgegaan op het nieuwe forum
        $sessionhash = (isset($_COOKIE['bbsessionhash'])) ? (string) $_COOKIE['bbsessionhash'] : (string) $_COOKIE['bb_sessionhash'];

        $getSessionData = "
            SELECT userid, loggedin
            FROM " . Settings::GetSetting('vbulletin_db_prefix') . "session
            WHERE sessionhash = '" . DB::EscapeString($sessionhash) . "'
            LIMIT 1";
        $sqlSessionData = DB::GetQuery($getSessionData);
        $sessionData = DB::GetAssoc($sqlSessionData);

        $userid = (int) $sessionData['userid'];
        $loggedin = (int) $sessionData['loggedin'];

        if ($loggedin == 2 && $userid > 0) {
            self::$loggedin = true;
            self::$userid = $userid;
        } else {
            self::$loggedin = false;
            self::$userid = 0;
        }

        self::$userdata = array();
        self::$userdata['loggedin'] = self::$loggedin;
        self::$userdata['userid'] = self::$userid;
    }

    private static function checkUserData() {
        if(self::$loggedin) {
            $getUserData = "
                SELECT usergroupid, username, email, usertitle
                FROM " . Settings::GetSetting('vbulletin_db_prefix') . "user
                WHERE userid = '" . DB::EscapeString(self::$userid) . "'
                LIMIT 1";
            $sqlUserData = DB::GetQuery($getUserData);
            $userdata = DB::GetAssoc($sqlUserData);

            self::$userdata['usergroup'] = (int) $userdata['usergroupid'];
            self::$userdata['username'] = (string) $userdata['username'];
            self::$userdata['email'] = (string) $userdata['email'];
            self::$userdata['usertitle'] = (string) $userdata['usertitle'];
        }
    }

    private static function checkUserGroup() {
        if(self::$loggedin && (int) self::$userdata['usergroup'] > 0) {
            $getUserGroup = "
                SELECT usertitle
                FROM " . Settings::GetSetting('vbulletin_db_prefix') . "usergroup
                WHERE usergroupid = '" . DB::EscapeString(self::$userdata['usergroup']) . "'
                LIMIT 1";
            $sqlUserGroup = DB::GetQuery($getUserGroup);
            $usergroup = DB::GetAssoc($sqlUserGroup);
            
            self::$userdata['usergroupname'] = $usergroup['usertitle'];
        }
    }

    private static function checkCrewData() {
        if(self::$loggedin) {
            $getCrewData = "
                SELECT crew_id, crew_name, crew_function, crew_text, crew_avatar
                FROM ug_crew
                WHERE crew_user_id = '" . DB::EscapeString(self::$userid) . "'
                LIMIT 1";
            $sqlCrewData = DB::GetQuery($getCrewData);
            $crewdata = DB::GetAssoc($sqlCrewData);

            self::$userdata['crewid'] = $crewdata['crew_id'];
            self::$userdata['crewname'] = $crewdata['crew_name'];
            self::$userdata['crewfunction'] = $crewdata['crew_function'];
            self::$userdata['crewtext'] = $crewdata['crew_text'];
            self::$userdata['crewavatar'] = $crewdata['crew_avatar'];
        }
    }

    private static function checkCrewFunction() {
        if(self::$loggedin && (int) self::$userdata['usergroup'] > 0) {
            $getUserGroup = "
                SELECT u_name, u_levels, u_order
                FROM ug_userlevels
                WHERE u_id = '" . DB::EscapeString(self::$userdata['crewfunction']) . "'
                LIMIT 1";
            $sqlUserGroup = DB::GetQuery($getUserGroup);
            $usergroup = DB::GetAssoc($sqlUserGroup);
            self::$userdata['crewfunctionname'] = $usergroup['u_name'];
            self::$userdata['crewlevels'] = $usergroup['u_levels'];
            self::$userdata['creworder'] = $usergroup['u_order'];
        }
    }

    /**
     * Fetch whether the user is logged in
     *
     * @return bool whether the user is logged in
     */
    public static function isLoggedIn() {
        return self::$loggedin;
    }

    /**
     * Fetch the user id
     *
     * @return int the user id, returns false if none was found
     */
    public static function getUserId() {
        if(self::$userid > 0) {
            return self::$userid;
        } else {
            return false;
        }
    }

    /**
     * Fetch an item from the user data array
     * 
     * @param string $key the key of the field
     * @return string the value of the selected field
     */
    public static function getUserData($key) {
        if(isset(self::$userdata[$key])) {
            return self::$userdata[$key];
        } else {
            return false;
        }
    }

    /**
     * For debug purposes: fetches the entire user data array
     *
     * @return array the user data array
     */
    public static function getUserDataArray() {
        return self::$userdata;
    }
}

?>