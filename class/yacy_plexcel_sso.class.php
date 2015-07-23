<?php
  require_once 'plexcel/plexcel.php';

  /**
   * Yacy User Auth based on Single Sign On with Plexcel
   * @see https://www.ioplex.com/plexcel.html
   *
   * Kerberos Single Sign On must be enabled, please refer to plexcel/examples/ for details
   */
   
  class YacyAuthPlexcel {
    /**
     * getGroupMembership() determine any group names the user belongs to
     * @access public
     * @return array
     */
    public static function getGroupMembership() {
    trigger_error('starting sso');
      $err = NULL;
      $acct = NULL;

      $px = plexcel_new(NULL, NULL);
      trigger_error('plexcel new done');
      if ($px == FALSE) {
	throw new Exception('error: ' . plexcel_status(NULL));
      } else {
	if (plexcel_sso($px) == FALSE) {
          throw new Exception('error: ' . plexcel_status($px));
	} else {
          // An account name of NULL means the current user.
          $acct = plexcel_get_account($px, NULL, array('memberof'));
	  if (is_array($acct) == FALSE) {
            throw new Exception('error: ' . plexcel_status($px));
	  }
        }
        return $acct['memberOf'];
      }
    } // function egtGroupMembership
  } // class YacyAuthPlexcel

?>