<?php
/**
 * TextSanitizer extension
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
 * @package         class
 * @subpackage      textsanitizer
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: config.php 8066 2011-11-06 05:09:33Z beckmi $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

return $config = array(
    // Click to open an image in a new window in full size using CaricFoto
    'clickable' => 1,
    // Resize the image down to max_width set below
    'resize' => 1,
    // Maximum width of an image displayed on page, otherwise it will be resized
    'max_width' => 300);

?>