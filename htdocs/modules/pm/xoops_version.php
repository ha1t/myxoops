<?php
/**
 * Private message module
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
 * @author          Jan Pedersen
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: xoops_version.php 8446 2011-12-08 13:27:46Z trabis $
 */

/**
 * This is a temporary solution for merging XOOPS 2.0 and 2.2 series
 * A thorough solution will be available in XOOPS 3.0
 *
 */

$modversion = array();
$modversion['name'] = _PM_MI_NAME;
$modversion['version'] = 1.08;
$modversion['description'] = _PM_MI_DESC;
$modversion['author'] = "Jan Pedersen, Taiwen Jiang";
$modversion['credits'] = "The XOOPS Project, Wanikoo";
$modversion['help']        = 'page=help';
$modversion['license']     = 'GNU GPL 2.0';
$modversion['license_url'] = "www.gnu.org/licenses/gpl-2.0.html/";
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = "pm";
$modversion['dirmoduleadmin'] = 'Frameworks/moduleclasses';
$modversion['icons16'] = 'Frameworks/moduleclasses/icons/16';
$modversion['icons32'] = 'Frameworks/moduleclasses/icons/32';

//about
$modversion['release_date']     = '2011/10/08';
$modversion["module_website_url"] = "http://www.xoops.org/";
$modversion["module_website_name"] = "XOOPS";
$modversion["module_status"] = "RC";
$modversion['min_php']='5.2';
$modversion['min_xoops']="2.5.0";

// Admin menu
// Set to 1 if you want to display menu generated by system module
$modversion['system_menu'] = 1;

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/admin.php";
$modversion['adminmenu'] = "admin/menu.php";

// Mysql file
//$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

// Table
//$modversion['tables'][0] = "pm_messages";

// Scripts to run upon installation or update
$modversion['onInstall'] = "include/install.php";
$modversion['onUpdate'] = "include/update.php";

// Templates
$modversion['templates'] = array();
$modversion['templates'][1]['file'] = 'pm_pmlite.html';
$modversion['templates'][1]['description'] = '';
$modversion['templates'][2]['file'] = 'pm_readpmsg.html';
$modversion['templates'][2]['description'] = '';
$modversion['templates'][3]['file'] = 'pm_viewpmsg.html';
$modversion['templates'][3]['description'] = '';
//$modversion['templates'][4]['file'] = 'pm_lookup.html';
//$modversion['templates'][4]['description'] = '';

// Menu
$modversion['hasMain'] = 1;

$modversion['config'] = array();
$modversion['config'][]=array(
    'name' => 'perpage',
    'title' => '_PM_MI_PERPAGE',
    'description' => '_PM_MI_PERPAGE_DESC',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 20);

$modversion['config'][]=array(
    'name' => 'max_save',
    'title' => '_PM_MI_MAXSAVE',
    'description' => '_PM_MI_MAXSAVE_DESC',
    'formtype' => 'textbox',
    'valuetype' => 'int',
    'default' => 10);

$modversion['config'][]=array(
    'name' => 'prunesubject',
    'title' => '_PM_MI_PRUNESUBJECT',
    'description' => '_PM_MI_PRUNESUBJECT_DESC',
    'formtype' => 'textbox',
    'valuetype' => 'text',
    'default' => _PM_MI_PRUNESUBJECTDEFAULT);

$modversion['config'][]=array(
    'name' => 'prunemessage',
    'title' => '_PM_MI_PRUNEMESSAGE',
    'description' => '_PM_MI_PRUNEMESSAGE_DESC',
    'formtype' => 'textarea',
    'valuetype' => 'text',
    'default' => _PM_MI_PRUNEMESSAGEDEFAULT);
?>