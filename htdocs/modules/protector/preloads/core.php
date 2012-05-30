<?php
/**
 * Protector
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         protector
 * @since           2.4.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id: core.php 8066 2011-11-06 05:09:33Z beckmi $
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Protector core preloads
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 */
class ProtectorCorePreload extends XoopsPreloadItem
{
    function eventCoreIncludeCommonStart($args)
    {
        include XOOPS_TRUST_PATH . '/modules/protector/include/precheck.inc.php';
    }

    function eventCoreIncludeCommonEnd($args)
    {
            include XOOPS_TRUST_PATH . '/modules/protector/include/postcheck.inc.php';
        }

    function eventCoreClassDatabaseDatabasefactoryConnection($args)
    {
        if (defined('XOOPS_DB_ALTERNATIVE') && class_exists(XOOPS_DB_ALTERNATIVE)) {
            $args[0] = XOOPS_DB_ALTERNATIVE;
        }
    }

}
?>