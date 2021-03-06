<?php
/**
 * Kunena Plugin
 * @package Kunena.Plugins
 * @subpackage AlphaUserPoints
 *
 * @copyright (C) 2008 - 2013 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();

class KunenaProfileAlphaUserPoints extends KunenaProfile {
	protected $params = null;

	public function __construct($params) {
		$this->params = $params;
	}

	public function getUserListURL($action = '', $xhtml = true) {
		$config = KunenaFactory::getConfig ();
		$my = JFactory::getUser();
		if ( $config->userlist_allowed == 1 && $my->id == 0  ) return false;
		return AlphaUserPointsHelper::getAupUsersURL ();
	}

	public function getProfileURL($user, $task = '', $xhtml = true) {
		if ($user == 0)
			return false;
		$user = KunenaFactory::getUser ( $user );
		$my = JFactory::getUser ();
		if ($user === false)
			return false;
		$userid = $my->id != $user->userid ? '&userid=' . AlphaUserPointsHelper::getAnyUserReferreID ( $user->userid ) : '';
		$AUP_itemid = AlphaUserPointsHelper::getItemidAupProfil ();
		return JRoute::_ ( 'index.php?option=com_alphauserpoints&view=account' . $userid . '&Itemid=' . $AUP_itemid, $xhtml );
	}

	public function _getTopHits($limit=0) {
		$db = JFactory::getDBO ();
		$query = "SELECT userid AS id, profileviews AS count FROM #__alpha_userpoints WHERE profileviews>0 ORDER BY profileviews DESC";
		$db->setQuery ( $query, 0, $limit );
		$top = (array) $db->loadObjectList ();
		KunenaError::checkDatabaseError ();
		return $top;
	}

	public function showProfile($view, &$params) {}

	public function getEditProfileURL($userid, $xhtml = true) {
		return $this->getProfileURL($userid, $task = '', $xhtml = true);
	}
}
