<?php
/**
 * Private message
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code 
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         pm
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: menu.php 8066 2011-11-06 05:09:33Z beckmi $
 */

$module_handler =& xoops_gethandler('module');
$xoopsModule =& XoopsModule::getByDirname('pm');
$moduleInfo =& $module_handler->get($xoopsModule->getVar('mid'));
$pathIcon32 = $moduleInfo->getInfo('icons32');

$adminmenu = array();

$i = 1;
$adminmenu[$i]['title'] = _PM_MI_INDEX;
$adminmenu[$i]['link'] = "admin/admin.php";
$adminmenu[$i]['icon']  = '../../'.$pathIcon32.'/home.png' ;
$i++;
$adminmenu[$i]['title'] = _PM_MI_PRUNE;
$adminmenu[$i]['link'] = "admin/prune.php";
$adminmenu[$i]['icon']  = '../../'.$pathIcon32.'/prune.png' ;
$i++;
$adminmenu[$i]['title'] = _PM_MI_ABOUT;
$adminmenu[$i]['link']  = 'admin/about.php';
$adminmenu[$i]['icon']  = '../../'.$pathIcon32.'/about.png';