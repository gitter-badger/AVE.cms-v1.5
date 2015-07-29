<?php

/**
 * AVE.cms
 *
 * Класс, предназначенный для работы шаблонами и пунктами меню навигаций
 *
 * @package AVE.cms
 * @version 3.x
 * @filesource
 * @copyright © 2007-2014 AVE.cms, http://www.ave-cms.ru
 *
 */

class AVE_Navigation
{

/**
 *	Свойства класса
 */


/**
 *	Внутренние методы класса
 */

	/**
	 * Метод, предназначенный для удаления запрещённых символов
	 * и преобразование специальных символов в HTML сущности
	 *
	 * @param string $text
	 * @return string
	 */
	function _replace_wildcode($text)
	{
//		$text = preg_replace('#[^(\w)|(\x7F-\xFF)|(\s)|\/-]#', '', $text);
//		$text = preg_replace('#[^(\w)|(\?)|(-)|(\x7F-\xFF)|(\s)|\/-]#', '', $text);
//		$text = htmlspecialchars($text, ENT_QUOTES);
		return $text;
	}

/**
 *	Внутренние методы
 */

	/**
	 * Метод, предназначенный для вывода списка всех существующих меню навигаций в Паели управления
	 *
	 */
	function navigationList()
	{
		global $AVE_DB, $AVE_Template;

		$mod_navis = array();

		// Выполняем запрос к БД на получение списка всех меню навигаций
		$sql = $AVE_DB->Query("
			SELECT
				id,
				navi_titel
			FROM " . PREFIX . "_navigation
			ORDER BY id ASC
		");

		// Формируем данные в массив
		while ($row = $sql->fetchrow())
		{
			array_push($mod_navis, $row);
		}
		$sql->Close();

		// Передаем данные в шаблон для вывода и отображаем страницу со списком меню
		$AVE_Template->assign('mod_navis', $mod_navis);
		$AVE_Template->assign('content', $AVE_Template->fetch('navigation/overview.tpl'));
	}



	/**
	 * Метод, предназначенный для добавления нового меню
	 *
	 */
	function navigationNew()
	{
		global $AVE_DB, $AVE_Template, $AVE_User;

		// Определяем действие пользователя
		switch($_REQUEST['sub'])
		{
			// Если действие не определено, отображаем чистую форму для создания шаблона навигации
			case '':
				// Получаем список всех Групп пользователей
				$row->AvGroups = $AVE_User->userGroupListGet();

				// Передаем данные в шаблон и отображаем страницу для добавления нового шаблона меню
				$AVE_Template->assign('row', $row);
				$AVE_Template->assign('formaction', 'index.php?do=navigation&amp;action=new&amp;sub=save&amp;cp=' . SESSION);
				$AVE_Template->assign('content', $AVE_Template->fetch('navigation/template.tpl'));
				break;


			// Если пользователь нажал на кнопку Добавить (Сохранить)
			case 'save':

				// Определяем название меню навигации
				$navi_titel   = (empty($_POST['navi_titel']))   ? 'title' : $_POST['navi_titel'];

				// Определяем шаблон оформления 1-го уровня ссылок в меню. Если шаблон не указан пользователем,тогда
				// используем вариант "по умолчанию"
				$navi_level1  = (empty($_POST['navi_level1']))  ? "<a target=\"[tag:target]\" href=\"[tag:link]\">[tag:linkname]</a>" : $_POST['navi_level1'];
				$navi_level1active = (empty($_POST['navi_level1active'])) ? "<a target=\"[tag:target]\" href=\"[tag:link]\" class=\"first_active\">[tag:linkname]</a>" : $_POST['navi_level1active'];

				// Выполняем запрос к БД на добавление нового меню
				$AVE_DB->Query("
					INSERT
					INTO " . PREFIX . "_navigation
					SET
						id	   = '',
						navi_titel	= '" . $navi_titel . "',
						navi_level1   = '" . $navi_level1 . "',
						navi_level1active  = '" . $navi_level1active . "',
						navi_level2   = '" . $_POST['navi_level2'] . "',
						navi_level2active  = '" . $_POST['navi_level2active'] . "',
						navi_level3   = '" . $_POST['navi_level3'] . "',
						navi_level3active  = '" . $_POST['navi_level3active'] . "',
						navi_level1begin = '" . $_POST['navi_level1begin'] . "',
						navi_level2begin = '" . $_POST['navi_level2begin'] . "',
						navi_level3begin = '" . $_POST['navi_level3begin'] . "',
						navi_level1end = '" . $_POST['navi_level1end'] . "',
						navi_level2end = '" . $_POST['navi_level2end'] . "',
						navi_level3end = '" . $_POST['navi_level3end'] . "',
						navi_begin	  = '" . $_POST['navi_begin'] . "',
						navi_end	 = '" . $_POST['navi_end'] . "',
						navi_user_group  = '" . (empty($_REQUEST['navi_user_group']) ? '' : implode(',', $_REQUEST['navi_user_group'])) . "',
						navi_expand_ext = '" . $_POST['navi_expand_ext'] . "'
				");

				// Сохраняем системное сообщение в журнал
				reportLog($AVE_Template->get_config_vars('NAVI_REPORT_NEW') . " (" . stripslashes($navi_titel) . ")");

				// Выполянем переход к списку меню навигаций
				header('Location:index.php?do=navigation&cp=' . SESSION);
				break;
		}
	}



	/**
	 * Метод, предназначенный для редактирования шаблона навигации
	 *
	 * @param int $navigation_id идентификатор меню навигации
	 */
	function navigationEdit($navigation_id)
	{
		global $AVE_DB, $AVE_Template, $AVE_User;

		// Получаем id меню
		$navigation_id = (int)$navigation_id;

		// Определяем действие пользователя
		switch ($_REQUEST['sub'])
		{
			// Если действие не определено, отображаем форму с данными для редактирования
			case '':

				// Выполняем запрос к БД и получаем всю информацию о данном меню
				$row = $AVE_DB->Query("
					SELECT *
					FROM " . PREFIX . "_navigation
					WHERE id = '" . $navigation_id . "'
				")->fetchrow();

				// Формируем список групп пользователей
				$row->navi_user_group = explode(',', $row->navi_user_group);
				$row->AvGroups = $AVE_User->userGroupListGet();

				// Формируем ряд переменных для использования в шаблоне и отображаем форм с данными для редактирования
				$AVE_Template->assign('nav', $row);
				$AVE_Template->assign('formaction', 'index.php?do=navigation&action=templates&sub=save&id=' . $navigation_id . '&cp=' . SESSION);
				$AVE_Template->assign('content', $AVE_Template->fetch('navigation/template.tpl'));
				break;

			// Если пользователь нажал на кнопку Сохранить изменения
			case 'save':

				// Выполняем запрос к БД и обновляем информацию в таблице для данного меню
				$sql = $AVE_DB->Query("
					UPDATE " . PREFIX . "_navigation
					SET
						navi_titel	= '" . $_POST['navi_titel'] . "',
						navi_level1   = '" . $_POST['navi_level1'] . "',
						navi_level1active  = '" . $_POST['navi_level1active'] . "',
						navi_level2   = '" . $_POST['navi_level2'] . "',
						navi_level2active  = '" . $_POST['navi_level2active'] . "',
						navi_level3   = '" . $_POST['navi_level3'] . "',
						navi_level3active  = '" . $_POST['navi_level3active'] . "',
						navi_level1begin = '" . $_POST['navi_level1begin'] . "',
						navi_level1end = '" . $_POST['navi_level1end'] . "',
						navi_level2begin = '" . $_POST['navi_level2begin'] . "',
						navi_level2end = '" . $_POST['navi_level2end'] . "',
						navi_level3begin = '" . $_POST['navi_level3begin'] . "',
						navi_level3end = '" . $_POST['navi_level3end'] . "',
						navi_begin	  = '" . $_POST['navi_begin'] . "',
						navi_end	 = '" . $_POST['navi_end'] . "',
						navi_user_group  = '" . (empty($_REQUEST['navi_user_group']) ? '' : implode(',', $_REQUEST['navi_user_group'])) . "',
						navi_expand_ext = '" . $_POST['navi_expand_ext'] . "'
					WHERE
						id = '" . $navigation_id . "'
				");

				if ($sql->_result === false) {
					$message = $AVE_Template->get_config_vars('NAVI_REPORT_SAVED_ERR');
					$header = $AVE_Template->get_config_vars('NAVI_REPORT_ERROR');
					$theme = 'error';
				}else{
					$message = $AVE_Template->get_config_vars('NAVI_REPORT_SAVED');
					$header = $AVE_Template->get_config_vars('NAVI_REPORT_SUCCESS');
					$theme = 'accept';
					reportLog($AVE_Template->get_config_vars('NAVI_REPORT_EDIT') . " (" . stripslashes($_POST['navi_titel']) . ')');
				}

				if (isset($_REQUEST['ajax']) && $_REQUEST['ajax'] = '1') {
					echo json_encode(array('message' => $message, 'header' => $header, 'theme' => $theme));
				} else {
					$AVE_Template->assign('message', $message);
					header('Location:index.php?do=navigation&cp=' . SESSION);
				}
				exit;
		}
	}



	/**
	 * Метод, предназначенный для копирования шаблона меню
	 *
	 * @param int $navigation_id идентификатор меню навигации источника
	 */
	function navigationCopy($navigation_id)
	{
		global $AVE_DB, $AVE_Template;


		// Если в запросе указано числовое значение id меню
		if (is_numeric($navigation_id))
		{
			// Выполняем запрос к БД на получение информации о копируемом меню
			$row = $AVE_DB->Query("
				SELECT *
				FROM " . PREFIX . "_navigation
				WHERE id = '" . $navigation_id . "'
			")->fetchrow();


			// Если данные получены, тогда
			if ($row)
			{

				// Выполняем запрос к БД на добавление нового меню и сохраняем информацию с учетом данных,
				// полученных в предыдущем запросе к БД
				$AVE_DB->Query("
					INSERT
					INTO " . PREFIX . "_navigation
					SET
						id	   = '',
						navi_titel	= '" . addslashes((empty($_REQUEST['navi_titel']) ? $row->navi_titel : $_REQUEST['navi_titel'])) . "',
						navi_level1   = '" . addslashes($row->navi_level1) . "',
						navi_level1active  = '" . addslashes($row->navi_level1active) . "',
						navi_level2   = '" . addslashes($row->navi_level2) . "',
						navi_level2active  = '" . addslashes($row->navi_level2active) . "',
						navi_level3   = '" . addslashes($row->navi_level3) . "',
						navi_level3active  = '" . addslashes($row->navi_level3active) . "',
						navi_begin	  = '" . addslashes($row->navi_begin) . "',
						navi_end	 = '" . addslashes($row->navi_end) . "',
						navi_level1begin = '" . addslashes($row->navi_level1begin) . "',
						navi_level2begin = '" . addslashes($row->navi_level2begin) . "',
						navi_level3begin = '" . addslashes($row->navi_level3begin) . "',
						navi_level1end = '" . addslashes($row->navi_level1end) . "',
						navi_level2end = '" . addslashes($row->navi_level2end) . "',
						navi_level3end = '" . addslashes($row->navi_level3end) . "',
						navi_user_group  = '" . addslashes($row->navi_user_group) . "',
						navi_expand_ext = '" . $row->navi_expand_ext . "'
				");


				// Сохраняем системное сообщение в журнал
				reportLog($AVE_Template->get_config_vars('NAVI_REPORT_COPY') . " (" . (empty($_REQUEST['navi_titel']) ? $row->navi_titel : $_REQUEST['navi_titel']) . ")");
			}
		}


		// Выполянем переход к списку меню навигаций
		header('Location:index.php?do=navigation&cp=' . SESSION);
	}



	/**
	 * Метод, предназначенный для удаления меню навигации и всех пунктов относящихся к нему
	 *
	 * @param int $navigation_id идентификатор меню навигации
	 */
	function navigationDelete($navigation_id)
	{
		global $AVE_DB, $AVE_Template;

		// Если id меню числовой и это не первое меню (id не 1)
		if (is_numeric($navigation_id) && $navigation_id != 1)
		{

			 $sql= $AVE_DB->Query("
				SELECT *
				FROM " . PREFIX . "_navigation
				WHERE id = '" . $navigation_id . "'
			")->FetchRow();

			// Выполняем запрос к БД на удаление общей информации и шаблона оформления меню
			$AVE_DB->Query("DELETE FROM " . PREFIX . "_navigation WHERE id = '" . $navigation_id . "'");
			// Выполняем запрос к БД на удаление всех пунктов для данного меню
			$AVE_DB->Query("DELETE FROM " . PREFIX . "_navigation_items WHERE navi_id = '" . $navigation_id . "'");

			// Сохраняем системное сообщение в журнал
			reportLog($AVE_Template->get_config_vars('NAVI_REPORT_DEL') . " (" . stripslashes($sql->navi_titel) . ") (id: $navigation_id)");
		}

		// Выполянем переход к списку меню навигаций
		header('Location:index.php?do=navigation&cp=' . SESSION);
	}



	/**
	 * Метод, предназначенный для получения списка всех пунктов у всех меню навигации
	 *
	 */
	function navigationAllItemList()
	{
		global $AVE_DB, $AVE_Template;

		$navigation_item = array();
		$navigations = array();

		// Выполняем запрос к БД на получение id и названия меню навигации
		$sql = $AVE_DB->Query("
			SELECT
				id,
				navi_titel
			FROM " . PREFIX . "_navigation
		");


		// Циклически обрабатываем полученные данные
		while ($navigation = $sql->fetchrow())
		{
			// Выполняем запрос к БД на получение всех пунктов для каждого меню.
			// Фактически получаем пункты первого уровня.
			$sql_navis = $AVE_DB->Query("
				SELECT *
				FROM " . PREFIX . "_navigation_items
				WHERE navi_id = '" . $navigation->id . "'
				AND parent_id = 0
				AND navi_item_level = 1
				ORDER BY navi_item_position ASC
			");

			// Циклически обрабатываем полученые данные
			while ($row_1 = $sql_navis->fetchrow())
			{
				$navigation_item_2 = array();

				// Выполняем запрос к БД на получение подпунктов меню.
				// Фактически получаем пункты второго уровня.
				$sql_2 = $AVE_DB->Query("
					SELECT *
					FROM " . PREFIX . "_navigation_items
					WHERE navi_id = '" . $navigation->id . "'
					AND parent_id = '" . $row_1->Id . "'
					AND navi_item_level = 2
					ORDER BY navi_item_position ASC
				");

				// Циклически обрабатываем полученые данные
				while ($row_2 = $sql_2->fetchrow())
				{
					$navigation_item_3 = array();

					// Выполняем запрос к БД на получение подпунктов меню.
					// Фактически получаем пункты третьего уровня.
					$sql_3 = $AVE_DB->Query("
						SELECT *
						FROM " . PREFIX . "_navigation_items
						WHERE navi_id = '" . $navigation->id . "'
						AND parent_id = '" . $row_2->Id . "'
						AND navi_item_level = 3
						ORDER BY navi_item_position ASC
					");

					while ($row_3 = $sql_3->fetchrow())
					{
						array_push($navigation_item_3, $row_3);
					}

					$row_2->ebene_3 = $navigation_item_3;
					array_push($navigation_item_2, $row_2);
				}

				$row_1->ebene_2 = $navigation_item_2;
				$row_1->RubId = $navigation->id;
				$row_1->Rubname = $navigation->navi_titel;
				array_push($navigation_item, $row_1);
			}
			array_push($navigations, $navigation);
		}

		// Передаем полученные данные в шаблон для вывода
		$AVE_Template->assign('navis', $navigations);
		$AVE_Template->assign('navi_items', $navigation_item);
	}


	/**
	 * Метод, предназначенный для вывода пунктов меню навигации в Панели управления
	 *
	 * @param int $id идентификатор меню навигации
	 */
	function navigationItemList($id)
	{
		global $AVE_DB, $AVE_Template;

		$id = (int)$id;

		if(isset($_REQUEST['save']) && $_REQUEST['save'] == 'pos')
		{
			$result = $_REQUEST["item"];
			$i = 0;
			foreach($result as $value) {
				$AVE_DB->Query("UPDATE " . PREFIX . "_navigation_items SET navi_item_position = '".$i."' WHERE Id = '".$value."'");
				$i++;
			}

			if (isAjax()){
				$message = $AVE_Template->get_config_vars('NAVI_SORTED');
				$header = $AVE_Template->get_config_vars('NAVI_REPORT_SUCCESS');
				$theme = 'accept';
				echo json_encode(array('message' => $message, 'header' => $header, 'theme' => $theme));
			}

			exit;
		}

		$navigation_item = array();

		// Выполняем запрос к БД и получаем список пунктов первого уровня для выбранного меню
		$sql_navis = $AVE_DB->Query("
			SELECT *
			FROM " . PREFIX . "_navigation_items
			WHERE navi_id = '" . $id . "'
			AND parent_id = 0
			AND navi_item_level = 1
			ORDER BY navi_item_position ASC
		");

		while ($row_1 = $sql_navis->fetchrow())
		{
			$navigation_item_2 = array();
			// Выполняем запрос к БД и получаем список пунктов второго уровня для выбранного меню
			$sql_2 = $AVE_DB->Query("
				SELECT *
				FROM " . PREFIX . "_navigation_items
				WHERE navi_id = '" . $id . "'
				AND parent_id = '" . $row_1->Id . "'
				AND navi_item_level = 2
				ORDER BY navi_item_position ASC
			");
			while ($row_2 = $sql_2->fetchrow())
			{
				$navigation_item_3 = array();

				// Выполняем запрос к БД и получаем список пунктов третьего уровня для выбранного меню
				$sql_3 = $AVE_DB->Query("
					SELECT *
					FROM " . PREFIX . "_navigation_items
					WHERE navi_id = '" . $id . "'
					AND parent_id = '" . $row_2->Id . "'
					AND navi_item_level = 3
					ORDER BY navi_item_position ASC
				");
				while ($row_3 = $sql_3->fetchrow())
				{
					array_push($navigation_item_3, $row_3);
				}

				$row_2->ebene_3 = $navigation_item_3;
				array_push($navigation_item_2, $row_2);
			}
			$row_1->ebene_2 = $navigation_item_2;
			array_push($navigation_item, $row_1);
		}

		// Выполняем запрос к БД и получаем название меню навигации
		$sql = $AVE_DB->Query("
			SELECT navi_titel
			FROM " . PREFIX . "_navigation
			WHERE id = '" . $id . "'
		");
		$row = $sql->fetchrow();

		// Передаем данные в шаблон для вывода и отображаем страницу с пунктами меню
		$AVE_Template->assign('NavigatonName', $row->navi_titel);
		$AVE_Template->assign('entries', $navigation_item);
		$AVE_Template->assign('content', $AVE_Template->fetch('navigation/entries.tpl'));
	}



	/**
	 * Метод, предназначенный для управления пунктами меню навигации в Панели управления
	 *
	 * @param int $id идентификатор меню навигации
	 */
	function navigationItemEdit($nav_id)
	{
		global $AVE_DB, $AVE_Template;

		$nav_id = (int)$nav_id;

		// Циклически обрабатываем все параметры, пришедшие методом POST при сохранении изменений
		foreach ($_POST['title'] as $id => $title)
		{
			// Если название пункта меню не пустое
			if (!empty($title))
			{
				$id = (int)$id;

				$_POST['navi_item_link'][$id] = (strpos($_POST['navi_item_link'][$id], 'javascript') !== false)
					? str_replace(array(' ', '%'), '-', $_POST['navi_item_link'][$id])
					: $_POST['navi_item_link'][$id];

				// Определяем флаг статуса пункта меню (активен/неактивен)
				$navi_item_status = (empty($_POST['navi_item_status'][$id]) || empty($_POST['navi_item_link'][$id])) ? 0 : 1;


				$link_url = '';
				$matches = array();

				// документы	
				preg_match('/^index\.php\?id=(\d+)$/', trim($_POST['navi_item_link'][$id]), $matches);
				if (isset($matches[1]))
				{
					$link_url = $AVE_DB->Query("
						SELECT document_alias
						FROM " . PREFIX . "_documents
						WHERE id = '" . $matches[1] . "'
					")->GetCell();
				}

				$position = (isset($_POST['navi_item_position'][$id])) ? "navi_item_position  = '" . intval($_POST['navi_item_position'][$id]) . "'," : '';

				$AVE_DB->Query("
					UPDATE " . PREFIX . "_navigation_items
					SET
						title = '" . $this->_replace_wildcode($title) . "',
						navi_item_link  = '" . $_POST['navi_item_link'][$id] . "',
						$position
						navi_item_target  = '" . $_POST['navi_item_target'][$id] . "',
						navi_item_status = '" . $navi_item_status . "',
						navi_item_desc = '" . $_POST['descr'][$id] . "',
						navi_item_Img = '" . $_POST['Img'][$id] . "',
						navi_item_Img_id = '" . $_POST['Img_id'][$id] . "',
						document_alias   = '" . ($link_url == '' ? $_POST['navi_item_link'][$id] : $link_url) . "'
					WHERE
						Id = '" . $id . "'
				");
			}
		}

		// Если в запросе пришел параметр на добавление нового пункта меню первого уровня
		if (!empty($_POST['Titel_N'][0]))
		{

			// Выполняем запрос к БД и добавляем новый пункт
			$AVE_DB->Query("
				INSERT
				INTO " . PREFIX . "_navigation_items
				SET
					Id	 = '',
					title  = '" . $this->_replace_wildcode($_POST['Titel_N'][0]) . "',
					parent_id  = '0',
					navi_item_link   = '" . $_POST['Link_N'][0] . "',
					navi_item_target   = '" . $_POST['Target_N'][0] . "',
					navi_item_level  = '1',
					navi_item_position   = '" . intval($_POST['Rang_N'][0]) . "',
					navi_id = '" . intval($_POST['navi_id']) . "',
					navi_item_desc = '" . $_POST['descr_N'][0] . "',
					navi_item_Img = '" . $_POST['Img_N'][0] . "',
					navi_item_Img_id = '" . $_POST['Img_id_N'][0] . "',
					navi_item_status  = '" . (empty($_POST['Link_N'][0]) ? '0' : '1') . "',
					document_alias	= '" . prepare_url(empty($_POST['Url_N'][0]) ? $_POST['Titel_N'][0] : $_POST['Url_N'][0]) . "'
			");

			// Сохраняем системное сообщение в журнал
			reportLog($AVE_Template->get_config_vars('NAVI_REPORT_ADDIT') . " (" . stripslashes($_POST['Titel_N'][0]) . ") - ". $AVE_Template->get_config_vars('NAVI_REPORT_FLEV'));
		}

		// Обрабатываем данные с целью добавления пунктов меню второго уровня
		foreach ($_POST['Titel_Item_2'] as $new2_id => $title)
		{
			// Если название пункта не пустое
			if (!empty($title))
			{
				$new2_id = (int)$new2_id;

				// Выполняем запрос к БД и добавляем новый подпункт
				$AVE_DB->Query("
					INSERT
					INTO " . PREFIX . "_navigation_items
					SET
						Id	 = '',
						title  = '" . $this->_replace_wildcode($title) . "',
						parent_id  = '" . $new2_id . "',
						navi_item_link   = '" . $_POST['Link_Item_2'][$new2_id] . "',
						navi_item_target   = '" . $_POST['Target_Item_2'][$new2_id] . "',
						navi_item_desc = '" . $_POST['descr_Item_2'][$new2_id] . "',
						navi_item_Img = '" . $_POST['Img_Item_2'][$new2_id] . "',
						navi_item_Img_id = '" . $_POST['Img_id_Item_2'][$new2_id] . "',
						navi_item_level  = '2',
						navi_item_position   = '" . intval($_POST['Rang_Item_2'][$new2_id]) . "',
						navi_id = '" . intval($_POST['navi_id']) . "',
						navi_item_status  = '" . (empty($_POST['Link_Item_2'][$new2_id]) ? '0' : '1') . "',
						document_alias	= '" . prepare_url(empty($_POST['Url_Item_2'][$new2_id]) ? $title : $_POST['Url_Item_2'][$new2_id]) . "'
				");

				// Сохраняем системное сообщение в журнал
				reportLog($AVE_Template->get_config_vars('NAVI_REPORT_ADDIT') . " (" . $this->_replace_wildcode($title) . ") - ". $AVE_Template->get_config_vars('NAVI_REPORT_SLEV'));
			}
		}

		// Обрабатываем данные с целью добавления пунктов меню третьего уровня
		foreach ($_POST['Titel_Item_3'] as $new3_id => $title)
		{
			// Если название пункта не пустое
			if (!empty($title))
			{
				$new3_id = (int)$new3_id;
				// Выполняем запрос к БД и добавляем новый подпункт
				$AVE_DB->Query("
					INSERT
					INTO " . PREFIX . "_navigation_items
					SET
						Id	 = '',
						title  = '" . $this->_replace_wildcode($title) . "',
						parent_id  = '" . $new3_id . "',
						navi_item_link   = '" . $_POST['Link_Item_3'][$new3_id] . "',
						navi_item_target   = '" . $_POST['Target_Item_3'][$new3_id] . "',
						navi_item_desc = '" . $_POST['descr_Item_3'][$new3_id] . "',
						navi_item_Img = '" . $_POST['Img_Item_3'][$new3_id] . "',
						navi_item_Img_id = '" . $_POST['Img_id_Item_3'][$new3_id] . "',
						navi_item_level  = '3',
						navi_item_position   = '" . intval($_POST['Rang_Item_3'][$new3_id]) . "',
						navi_id = '" . intval($_POST['navi_id']) . "',
						navi_item_status  = '" . (empty($_POST['Link_Item_3'][$new3_id]) ? '0' : '1') . "',
						document_alias	= '" . prepare_url(empty($_POST['Url_Item_3'][$new3_id]) ? $title : $_POST['Url_Item_3'][$new3_id]) . "'
				");

				// Сохраняем системное сообщение в журнал
				reportLog($AVE_Template->get_config_vars('NAVI_REPORT_ADDIT') . " (" . $this->_replace_wildcode($title) . ") - ". $AVE_Template->get_config_vars('NAVI_REPORT_TLEV'));
			}
		}

		// Если в запросе были отмечены пункты меню, которые необходимо удалить, тогда
		if (!empty($_POST['del']) && is_array($_POST['del']))
		{
			// Циклически обрабатываем помеченные пункты
			foreach ($_POST['del'] as $del_id => $del)
			{
				if (!empty($del))
				{
					$del_id = (int)$del_id;

					// Выполняем запрос к БД для определения у удаляемого пункта подпунктов
					$num = $AVE_DB->Query("
						SELECT Id
						FROM " . PREFIX . "_navigation_items
						WHERE parent_id = '" . $del_id . "'
						LIMIT 1
					")->NumRows();

					// Если данный пункт имеет подпункты, тогда
					if ($num==1)
					{
						$sql = $AVE_DB->Query("
							SELECT *
							FROM " . PREFIX . "_navigation_items
							WHERE Id = '" . $del_id . "'
							LIMIT 1
						")->FetchRow();
						// Выполняем запрос к БД и деактивируем пункт меню
						$AVE_DB->Query("
							UPDATE " . PREFIX . "_navigation_items
							SET navi_item_status = '0'
							WHERE Id = '" . $del_id . "'
						");
						// Сохраняем системное сообщение в журнал
						reportLog($AVE_Template->get_config_vars('NAVI_REPORT_DEACT') . " (" . stripslashes($sql->title) . ") (id: $del_id)");
					}
					else
					{ // В противном случае, если данный пункт не имеет подпунктов, тогда
						$sql = $AVE_DB->Query("
							SELECT *
							FROM " . PREFIX . "_navigation_items
							WHERE Id = '" . $del_id . "'
							LIMIT 1
						")->FetchRow();
						// Выполняем запрос к БД и удаляем помеченный пункт
						$AVE_DB->Query("
							DELETE
							FROM " . PREFIX . "_navigation_items
							WHERE Id = '" . $del_id . "'
						");
						// Сохраняем системное сообщение в журнал
						reportLog($AVE_Template->get_config_vars('NAVI_REPORT_DELIT') . " (" . stripslashes($sql->title) . ") (id: $del_id)");
					}
				}
			}
		}

		// Выполняем обновление страницы
		header('Location:index.php?do=navigation&action=entries&id=' . $nav_id . '&cp=' . SESSION);
		exit;
	}



	/**
	 * Метод, предназначенный для удаления пунктов меню навигации связанных с удаляемым документом.
	 * Данный метод вызывается при удалении документа с идентификатором $document_id.
	 * Если у пункта меню нет потомков - пункт удаляется, в противном случае пункт деактивируется
	 *
	 * @param int $document_id идентификатор удаляемого документа
	 */
	function navigationItemDelete($document_id)
	{
		global $AVE_DB, $AVE_Template;

		$document_id = (int)$document_id;

		// Выполняем запрос к БД и получаем ID пункта меню, с которым связан документ
		$sql = $AVE_DB->Query("
			SELECT Id
			FROM " . PREFIX . "_navigation_items
			WHERE navi_item_link = 'index.php?id=" . $document_id . "'
		");

		while ($row = $sql->fetchrow())
		{
			// Выполняем запрос к БД для определения у удаляемого пункта подпунктов
			$num = $AVE_DB->Query("
				SELECT Id
				FROM " . PREFIX . "_navigation_items
				WHERE parent_id = '" . $row->Id . "'
				LIMIT 1
			")->NumRows();

			// Если данный пункт имеет подпункты, тогда
			if ($num==1)
			{
				// Выполняем запрос к БД и деактивируем пункт меню
				$AVE_DB->Query("
					UPDATE " . PREFIX . "_navigation_items
					SET navi_item_status = '0'
					WHERE Id = '" . $row->Id . "'
				");

				// Сохраняем системное сообщение в журнал
				reportLog($AVE_Template->get_config_vars('NAVI_REPORT_DEACT') . " (id: $row->Id)");
			}
			else
			{ // В противном случае, если данный пункт не имеет подпунктов, тогда

				// Выполняем запрос к БД и удаляем помеченный пункт
				$AVE_DB->Query("
					DELETE
					FROM " . PREFIX . "_navigation_items
					WHERE Id = '" . $row->Id . "'
				");

				// Сохраняем системное сообщение в журнал
				reportLog($AVE_Template->get_config_vars('NAVI_REPORT_DELIT') . " (id: $row->Id)");
			}
		}
	}



	/**
	 * Метод, предназначенный для активации пункта меню навигации.
	 * Данный метод используется при изменении статуса документа с идентификатором $document_id
	 *
	 * @param int $document_id идентификатор документа на который ссылается пункт меню
	 */
	function navigationItemStatusOn($document_id)
	{
		global $AVE_DB, $AVE_Template;

		if (!is_numeric($document_id)) return;

		// Выполняем запрос к БД и получаем id пункта меню, который соответствует идентификатору документа в ссылке
		$sql = $AVE_DB->Query("
			SELECT Id
			FROM " . PREFIX . "_navigation_items
			WHERE navi_item_link = 'index.php?id=" . $document_id . "'
			AND navi_item_status = '0'
		");

		while ($row = $sql->fetchrow())
		{
			// Выполняем запрос к БД изменяем статус пункта меню на активный (1)
			$AVE_DB->Query("
				UPDATE " . PREFIX . "_navigation_items
				SET navi_item_status = '1'
				WHERE Id = '" . $row->Id . "'
			");

			// Сохраняем системное сообщение в журнал
			reportLog($AVE_Template->get_config_vars('NAVI_REPORT_ACT') . " (id: $row->Id)");
		}
	}

	/**
	 * Метод, предназначенный для деактивации пункта меню навигации.
	 * Данный метод используется при изменении статуса документа с идентификатором $document_id
	 *
	 * @param int $document_id идентификатор документа на который ссылается пункт меню
	 */
	function navigationItemStatusOff($document_id)
	{
		global $AVE_DB, $AVE_Template;

		if (!is_numeric($document_id)) return;

		// Выполняем запрос к БД и получаем id пункта меню, который соответствует идентификатору документа в ссылке
		$sql = $AVE_DB->Query("
			SELECT Id
			FROM " . PREFIX . "_navigation_items
			WHERE navi_item_link = 'index.php?id=" . $document_id . "'
			AND navi_item_status = '1'
		");

		while ($row = $sql->fetchrow())
		{
			// Выполняем запрос к БД изменяем статус пункта меню на неактивный (0)
			$AVE_DB->Query("
				UPDATE " . PREFIX . "_navigation_items
				SET navi_item_status = '0'
				WHERE Id = '" . $row->Id . "'
			");

			// Сохраняем системное сообщение в журнал
			reportLog($AVE_Template->get_config_vars('NAVI_REPORT_DEACT') . " (id: $row->Id)");

		}
	}
}

?>