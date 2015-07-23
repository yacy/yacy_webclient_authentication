<?php
  /**
   * this class could be used to authenticate against ldap or ad, in cases if sinlge sign on (via plexcel or other) isn't avaible
   * so far it was only used in development, therefore no html login script exists, instead the ldap credentials are given
   * over here as const - you should REALLY change this, if this module should be used in a prod environment
   *
   * this module requires certain config settings, 
   * @see class/config.class.php
   */
   
  class YacyLdapAuth {
    /**
     * Ldap/Ad Username
     */
    const AUTH_USER = 'taldo';
    // const AUTH_USER = 'Yacy_1';
    
    /**
     * Ldap/Ad Pass
     */
    const AUTH_PWD = 'hf63bh';
    // const AUTH_PWD = 'kvb123';
    
    /**
     * getGroupMembership() - return any group names the user is a member of; including those that are not relevant for yacy collections
     * filtering/mapping is done in class UserPrivs, 
     * @see class/user_privs.class.php
     * 
     * @return array list of all found group names
     */
    public static function getGroupMemberShip() {
      // try to connect to one of the given ldap/ad server
      $ldapConnect = false;
      foreach (Config::$ldapServer as $ldapHost) {
        if (!$ldapConnect) {
          $ldapConnect = ldap_connect($ldapHost);
        }
      }

      if ($ldapConnect) { 
        ldap_set_option($ldapConnect, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapConnect, LDAP_OPT_REFERRALS, 0);
        // ldap_set_option(NULL, LDAP_OPT_DEBUG_LEVEL, 1); // debug only

        // bind with the given system account to determine user's distinguished name (dn)
        $ldapBind = ldap_bind($ldapConnect, Config::BIND_DN, Config::BIND_PASSWORD);
        if ($ldapBind) {
          if (Config::DEBUG) {
            trigger_error('ldap bind with system credentials: success', E_USER_NOTICE);
            trigger_error('trying to authenticate user: ' . self::AUTH_USER, E_USER_NOTICE);
          }

          $ldapResult = ldap_search($ldapConnect, Config::LDAP_BASE_DN, "CN=" . self::AUTH_USER);           

          if (ldap_count_entries($ldapConnect, $ldapResult) == 1) {
            $ldapRecords = ldap_get_entries($ldapConnect, $ldapResult);
            $userDN = $ldapRecords[0]['dn'];
            if (Config::DEBUG) {
              trigger_error('found user dn: ' . $userDN, E_USER_NOTICE);
            }
        
            // re-bind with user-specific credentials (determined dn and given pass)
            $ldapBind = ldap_bind($ldapConnect, $userDN, self::AUTH_PWD);

            if ($ldapBind) {
              if (Config::DEBUG) {
                trigger_error('bind with user specific credentials: success', E_USER_NOTICE);
              }       
        
              $commonName = $ldapRecords[0]['cn'][0];
              $ldapSearchQuery = 'CN=' . $commonName;
              $ldapResult = ldap_search($ldapConnect, Config::LDAP_BASE_DN, $ldapSearchQuery);
              $ldapRecords = ldap_get_entries($ldapConnect, $ldapResult);
          
              $isMemberOf = array();
              for ($i = 0; $i < $ldapRecords[0]['memberof']['count']; $i++) {
                $isMemberOf[] = $ldapRecords[0]['memberof'][$i];
              }
              return $isMemberOf;
	    } else {
	      throw new Exception('received exactly one dn, but could not bind - please check the given credentials');
            }
          } else {
            throw new Exception('could not determine users dn - please verify the given credentials');
          }       
        } else {
          throw new Exception('could not bind with system account - please verify the system account given');
        }
      } else {
        throw new Exception('could not connect to any of the given ldap/ad server');  
      }
    } // function getGroupMembership
  
  
  } // class YacyLdapAuth

?>