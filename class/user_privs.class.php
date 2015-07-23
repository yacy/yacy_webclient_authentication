<?php
  /**
   * class UserPrivs - used to determine accessible collections for this user
   */
   
  class UserPrivs {
    /**
     * _collections arrax of all accessible collection names
     * @access private
     */
    private $_collections = array();
    
    /**
     * get all collections as url param (concat by |)
     * as the yacy server backend does not recognize any privileges, we have to ensure that at least one collection is given
     * in the case of no accessible collections, we return Config::YACY_DEFAULT_COLLECTION which could be either a non-existent collection name
     * or a publicly accessible one
     *
     * @param void
     * @accesspublic
     * @return string
     */
    public function getAsUrlParam() {
      if (count($this->_collections)) {
        return implode('|', $this->_collections);
      } else {
        return Config::YACY_DEFAULT_COLLECTION;
      }
    } // function getAsUrlParam
 

    /**
     * initCollections - receives all determined group name; filtering those related to yacy based on the given name prefix and suffix
     * @param array groupNames
     * @return void
     * @access private
     */
    private function initCollections($groupNames) {
      foreach ($groupNames as $roleName) {
        if (substr($roleName, -1 * strlen(Config::YACY_ROLE_NAME_SUFFIX)) == Config::YACY_ROLE_NAME_SUFFIX) {
          $parts = explode(',', $roleName, 2);
          $roleName = $parts[0];
          if (substr($roleName, 0, strlen(Config::YACY_ROLE_NAME_PREFIX)) == Config::YACY_ROLE_NAME_PREFIX) {
            $this->_collections[] = substr($roleName, strlen(Config::YACY_ROLE_NAME_PREFIX));
            if (Config::DEBUG) {
              trigger_error('found matching group name: ' . $roleName, E_USER_NOTICE);
            }
          } else {
            if (Config::DEBUG) {
              trigger_error('group name given, but does not match YACY_ROLE_NAME_PREFIX: ' . $roleName, E_USER_NOTICE);
            }
          }
        } else {
          if (Config::DEBUG) {
            trigger_error('group name given, but does not match YACY_ROLE_NAME_SUFFIX: ' . $roleName, E_USER_NOTICE);
          }
        }
      }                                                                                                                                                                           
    } // function initCollections
    
  
    /**
     * _construct - init of group membership is done here and passed to $this->initCollections()
     */
    public function __construct() {
      switch (Config::AUTH_MODULE) {
        case 'Plexcel' :
          require 'class/yacy_plexcel_sso.class.php';
          $this->initCollections( YacyAuthPlexcel::getGroupMembership() );
          break;
        case 'LDAP' :
          require 'class/yacy_ldap_auth.class.php';
          $this->initCollections( YacyLdapAuth::getGroupMembership() );
          break;
        default:
          throw new Exception('no auth module given, please see config.class.php');
      }
    } // function __construct
  
  } // class userPrivs
  
?>
   
  