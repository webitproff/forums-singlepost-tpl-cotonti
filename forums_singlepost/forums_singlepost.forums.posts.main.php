<?php
/* ====================
[BEGIN_COT_EXT]
Hooks=forums.posts.main
Order=10
[END_COT_EXT]
==================== */
/**
 * forums_singlepost.forums.posts.main.php — Loads category-specific template for single forum posts (e.g. forums.posts.single.e-moped-brands.tpl)
 * Register data in $db_core and $db_config. Setup & Config File for the Plugin
 * Плагин Forums Single Post Template для Cotonti CMF (PHP 8.4+, MySQL 8.0+)
 * Версия: 1.0.0
 * Дата: 2026-07-03
 * Имя файла: plugins/forums_singlepost/forums_singlepost.forums.posts.main.php
 *
 * Назначение плагина: загружает индивидуальный шаблон для одиночного поста форума.
 * Шаблон выбирается по коду категории (например, forums.posts.single.код_категории.tpl)
 * или, если такого нет, используется общий forums.posts.single.tpl.
 *
 * Источник: https://github.com/webitproff/ 
 * 
 *
 * @package forums_singlepost
 * @version 1.0.0
 * @author webitproff
 * @copyright Copyright (c) webitproff 2026 | https://github.com/webitproff
 * @license BSD
 */

defined('COT_CODE') or die('Wrong URL.');

global $id, $p, $s;

if ((!empty($id) || !empty($p)) && !empty($s) && isset(Cot::$structure['forums'][$s])) {
    $tplName = 'single.' . $s;
    // Проверяем, есть ли файл с кодом категории в теме
    $themePath = Cot::$cfg['themes_dir'] . '/' . Cot::$cfg['defaulttheme'];
    $catTplFile = $themePath . '/modules/forums/forums.posts.' . $tplName . '.tpl';
    if (file_exists($catTplFile)) {
        Cot::$structure['forums'][$s]['tpl'] = $tplName;
    } else {
        Cot::$structure['forums'][$s]['tpl'] = 'single';
    }
}

/* // не трогать
if (!empty($id) && !empty($s) && isset(Cot::$structure['forums'][$s])) {
    Cot::$structure['forums'][$s]['tpl'] = 'single';
}
*/