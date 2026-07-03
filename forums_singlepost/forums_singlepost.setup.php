<?php
/* ====================
[BEGIN_COT_EXT]
Code=forums_singlepost
Name=Single Post Template for Forums (per category)
Description=Loads category-specific template for single forum posts (e.g. forums.posts.single.e-moped-brands.tpl). Falls back to forums.posts.single.tpl if category-specific file not found.
Version=1.0.0
Date=2026-07-03
Author=webitproff
Copyright=&copy; webitproff 2026
Notes=Requires forums module. Place your templates as forums.posts.single.{category_code}.tpl or forums.posts.single.tpl in your theme/modules/forums/.
Auth_guests=R
Lock_guests=12345A
Auth_members=R
Lock_members=
Requires_modules=forums
[END_COT_EXT]
==================== */

/**
 * Разбор полей заголовка:
 *
 * Code:               Уникальный код плагина — forums_singlepost.
 * Name:               Человекочитаемое название.
 * Description:        Описание того, что делает плагин.
 * Version:            Версия плагина (1.0.0).
 * Date:               Дата релиза текущей версии.
 * Author:             Автор плагина.
 * Copyright:          Копирайт.
 * Notes:              Примечания, требования, инструкции.
 * Auth_guests:        Права для гостей — R (чтение).
 * Lock_guests:        Защищённые от изменения гостями права — 12345A (даже админ не может изменить в админке).
 * Auth_members:       Права для зарегистрированных пользователей — RW (чтение и запись).
 * Lock_members:       Защищённые от изменения участниками права (пусто).
 * Requires_modules:   Обязательные модули — forums (форум).
 *
 * Плагин не создаёт собственных таблиц, поэтому SQL-файлы install/uninstall не требуются.
 * Языковые файлы не нужны, так как интерфейс отсутствует.
 * Настроек нет, поэтому секция `BEGIN_COT_EXT_CONFIG` не используется.
 */

defined('COT_CODE') or die('Wrong URL');

/**
 * forums_singlepost.setup.php — регистрация плагина в базе, метаданные, права и зависимости
 * Register data in $db_core and $db_config. Setup & Config File for the Plugin
 * Плагин Forums Single Post Template для Cotonti CMF (PHP 8.4+, MySQL 8.0+)
 * Версия: 1.0.0
 * Дата: 2026-07-03
 * Имя файла: forums_singlepost.setup.php
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
 

/* 
 
forums_singlepost/
├── forums_singlepost.setup.php // Заголовок плагина и регистрация
├── forums_singlepost.forums.posts.main.php // Хук: подмена шаблона для одиночного поста
└── tpl/
	├── forums.posts.single.cat_code.tpl // шаблон одиночного поста конкретной категории, где cat_code это ее код в поле `structure_code` таблицы `cot_structure` 
	└── forums.posts.single.tpl // Дефолтный шаблон одиночного поста (скопируйте в тему)
  */