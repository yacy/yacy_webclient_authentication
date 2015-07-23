<?php
  /**
   * config settings related to yacy_webclient_authentication go here
   * for settings related to the yacy_webclient frontend, please refer to js/setup.js of the frontend
   */
   
  class Config {
    /**
     * default collection to search for, if no user specific collection is given
     * this can either be a non-existing collection name (to force no results to be found) or
     * a puclicly accessible collection name
     *
     * if more than one collection should be used, you can concat them by |
     */
    const YACY_DEFAULT_COLLECTION = 'yacy_default_collection';

    /**
     * Yacy Backend Server
     */
    const YACY_SERVER_SEARCH = 'http://172.20.12.38:8090/';
    
    /** 
     * Yacy Search - script name
     */
    const YACY_SEARCHSCRIPT  = 'yacysearch.json';
    
    /**
     * Yacy Suggest - script name
     */
    const YACY_SUGGESTSCRIPT = 'suggest.json';
    
    /**
     * which auth module to use
     * currently available:
     * - LDAP - only used for development so far, use with care
     * - Plexcel - Single Sign On solution by ioplex, see https://www.ioplex.com/plexcel.html
     */
    const AUTH_MODULE = 'Plexcel';
    
    /**
     * wether to turn debug output on or off; debug levels are not recognised
     * in case this is turned on, debug output goes to php error reporting with E_USER_NOTICE
     * if you are missing debug output there, please ensure that E_USER_NOTICE isn't disabled in php.ini
     */
    const DEBUG = false;        
   
    /**
     * yacy role name prefix and suffix
     * these are used to determine if a given group/role name is realted to yacy
     * prefix and suffix are stripped off
     */
    const YACY_ROLE_NAME_PREFIX = 'CN=G_Role_KVB_YaCy_';
    const YACY_ROLE_NAME_SUFFIX = ',OU=YaCy,OU=Gruppen Projekte und Anwendungen,OU=42 - Informationsmanagement,OU=KVB,OU=Organisational,DC=root,DC=ad,DC=intra';
   
                 
    /**
     * BEGIN: AUTH MODULE LDAP specific settings 
     */ 
      /** 
       * LDAP / AD auth server
       */
      static $ldapServer = array(
        '172.20.60.52' // kvbdc01
      );
      /**
       * system account - used to determine user's full dn (nothing else)
       */
      const BIND_DN       = 'CN=taldo,OU=Support Accounts,OU=Administrative,dc=root,dc=ad,dc=intra';
      const BIND_PASSWORD = 'hf63bh';
      const LDAP_BASE_DN  = 'dc=root,dc=ad,dc=intra';
    /**
     * END: AUTH MODULE LDAP specific settings 
     */ 
  
  } // class Config
  
?>