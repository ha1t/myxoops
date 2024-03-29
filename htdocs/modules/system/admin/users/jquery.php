<?php
/**
 * jQuery File Tree PHP Connector
 * Output a list of files for jQuery File Tree
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author      Maxime Cointin (AKA Kraven30)  
 * @package     system
 * @version     $Id: jquery.php 8066 2011-11-06 05:09:33Z beckmi $
 */

require('../../../../mainfile.php');
require(XOOPS_ROOT_PATH.'/header.php');

if (!defined('XOOPS_ROOT_PATH')) { die('Error'); }

if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) exit( _NOPERM );

if (isset($_REQUEST["op"])) {
	$op = $_REQUEST["op"];
} else {
	@$op = "default";
}

switch ($op) {

	// Display post
	case 'display_post': 
		global $xoopsDB;
        
        $GLOBALS['xoopsLogger']->activated = false;
        
		include_once XOOPS_ROOT_PATH . '/include/comment_constants.php';
		include_once XOOPS_ROOT_PATH . '/class/xoopsmodule.php';
        include_once XOOPS_ROOT_PATH . '/modules/system/include/functions.php';
				
		$tables = array();
		// Count comments (approved only: com_status == XOOPS_COMMENT_ACTIVE)		
		$tables[] = array ('table_name' => 'xoopscomments', 'uid_column' => 'com_uid', 'criteria' => new Criteria('com_status', XOOPS_COMMENT_ACTIVE));
		// Count forum posts
		if (XoopsModule::getByDirname("newbb")) {
			$tables[] = array ('table_name' => 'bb_posts', 'uid_column' => 'uid');
		}
		$uid = system_CleanVars($_REQUEST, 'uid', int);
		$total_posts = 0;
		foreach ($tables as $table) 
		{
			$criteria = new CriteriaCompo();
			$criteria->add (new Criteria($table['uid_column'], $uid ));
			if (!empty($table['criteria'])) {
				$criteria->add ($table['criteria']);
			}
			$sql = "SELECT COUNT(*) AS total FROM ".$xoopsDB->prefix($table['table_name']) . ' ' . $criteria->renderWhere();
			if ( $result = $xoopsDB->query($sql) ) {
				if ($row = $xoopsDB->fetchArray($result)) {
					$total_posts = $total_posts + $row['total'];
				}
			}
		}

		$sql = "UPDATE ".$xoopsDB->prefix("users")." SET posts = '".$total_posts."' WHERE uid = '".$uid."'";
		if ( !$result = $xoopsDB->queryF($sql) ) {
			redirect_header("admin.php?fct=users",1,_AM_SYSTEM_USERS_CNUUSER);
		} else {
			echo $total_posts;
		}
	   break;
}

?>