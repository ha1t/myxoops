<?php
/**
 * XOOPS comment view
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
 * @package         kernel
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id: comment_view.php 8066 2011-11-06 05:09:33Z beckmi $
 */

if (!defined('XOOPS_ROOT_PATH') || !is_object($xoopsModule)) {
    die('Restricted access');
}

include_once $GLOBALS['xoops']->path('include/comment_constants.php');

if (XOOPS_COMMENT_APPROVENONE != $xoopsModuleConfig['com_rule']) {
    include_once $GLOBALS['xoops']->path('modules/system/constants.php');
    $gperm_handler =& xoops_gethandler('groupperm');
    $groups = ($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
    $xoopsTpl->assign('xoops_iscommentadmin', $gperm_handler->checkRight('system_admin', XOOPS_SYSTEM_COMMENT, $groups));

    xoops_loadLanguage('comment');

    $comment_config = $xoopsModule->getInfo('comments');
    $com_itemid = (trim($comment_config['itemName']) != '' && isset($_GET[$comment_config['itemName']])) ? intval($_GET[$comment_config['itemName']]) : 0;
    if ($com_itemid > 0) {
        $com_mode = isset($_GET['com_mode']) ? htmlspecialchars(trim($_GET['com_mode']), ENT_QUOTES) : '';
        if ($com_mode == '') {
            if (is_object($xoopsUser)) {
                $com_mode = $xoopsUser->getVar('umode');
            }
            $com_mode = empty($com_mode) ? $xoopsConfig['com_mode'] : $com_mode;
        }
        $xoopsTpl->assign('comment_mode', $com_mode);
        if (!isset($_GET['com_order'])) {
            if (is_object($xoopsUser)) {
                $com_order = $xoopsUser->getVar('uorder');
            } else {
                $com_order = $xoopsConfig['com_order'];
            }
        } else {
            $com_order = intval($_GET['com_order']);
        }
        if ($com_order != XOOPS_COMMENT_OLD1ST) {
            $xoopsTpl->assign(array(
                'comment_order' => XOOPS_COMMENT_NEW1ST ,
                'order_other' => XOOPS_COMMENT_OLD1ST));
            $com_dborder = 'DESC';
        } else {
            $xoopsTpl->assign(array(
                'comment_order' => XOOPS_COMMENT_OLD1ST ,
                'order_other' => XOOPS_COMMENT_NEW1ST));
            $com_dborder = 'ASC';
        }
        // admins can view all comments and IPs, others can only view approved(active) comments
        if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->getVar('mid'))) {
            $admin_view = true;
        } else {
            $admin_view = false;
        }

        $com_id = isset($_GET['com_id']) ? intval($_GET['com_id']) : 0;
        $com_rootid = isset($_GET['com_rootid']) ? intval($_GET['com_rootid']) : 0;
        $comment_handler = & xoops_gethandler('comment');
        if ($com_mode == 'flat') {
            $comments = $comment_handler->getByItemId($xoopsModule->getVar('mid'), $com_itemid, $com_dborder);
            include_once $GLOBALS['xoops']->path('class/commentrenderer.php');
            $renderer =& XoopsCommentRenderer::instance($xoopsTpl);
            $renderer->setComments($comments);
            $renderer->renderFlatView($admin_view);
        } elseif ($com_mode == 'thread') {
            // RMV-FIX... added extraParam stuff here
            $comment_url = $comment_config['pageName'] . '?';
            if (isset($comment_config['extraParams']) && is_array($comment_config['extraParams'])) {
                $extra_params = '';
                foreach ($comment_config['extraParams'] as $extra_param) {
                    // This page is included in the module hosting page -- param could be from anywhere
                    if (isset(${$extra_param})) {
                        $extra_params .= $extra_param . '=' . ${$extra_param} . '&amp;';
                    } else if (isset($_POST[$extra_param])) {
                        $extra_params .= $extra_param . '=' . $_POST[$extra_param] . '&amp;';
                    } else if (isset($_GET[$extra_param])) {
                        $extra_params .= $extra_param . '=' . $_GET[$extra_param] . '&amp;';
                    } else {
                        $extra_params .= $extra_param . '=&amp;';
                    }
                    //$extra_params .= isset(${$extra_param}) ? $extra_param .'='.${$extra_param}.'&amp;' : $extra_param .'=&amp;';
                }
                $comment_url .= $extra_params;
            }
            $xoopsTpl->assign('comment_url', $comment_url . $comment_config['itemName'] . '=' . $com_itemid . '&amp;com_mode=thread&amp;com_order=' . $com_order);
            if (!empty($com_id) && !empty($com_rootid) && ($com_id != $com_rootid)) {
                // Show specific thread tree
                $comments = $comment_handler->getThread($com_rootid, $com_id);
                if (false != $comments) {
                    include_once $GLOBALS['xoops']->path('class/commentrenderer.php');
                    $renderer = & XoopsCommentRenderer::instance($xoopsTpl);
                    $renderer->setComments($comments);
                    $renderer->renderThreadView($com_id, $admin_view);
                }
            } else {
                // Show all threads
                $top_comments = $comment_handler->getTopComments($xoopsModule->getVar('mid'), $com_itemid, $com_dborder);
                $c_count = count($top_comments);
                if ($c_count > 0) {
                    for($i = 0; $i < $c_count; $i ++) {
                        $comments = $comment_handler->getThread($top_comments[$i]->getVar('com_rootid'), $top_comments[$i]->getVar('com_id'));
                        if (false != $comments) {
                            include_once $GLOBALS['xoops']->path('class/commentrenderer.php');
                            $renderer =& XoopsCommentRenderer::instance($xoopsTpl);
                            $renderer->setComments($comments);
                            $renderer->renderThreadView($top_comments[$i]->getVar('com_id'), $admin_view);
                        }
                        unset($comments);
                    }
                }
            }
        } else {
            // Show all threads
            $top_comments = $comment_handler->getTopComments($xoopsModule->getVar('mid'), $com_itemid, $com_dborder);
            $c_count = count($top_comments);
            if ($c_count > 0) {
                for($i = 0; $i < $c_count; $i ++) {
                    $comments = $comment_handler->getThread($top_comments[$i]->getVar('com_rootid'), $top_comments[$i]->getVar('com_id'));
                    include_once $GLOBALS['xoops']->path('class/commentrenderer.php');
                    $renderer =& XoopsCommentRenderer::instance($xoopsTpl);
                    $renderer->setComments($comments);
                    $renderer->renderNestView($top_comments[$i]->getVar('com_id'), $admin_view);
                }
            }
        }

        // assign comment nav bar
        $navbar = '
<form method="get" action="' . $comment_config['pageName'] . '">
<table width="95%" class="outer" cellspacing="1">
  <tr>
    <td class="even" align="center"><select name="com_mode"><option value="flat"';
        if ($com_mode == 'flat') {
            $navbar .= ' selected="selected"';
        }
        $navbar .= '>' . _FLAT . '</option><option value="thread"';
        if ($com_mode == 'thread' || $com_mode == '') {
            $navbar .= ' selected="selected"';
        }
        $navbar .= '>' . _THREADED . '</option><option value="nest"';
        if ($com_mode == 'nest') {
            $navbar .= ' selected="selected"';
        }
        $navbar .= '>' . _NESTED . '</option></select> <select name="com_order"><option value="' . XOOPS_COMMENT_OLD1ST . '"';
        if ($com_order == XOOPS_COMMENT_OLD1ST) {
            $navbar .= ' selected="selected"';
        }
        $navbar .= '>' . _OLDESTFIRST . '</option><option value="' . XOOPS_COMMENT_NEW1ST . '"';
        if ($com_order == XOOPS_COMMENT_NEW1ST) {
            $navbar .= ' selected="selected"';
        }
        unset($postcomment_link);
        $navbar .= '>' . _NEWESTFIRST . '</option></select><input type="hidden" name="' . $comment_config['itemName'] . '" value="' . $com_itemid . '" /> <input type="submit" value="' . _CM_REFRESH . '" class="formButton" />';
        if (! empty($xoopsModuleConfig['com_anonpost']) || is_object($xoopsUser)) {
            $postcomment_link = 'comment_new.php?com_itemid=' . $com_itemid . '&amp;com_order=' . $com_order . '&amp;com_mode=' . $com_mode;

            $xoopsTpl->assign('anon_canpost', true);
        }
        $link_extra = '';
        if (isset($comment_config['extraParams']) && is_array($comment_config['extraParams'])) {
            foreach ($comment_config['extraParams'] as $extra_param) {
                if (isset(${$extra_param})) {
                    $link_extra .= '&amp;' . $extra_param . '=' . ${$extra_param};
                    $hidden_value = htmlspecialchars(${$extra_param}, ENT_QUOTES);
                    $extra_param_val = ${$extra_param};
                } else if (isset($_POST[$extra_param])) {
                    $extra_param_val = $_POST[$extra_param];
                } else if (isset($_GET[$extra_param])) {
                    $extra_param_val = $_GET[$extra_param];
                }
                if (isset($extra_param_val)) {
                    $link_extra .= '&amp;' . $extra_param . '=' . $extra_param_val;
                    $hidden_value = htmlspecialchars($extra_param_val, ENT_QUOTES);
                    $navbar .= '<input type="hidden" name="' . $extra_param . '" value="' . $hidden_value . '" />';
                }
            }
        }
        if (isset($postcomment_link)) {
            $navbar .= '&nbsp;<input type="button" onclick="self.location.href=\'' . $postcomment_link . '' . $link_extra . '\'" class="formButton" value="' . _CM_POSTCOMMENT . '" />';
        }
        $navbar .= '
    </td>
  </tr>
</table>
</form>';
        $xoopsTpl->assign(array(
            'commentsnav' => $navbar ,
            'editcomment_link' => 'comment_edit.php?com_itemid=' . $com_itemid . '&amp;com_order=' . $com_order . '&amp;com_mode=' . $com_mode . '' . $link_extra ,
            'deletecomment_link' => 'comment_delete.php?com_itemid=' . $com_itemid . '&amp;com_order=' . $com_order . '&amp;com_mode=' . $com_mode . '' . $link_extra ,
            'replycomment_link' => 'comment_reply.php?com_itemid=' . $com_itemid . '&amp;com_order=' . $com_order . '&amp;com_mode=' . $com_mode . '' . $link_extra));

        // assign some lang variables
        $xoopsTpl->assign(array(
            'lang_from' => _CM_FROM ,
            'lang_joined' => _CM_JOINED ,
            'lang_posts' => _CM_POSTS ,
            'lang_poster' => _CM_POSTER ,
            'lang_thread' => _CM_THREAD ,
            'lang_edit' => _EDIT ,
            'lang_delete' => _DELETE ,
            'lang_reply' => _REPLY ,
            'lang_subject' => _CM_REPLIES ,
            'lang_posted' => _CM_POSTED ,
            'lang_updated' => _CM_UPDATED ,
            'lang_notice' => _CM_NOTICE));
    }
}
?>