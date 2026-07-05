
# `forums_singlepost` — Single post template for Cotonti Forums
**Author:** [webitproff](https://github.com/webitproff)  
**Date:** 2026-07-03  
**Copyright:** © webitproff, 2026  
**[Repository](https://github.com/webitproff/forums-singlepost-tpl-cotonti)**  
**License:** BSD 3-Clause License
## **[DEMO](https://abuyfile.com/ru/forums/cotonti/original/extrafields/post509)**

[![Version](https://img.shields.io/badge/version-1.0.0-green.svg)](https://github.com/webitproff/forums-singlepost-tpl-cotonti/releases)
[![Cotonti Compatibility](https://img.shields.io/badge/Cotonti-v.1+-orange.svg)](https://github.com/Cotonti/Cotonti)
[![PHP](https://img.shields.io/badge/PHP-8.4+-purple.svg)](https://www.php.net/releases/8_4_0.php)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-blue.svg)](https://www.mysql.com/)
[![Bootstrap v5.3+](https://img.shields.io/badge/Bootstrap-v5.3+-blueviolet.svg)](https://getbootstrap.com/)
[![License](https://img.shields.io/badge/license-BSD%203--Clause-blue.svg)](https://github.com/webitproff/forums-singlepost-tpl-cotonti/blob/main/LICENSE)

---

## 1. Purpose of the plugin

This plugin allows you to set **an individual template for a single forum post** page (when a specific message is opened via a direct link, rather than the whole topic).  
If a category‑specific template is not available, the simplified shared template `forums.posts.single.tpl` is used automatically.

**Example:**  
A regular topic opens with the template `forums.posts.tpl` (list of all posts).  
When a user clicks on a post number (e.g. `#1`), they land on a single‑post page. The plugin can display this page with a different design – for instance, without unnecessary elements, with a quick reply form, or with a special layout that depends on the forum section.

---

## 2. Plugin file structure

```
plugins/forums_singlepost/
├── forums_singlepost.setup.php           ← plugin registration
├── forums_singlepost.forums.posts.main.php ← executing hook
└── tpl/
    ├── forums.posts.single.tpl           ← default single‑post template (copy to themes/your_theme/modules/forums/)
    └── forums.posts.single.cat_code.tpl  ← example for a specific category (copy to your theme in the same way)
```

**Important:** The files inside the plugin’s `tpl/` folder are **not** used directly. They are just starter templates that you need to **copy into your theme’s folder**.

---

## 3. Why the files are named this way

### `forums_singlepost.setup.php`

The name is composed of the plugin code (`forums_singlepost`) and the suffix `.setup.php`.  
When Cotonti installs or updates extensions, it looks for files with this name to obtain metadata and register the plugin in the database.

### `forums_singlepost.forums.posts.main.php`

The naming follows the rule: `{Code}.{hook_name}.php`.  
- `Code` = `forums_singlepost` (must match the code in `setup.php` and the plugin folder name).  
- Hook name = `forums.posts.main` — the hook point inside the `forums` module (file `posts.php`), placed right before the line that loads the template.

That is why the file is named exactly like this. If the name does not match, the plugin will not be executed.

---

## 4. Why the `[BEGIN_COT_EXT]` block is required

Every plugin PHP file starts with a special comment header:

```php
/* ====================
[BEGIN_COT_EXT]
Code=forums_singlepost
...
[END_COT_EXT]
==================== */
```

**In `setup.php`** it contains metadata:  
- `Code` — unique plugin identifier.  
- `Name`, `Description`, `Version`, `Author` etc. — information shown to the administrator.  
- `Auth_guests`, `Auth_members` — plugin access permissions (who can enable/disable it).  
- `Lock_guests`, `Lock_members` — protected permissions that cannot be changed via the interface.  
- `Requires_modules` — a list of required modules; here `forums`, because the plugin works only with forums.

This information is written to the database during installation and is used by the admin panel.

**In hook files** (e.g. `forums_singlepost.forums.posts.main.php`) the `[BEGIN_COT_EXT]` block tells the system which hook this file is attached to:

```php
Hooks=forums.posts.main
Order=10
```

- `Hooks` — hook name.  
- `Order` — execution priority (the smaller the number, the earlier it runs). The default is `10`.

Without this block the file will be ignored.

---

## 5. How the plugin works

### How the template for a posts page is normally chosen

In `modules/forums/posts.php` there is a line:

```
$mskin = cot_tplfile(array('forums', 'posts', Cot::$structure['forums'][$s]['tpl']));
```

The function `cot_tplfile()` assembles a file name like `forums.posts.{tpl}.tpl`.  
If `{tpl}` is an empty string (the usual situation), `forums.posts.tpl` is loaded.  
If `{tpl} = 'single'`, `forums.posts.single.tpl` will be loaded.

### What the plugin does

The `forums.posts.main` hook runs **before** the template is loaded. The plugin checks whether the current page is a single post (by looking for the `id` or `p` parameters in the URL), and if so, it replaces the value `Cot::$structure['forums'][$s]['tpl']` with the desired one.

```
global $id, $p, $s;
if ((!empty($id) || !empty($p)) && !empty($s) && isset(Cot::$structure['forums'][$s])) {
    $tplName = 'single.' . $s;   // e.g. 'single.e-moped-brands'
    $themePath = Cot::$cfg['themes_dir'] . '/' . Cot::$cfg['defaulttheme'];
    $catTplFile = $themePath . '/modules/forums/forums.posts.' . $tplName . '.tpl';
    if (file_exists($catTplFile)) {
        Cot::$structure['forums'][$s]['tpl'] = $tplName;
    } else {
        Cot::$structure['forums'][$s]['tpl'] = 'single';
    }
}
```

After that, `cot_tplfile()` uses the new `tpl` value and loads the corresponding file from the current theme’s folder.

### Why templates must be placed in the theme, not in the plugin folder

For the `forums` module, the function `cot_tplfile()` **only** searches for templates in theme folders and in the standard module directory (`modules/forums/tpl/`). The folder `plugins/forums_singlepost/tpl/` is **not** included in the search paths for the `forums` module. Therefore you must copy the ready‑to‑use templates into:

```
themes/your_theme/modules/forums/
```

That is where the engine will find them.

### Why the file name must include the category code

The plugin generates `tplName = 'single.CATEGORY_CODE'`.  
For a category with the code `e-moped-brands`, the requested file will be `forums.posts.single.e-moped-brands.tpl`.  
If that file does not exist, it falls back to `forums.posts.single.tpl`.

This way you can create a unique design for the single‑post page of each forum section. Without the category code in the file name it would be impossible to differentiate the templates.

---

## 6. How to use the plugin

### Step 1. Installation

1. Copy the `forums_singlepost` folder into the `plugins/` directory of your site.
2. Go to the admin panel → **Extensions**, find `forums_singlepost` and click **Install**.
3. Make sure the plugin is enabled (green indicator).

### Step 2. Preparing the templates

Copy the default single‑post template from `plugins/forums_singlepost/tpl/forums.posts.single.tpl` into your theme’s folder:

```
themes/your_theme/modules/forums/forums.posts.single.tpl
```

Now, when you click on a post number in any topic, this simplified template will open instead of the standard post list.

### Step 3. Creating a category‑specific template (optional)

If you want to use a special template for a particular category (e.g. `e-moped-brands`):

1. Create a file in the same folder:

```
themes/your_theme/modules/forums/forums.posts.single.e-moped-brands.tpl
```

2. Fill it with the desired markup.

Now single posts from that category will load this template. If the file does not exist, `forums.posts.single.tpl` will be used instead.

### Use‑case examples

- **Clean reading page:** only the post text, without avatars, control buttons or pagination. Suitable for publications where content is the most important.
- **Page with a quick reply form:** as in the second example (`e-moped-brands`) – a single post immediately accompanied by a reply field. Convenient for support sections or product discussions.
- **Post landing page:** you can add banners, call‑to‑action buttons, a video player – whatever is needed for a particular section.

---

## 7. What will not work if you copy everything from `forums.posts.tpl`

You can completely copy the content of the standard `forums.posts.tpl` into `forums.posts.single.xxx.tpl`, but keep in mind:

- **Pagination** will not appear (since there is only one post on the page, the `{PAGINATION}` block will be empty).
- **The “Delete” button for the specific post** will not show up – the logic in `posts.php` forbids deleting the first (and only) post of a topic. Instead, an admin must use the topic management buttons.
- **The post anchor link** (`{FORUMS_POSTS_ROW_IDURL}`) will point to the same page, which may cause an unnecessary reload.

All other elements of the main template (topic title, breadcrumbs, admin buttons, reply form, attachments, plugins, etc.) will work exactly as they do when viewing the whole topic.

---

## 8. Summary

The `forums_singlepost` plugin is a lightweight and flexible way to change the appearance of a single forum post page without modifying the core.  
All the work boils down to placing templates in your theme’s folder. The plugin requires no configuration, creates no database tables, and does not affect other pages.

___


# Плагин `forums_singlepost` — шаблон одиночного поста форума Cotonti
**Автор:** [webitproff](https://github.com/webitproff)  
**Дата:** 2026-07-03  
**Copyright:** © webitproff, 2026  
**[Репозиторий](https://github.com/webitproff/forums-singlepost-tpl-cotonti)**
**Лицензия:** BSD 3-Clause License

---

## 1. Назначение плагина

Плагин позволяет для каждого раздела форума задать **индивидуальный шаблон страницы одиночного поста** (когда открывается конкретное сообщение по прямой ссылке, а не вся тема целиком).  
Если специальный шаблон для категории не задан, автоматически используется общий упрощённый шаблон `forums.posts.single.tpl`.

**Пример:**  
Обычная тема открывается с шаблоном `forums.posts.tpl` (список всех постов).  
Когда пользователь кликает по номеру поста `#1`, он попадает на страницу одного поста. Плагин может показать эту страницу с другим дизайном, например, без лишних элементов, с формой быстрого ответа или с особым оформлением, зависящим от категории.

---

## 2. Структура файлов плагина

```
plugins/forums_singlepost/
├── forums_singlepost.setup.php           ← регистрация плагина
├── forums_singlepost.forums.posts.main.php ← исполняемый хук
└── tpl/
    ├── forums.posts.single.tpl           ← заготовка дефолтного шаблона (скопируйте в тему themes/ваша_тема/modules/forums/здесь лежит файл **forums.posts.single.tpl**)
    └── forums.posts.single.cat_code.tpl  ← пример для конкретной категории (скопируйте в тему - точно также)
```

**Важно:** Файлы из папки `tpl/` плагина **не** загружаются напрямую. Это лишь заготовки, которые вы должны **скопировать в папку вашей темы**.

---

## 3. Почему файлы называются именно так

### `forums_singlepost.setup.php`

Имя состоит из кода плагина (`forums_singlepost`) и суффикса `.setup.php`.  
Система Cotonti при установке/обновлении расширений ищет файлы с таким именем, чтобы получить метаданные и зарегистрировать плагин в базе данных.

### `forums_singlepost.forums.posts.main.php`

Здесь используется правило: `{Code}.{имя_хука}.php`.  
- `Code` = `forums_singlepost` (должен совпадать с кодом в `setup.php` и названием папки).  
- Имя хука = `forums.posts.main` — точка в коде модуля `forums` (файл `posts.php`), сразу перед строкой загрузки шаблона.

Именно поэтому файл называется так. Если имя не совпадёт, плагин не выполнится.

---

## 4. Зачем нужен блок `[BEGIN_COT_EXT]`

В начале каждого PHP‑файла плагина находится специальный заголовок:

```php
/* ====================
[BEGIN_COT_EXT]
Code=forums_singlepost
...
[END_COT_EXT]
==================== */
```

**В `setup.php`** он содержит метаданные:  
- `Code` — уникальный код плагина.  
- `Name`, `Description`, `Version`, `Author` и т.д. — информация для администратора.  
- `Auth_guests`, `Auth_members` — права доступа к плагину (кто может включать/выключать).  
- `Lock_guests`, `Lock_members` — защищённые права.  
- `Requires_modules` — перечень обязательных модулей, без которых плагин не заработает (здесь `forums`).

Эта информация записывается в БД при установке и используется админ‑панелью.

**В файлах хуков** (например, `forums_singlepost.forums.posts.main.php`) блок `[BEGIN_COT_EXT]` указывает системе, к какому хуку привязан данный файл:

```php
Hooks=forums.posts.main
Order=10
```

- `Hooks` — имя хука.  
- `Order` — приоритет выполнения (чем меньше число, тем раньше сработает). Значение `10` используется по умолчанию.

Без этого блока файл будет проигнорирован.

---

## 5. Механизм работы плагина

### Как обычно выбирается шаблон для страницы с постами

В файле `modules/forums/posts.php` есть строка:

```
$mskin = cot_tplfile(array('forums', 'posts', Cot::$structure['forums'][$s]['tpl']));
```

Функция `cot_tplfile()` собирает имя файла так: `forums.posts.{$tpl}.tpl`.  
Если `$tpl` пустая строка (как обычно), загружается `forums.posts.tpl`.  
Если `$tpl = 'single'`, будет загружен `forums.posts.single.tpl`.

### Что делает плагин

Хук `forums.posts.main` выполняется **до** загрузки шаблона. Плагин проверяет, является ли текущая страница одиночным постом (по наличию параметров `id` или `p` в URL), и если да — подменяет значение `Cot::$structure['forums'][$s]['tpl']` на нужное.

```
global $id, $p, $s;
if ((!empty($id) || !empty($p)) && !empty($s) && isset(Cot::$structure['forums'][$s])) {
    $tplName = 'single.' . $s;   // например, 'single.e-moped-brands'
    $themePath = Cot::$cfg['themes_dir'] . '/' . Cot::$cfg['defaulttheme'];
    $catTplFile = $themePath . '/modules/forums/forums.posts.' . $tplName . '.tpl';
    if (file_exists($catTplFile)) {
        Cot::$structure['forums'][$s]['tpl'] = $tplName;
    } else {
        Cot::$structure['forums'][$s]['tpl'] = 'single';
    }
}
```

После этого `cot_tplfile()` срабатывает уже с новым значением `tpl` и загружает соответствующий файл из папки текущей темы.

### Почему шаблоны должны лежать в теме, а не в папке плагина

Функция `cot_tplfile()` для модуля `forums` ищет шаблоны **только в папках темы** и в стандартной директории модуля (`modules/forums/tpl/`). Папка `plugins/forums_singlepost/tpl/` **не** входит в пути поиска для модуля `forums`. Поэтому готовые шаблоны необходимо скопировать в:

```
themes/ваша_тема/modules/forums/
```

Именно там движок сможет их найти.

### Почему в имени файла должен быть код категории

Плагин формирует `tplName = 'single.КОД_КАТЕГОРИИ'`.  
Для категории с кодом `e-moped-brands` будет запрошен файл `forums.posts.single.e-moped-brands.tpl`.  
Если такого файла нет, откат идёт к `forums.posts.single.tpl`.

Таким образом вы можете для каждого раздела форума создать свой уникальный дизайн страницы одиночного поста. Без кода категории в имени файла разделить шаблоны было бы невозможно.

---

## 6. Как использовать плагин

### Шаг 1. Установка

1. Скопируйте папку `forums_singlepost` в `plugins/` вашего сайта.
2. Зайдите в админку → **Расширения**, найдите `forums_singlepost` и нажмите **Установить**.
3. Убедитесь, что плагин включён (зелёный индикатор).

### Шаг 2. Подготовка шаблонов

Скопируйте дефолтный single-шаблон из `plugins/forums_singlepost/tpl/forums.posts.single.tpl` в папку вашей темы:

```
themes/ваша_тема/modules/forums/forums.posts.single.tpl
```

Теперь при клике на номер поста в любой теме будет открываться этот упрощённый шаблон, а не стандартный список постов.

### Шаг 3. Создание категорийного шаблона (по желанию)

Если вы хотите для конкретной категории (например, `e-moped-brands`) использовать особый шаблон:

1. Создайте в той же папке файл:

```
themes/ваша_тема/modules/forums/forums.posts.single.e-moped-brands.tpl
```

2. Наполните его нужной вёрсткой.

Теперь при открытии одиночного поста из этой категории будет загружен именно он. Если файла нет, будет использован `forums.posts.single.tpl`.

### Примеры сценариев

- **Чистая страница для чтения:** только текст поста, без аватаров, кнопок управления, пагинации. Подходит для публикаций, где важнее контент.
- **Страница с формой быстрого ответа:** как во втором примере (`e-moped-brands`) — одиночный пост сразу с полем для ответа. Удобно для разделов техподдержки или обсуждений товаров.
- **Лендинг поста:** можно добавить баннеры, кнопки призыва к действию, видеоплеер – всё, что нужно для конкретного раздела.

---

## 7. Что не будет работать, если скопировать всё из `forums.posts.tpl`

Вы можете полностью скопировать содержимое штатного `forums.posts.tpl` в `forums.posts.single.xxx.tpl`, но при этом:

- **Пагинация** не отобразится (поскольку на странице только один пост, блок `{PAGINATION}` будет пуст).
- **Кнопка «Удалить» для конкретного поста** не появится — в коде `posts.php` стоит условие, что удалять первый (и единственный) пост темы нельзя. Вместо этого админ должен использовать кнопки управления темой.
- **Ссылка на якорь поста** (`{FORUMS_POSTS_ROW_IDURL}`) будет вести на ту же самую страницу, что может вызвать лишнюю перезагрузку.

В остальном все элементы главного шаблона (заголовок темы, хлебные крошки, админские кнопки, форма ответа, вложения, плагины и т.д.) будут работать точно так же, как и при просмотре всей темы.

---

## 8. Итог

Плагин `forums_singlepost` — это лёгкий и гибкий способ изменить отображение страницы одного поста форума без правки ядра.  
Вся работа сводится к размещению шаблонов в папке темы. Плагин не требует настройки, не создаёт таблиц в БД и не влияет на другие страницы.

