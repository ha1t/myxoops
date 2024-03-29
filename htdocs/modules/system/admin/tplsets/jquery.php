<?php
/**
 * Template Manager
 * Manage all templates: theme and module
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

include dirname( dirname( dirname( __FILE__ ) ) ) . '/header.php';

if (!defined('XOOPS_ROOT_PATH')) { die('Error'); }

if ( !is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) exit( _NOPERM );

error_reporting(0);
$GLOBALS['xoopsLogger']->activated = false;

if ( file_exists("./../../language/".$xoopsConfig['language']."/admin/tplsets.php") ) {
	include_once("./../../language/".$xoopsConfig['language']."/admin/tplsets.php");
} else {
	include_once("./../../language/english/admin/tplsets.php");
}

if (isset($_REQUEST["op"])) {
	$op = $_REQUEST["op"];
} else {
	@$op = "default";
}

$GLOBAL['xoopsLogger']->usePopup = true;

switch ($op) {
	// Display tree folder
	case "tpls_display_folder":
		$_REQUEST['dir'] = urldecode($_REQUEST['dir']);
		$root = XOOPS_THEME_PATH;
		if( file_exists($root . $_REQUEST['dir']) ) {
			$files = scandir($root . $_REQUEST['dir']);
			natcasesort($files);
			if( count($files) > 2 ) { /* The 2 accounts for . and .. */
				echo "<ul class=\"jqueryFileTree\" style=\"display: none;\">";
				// All dirs
				foreach( $files as $file ) {

					if( file_exists($root . $_REQUEST['dir'] . $file) && $file != '.' && $file != '..' && is_dir($root . $_REQUEST['dir'] . $file) ) {
						//retirer .svn
						$file_no_valid = array('.svn', 'icons', 'img', 'images', 'language');

						if(!in_array($file, $file_no_valid))
						{
							echo "<li class=\"directory collapsed\"><a href=\"#\" rel=\"" . htmlentities($_REQUEST['dir'] . $file) . "/\">" . htmlentities($file) . "</a></li>";
						}
					}
				}
				// All files
				foreach( $files as $file )
				{
					if( file_exists($root . $_REQUEST['dir'] . $file) && $file != '.' && $file != '..' && !is_dir($root . $_REQUEST['dir'] . $file) && $file != 'index.html' ) {
						$ext = preg_replace('/^.*\./', '', $file);

						$extensions = array('.html', '.htm', '.css');
						$extension_verif = strrchr($file, '.');

						if(in_array($extension_verif, $extensions)) {
							echo "<li class=\"file ext_$ext\"><a href=\"#\" onclick=\"tpls_edit_file('".htmlentities($_REQUEST['dir'].$file)."', '".htmlentities($_REQUEST['dir'])."', '".htmlentities($file)."', '".$ext."');\" rel=\"tpls_edit_file('".htmlentities($_REQUEST['dir'].$file)."', '".htmlentities($_REQUEST['dir'])."', '".htmlentities($file)."', '".$ext."');\">" . htmlentities($file) . "</a></li>";
						} else {
							//echo "<li class=\"file ext_$ext\">" . htmlentities($file) . "</li>";
						}

					}
				}
				echo "</ul>";
			}
		}
	    break;
    // Edit File
	case 'tpls_edit_file':
		$path_file = XOOPS_ROOT_PATH.'/themes'.trim($_REQUEST['path_file']);

		//Button restore
		if(file_exists($path_file.'.back')){
			$restore = '<button class="ui-corner-all tooltip" type="button" onclick="tpls_restore(\''.$path_file.'\')" value="'._AM_SYSTEM_TEMPLATES_RESTORE.'" title="'._AM_SYSTEM_TEMPLATES_RESTORE.'">
                            <img src="'.system_AdminIcons('revert.png').'" alt="'._AM_SYSTEM_TEMPLATES_RESTORE.'" />
                        </button>';
		}else{
			$restore = '';
		}
		xoops_load('XoopsFile');
        XoopsFile::load('file');

		$file = XoopsFile::getHandler('file', $path_file);
		$content = $file->read();
		if(empty($content)) {
			echo _AM_SYSTEM_TEMPLATES_EMPTY_FILE;
		}
		$ext = preg_replace('/^.*\./', '', $_REQUEST['path_file']);

		echo '<form name="back" action="admin.php?fct=tplsets&op=tpls_save" method="POST">
			  <table border="0">
				<tr>
					<td>
                          <div class="xo-btn-actions">
                              <div class="xo-buttons">
                                  <button class="ui-corner-all tooltip" type="submit" value="'._AM_SYSTEM_TEMPLATES_SAVE.'" title="'._AM_SYSTEM_TEMPLATES_SAVE.'">
                                      <img src="'.system_AdminIcons('save.png').'" alt="'._AM_SYSTEM_TEMPLATES_SAVE.'" />
                                  </button>
                                  '.$restore.'
                                  <button class="ui-corner-all tooltip" type="button" onclick="$(\'#display_contenu\').hide();$(\'#display_form\').fadeIn(\'fast\');" title="'._AM_SYSTEM_TEMPLATES_CANCEL.'">
                                      <img src="'.system_AdminIcons('cancel.png').'" alt="'._AM_SYSTEM_TEMPLATES_CANCEL.'" />
                                  </button>
                                  <div class="clear"></div>
                             </div>
                         </div>
                    </td>
				</tr>
				<tr>
					<td><textarea id="code_mirror" name="templates" rows=24 cols=110>'.$content.'</textarea></td>
				</tr>
			  </table>';
		echo '<input type="hidden" name="path_file" value="'.$path_file.'"><input type="hidden" name="file" value="'.trim($_REQUEST['file']).'"><input type="hidden" name="ext" value="'.$ext.'"></form>';
	    break;

	// Restore backup file
	case 'tpls_restore':
        $extensions = array('.html', '.htm', '.css');

        //check if the file is inside themes directory
        $valid_dir = stristr(realpath($_REQUEST['path_file']), realpath(XOOPS_ROOT_PATH . '/themes'));

		$old_file = $_REQUEST['path_file'].'.back';
		$new_file = $_REQUEST['path_file'];

        $extension_verif = strrchr($new_file, '.');
		if($valid_dir && in_array($extension_verif, $extensions) && file_exists($old_file) && file_exists($new_file) )
		{
			if(unlink($new_file))
			{
				if(rename($old_file, $new_file))
				{
					xoops_result(_AM_SYSTEM_TEMPLATES_RESTORE_OK);
					exit();
				}
			}
		}
		xoops_error(_AM_SYSTEM_TEMPLATES_RESTORE_NOTOK);
        break;

}

?>