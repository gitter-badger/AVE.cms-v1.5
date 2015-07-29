<?php
/**
 * AVE.cms
 * since 2.0
 * Класс работы с настройками LiveEditor
 * author Aleksandr Salnikov (Repellent) webstudio3v.ru
 * @package AVE.cms
 * @filesource
 */
class AVE_LiveEditor
{
	/**
	 * Вывод списка настроек
	 *
	 */
	function live_editorList()
	{
		global $AVE_DB, $AVE_Template;

		$live_editors = array();
		$sql = $AVE_DB->Query("SELECT * FROM " . PREFIX . "_liveeditor");

		// Формируем массив из полученных данных
		while ($result = $sql->FetchRow())
		{
			array_push($live_editors, $result);
		}

		$AVE_Template->assign('live_editors', $live_editors);
		$AVE_Template->assign('content', $AVE_Template->fetch('liveeditor/templates/list.tpl'));
	}
	
	/**
	 * Сохранение настроек
	 *
	 * @param int $liveeditor_id идентификатор настройки
	 */
	function live_editorSave($liveeditor_id = null)
	{
		global $AVE_DB, $AVE_Template;

		if (is_numeric($liveeditor_id))
		{
			$AVE_DB->Query("
				UPDATE " . PREFIX . "_liveeditor
				SET
					liveeditor_name = '" . $_POST['liveeditor_name'] . "'
				WHERE
					id = '" . $liveeditor_id . "'
			");

			if ($liveeditor_id == 1)
			{
				$str = $_POST['liveeditor_real_toolbar'];
				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_set_mf.php', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_set_mf.php', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}
			else if ($liveeditor_id == 2)
			{
				$str = $_POST['liveeditor_real_toolbar'];
				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_set_smf.php', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_set_smf.php', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}
			else if ($liveeditor_id == 3)
			{
				$str = $_POST['liveeditor_real_toolbar'];
				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_set_sys.php', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_set_sys.php', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}

			if ($liveeditor_id == 1)
			{
				$str = $_POST['liveeditor_available_toolbar'];
				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_available_mf.tpl', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_available_mf.tpl', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}
			else if ($liveeditor_id == 2)
			{
				$str = $_POST['liveeditor_available_toolbar'];
				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_available_smf.tpl', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_available_smf.tpl', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}
			else if ($liveeditor_id == 3)
			{
				$str = $_POST['liveeditor_available_toolbar'];
				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_available_sys.tpl', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_available_sys.tpl', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}

			if ($liveeditor_id == 1)
			{
				$str = $_POST['liveeditor_new_toolbar'];
				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_new_mf.tpl', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_new_mf.tpl', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}
			else if ($liveeditor_id == 2)
			{
				$str = $_POST['liveeditor_new_toolbar'];
				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_new_smf.tpl', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_new_smf.tpl', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}
			else if ($liveeditor_id == 3)
			{
				$str = $_POST['liveeditor_new_toolbar'];
				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_new_sys.tpl', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_new_sys.tpl', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}

			if ($liveeditor_id == 1)
			{
				$sys_settings = array (0=>"$_POST[li_insert_tag]\r\n", 1=>"$_POST[li_clear_text]\r\n", 2=>"$_POST[li_load_flickr]\r\n", 3=>"\"$_POST[li_input_name_flickr]\"\r\n", 4=>"$_POST[li_use_css_btn]\r\n", 5=>"$_POST[li_use_autoformat_table]\r\n", 6=>"\"$_POST[li_input_name_cssprefix]\"\r\n", 7=>"$_POST[li_use_focus]\r\n");
				$fp_sys = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/sys_settings.txt', 'w');
				$res_sys = fwrite($fp_sys, $sys_settings[0] . $sys_settings[1] . $sys_settings[2] . $sys_settings[3] . $sys_settings[4] . $sys_settings[5] . $sys_settings[6] . $sys_settings[7]);
				fclose($fp_sys);
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/sys_settings.txt', 0775);
			}
			else if ($liveeditor_id == 2)
			{
				$sys_settings = array (0=>"$_POST[li_insert_tag]\r\n", 1=>"$_POST[li_clear_text]\r\n", 2=>"$_POST[li_load_flickr]\r\n", 3=>"\"$_POST[li_input_name_flickr]\"\r\n", 4=>"$_POST[li_use_css_btn]\r\n", 5=>"$_POST[li_use_autoformat_table]\r\n", 6=>"\"$_POST[li_input_name_cssprefix]\"\r\n", 7=>"$_POST[li_use_focus]\r\n");
				$fp_sys = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/sys_settings.txt', 'w');
				$res_sys = fwrite($fp_sys, $sys_settings[0] . $sys_settings[1] . $sys_settings[2] . $sys_settings[3] . $sys_settings[4] . $sys_settings[5] . $sys_settings[6] . $sys_settings[7]);
				fclose($fp_sys);
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/sys_settings.txt', 0775);
			}
			else if ($liveeditor_id == 3)
			{
				$sys_settings = array (0=>"$_POST[li_insert_tag]\r\n", 1=>"$_POST[li_clear_text]\r\n", 2=>"$_POST[li_load_flickr]\r\n", 3=>"\"$_POST[li_input_name_flickr]\"\r\n", 4=>"$_POST[li_use_css_btn]\r\n", 5=>"$_POST[li_use_autoformat_table]\r\n", 6=>"\"$_POST[li_input_name_cssprefix]\"\r\n", 7=>"$_POST[li_use_focus]\r\n");
				$fp_sys = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/sys_settings.txt', 'w');
				$res_sys = fwrite($fp_sys, $sys_settings[0] . $sys_settings[1] . $sys_settings[2] . $sys_settings[3] . $sys_settings[4] . $sys_settings[5] . $sys_settings[6] . $sys_settings[7]);
				fclose($fp_sys);
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/sys_settings.txt', 0775);
			}
			
		}
		else
		{
			$AVE_DB->Query("
				INSERT
				INTO " . PREFIX . "_liveeditor
				SET
					id = '',
					liveeditor_name = '" . $_POST['liveeditor_name'] . "'
			");
			$liveeditor_id = $AVE_DB->Query("SELECT LAST_INSERT_ID(id) FROM " . PREFIX . "_liveeditor ORDER BY id DESC LIMIT 1")->GetCell();

			if ($liveeditor_id == 1)
			{
				$str = $_POST['liveeditor_real_toolbar'];
				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_set_mf.php', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_set_mf.php', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}
			else if ($liveeditor_id == 2)
			{
				$str = $_POST['liveeditor_real_toolbar'];
				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_set_smf.php', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_set_smf.php', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}
				else if ($liveeditor_id == 3)
			{
				$str = $_POST['liveeditor_real_toolbar'];
				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_set_sys.php', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_set_sys.php', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}

			if ($liveeditor_id == 1)
			{
				$str = $_POST['liveeditor_available_toolbar'];
				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_available_mf.tpl', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_available_mf.tpl', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}
			else if ($liveeditor_id == 2)
			{
				$str = $_POST['liveeditor_available_toolbar'];
				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_available_smf.tpl', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_available_smf.tpl', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}
				else if ($liveeditor_id == 3)
			{
				$str = $_POST['liveeditor_available_toolbar'];
				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_available_sys.tpl', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_available_sys.tpl', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}
		
			if ($liveeditor_id == 1)
			{
				$str = $_POST['liveeditor_new_toolbar'];
				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_new_mf.tpl', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_new_mf.tpl', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}
			else if ($liveeditor_id == 2)
			{
				$str = $_POST['liveeditor_new_toolbar'];
				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_new_smf.tpl', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_new_smf.tpl', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}
				else if ($liveeditor_id == 3)
			{
				$str = $_POST['liveeditor_new_toolbar'];
				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_new_sys.tpl', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_new_sys.tpl', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}
			if ($liveeditor_id == 1)
			{
				$sys_settings = array (0=>"$_POST[li_insert_tag]\r\n", 1=>"$_POST[li_clear_text]\r\n", 2=>"$_POST[li_load_flickr]\r\n", 3=>"\"$_POST[li_input_name_flickr]\"\r\n", 4=>"$_POST[li_use_css_btn]\r\n", 5=>"$_POST[li_use_autoformat_table]\r\n", 6=>"\"$_POST[li_input_name_cssprefix]\"\r\n", 7=>"$_POST[li_use_focus]\r\n");
				$fp_sys = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/sys_settings.txt', 'w');
				$res_sys = fwrite($fp_sys, $sys_settings[0] . $sys_settings[1] . $sys_settings[2] . $sys_settings[3] . $sys_settings[4] . $sys_settings[5] . $sys_settings[6] . $sys_settings[7]);
				fclose($fp_sys);
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/sys_settings.txt', 0775);
			}
			else if ($liveeditor_id == 2)
			{
				$sys_settings = array (0=>"$_POST[li_insert_tag]\r\n", 1=>"$_POST[li_clear_text]\r\n", 2=>"$_POST[li_load_flickr]\r\n", 3=>"\"$_POST[li_input_name_flickr]\"\r\n", 4=>"$_POST[li_use_css_btn]\r\n", 5=>"$_POST[li_use_autoformat_table]\r\n", 6=>"\"$_POST[li_input_name_cssprefix]\"\r\n", 7=>"$_POST[li_use_focus]\r\n");
				$fp_sys = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/sys_settings.txt', 'w');
				$res_sys = fwrite($fp_sys, $sys_settings[0] . $sys_settings[1] . $sys_settings[2] . $sys_settings[3] . $sys_settings[4] . $sys_settings[5] . $sys_settings[6] . $sys_settings[7]);
				fclose($fp_sys);
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/sys_settings.txt', 0775);
			}
			else if ($liveeditor_id == 3)
			{
				$sys_settings = array (0=>"$_POST[li_insert_tag]\r\n", 1=>"$_POST[li_clear_text]\r\n", 2=>"$_POST[li_load_flickr]\r\n", 3=>"\"$_POST[li_input_name_flickr]\"\r\n", 4=>"$_POST[li_use_css_btn]\r\n", 5=>"$_POST[li_use_autoformat_table]\r\n", 6=>"\"$_POST[li_input_name_cssprefix]\"\r\n", 7=>"$_POST[li_use_focus]\r\n");
				$fp_sys = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/sys_settings.txt', 'w');
				$res_sys = fwrite($fp_sys, $sys_settings[0] . $sys_settings[1] . $sys_settings[2] . $sys_settings[3] . $sys_settings[4] . $sys_settings[5] . $sys_settings[6] . $sys_settings[7]);
				fclose($fp_sys);
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/sys_settings.txt', 0775);
			}
		}

		if (!isset($_REQUEST['next_edit'])) {
			header('Location:index.php?do=liveeditor&cp=' . SESSION);
		} else {
			header('Location:index.php?do=liveeditor&action=edit&&id='.$liveeditor_id.'&cp='. SESSION);
		}
	}
	/**
	 * Редактирование настройки LiveEditor
	 *
	 * @param int $liveeditor_id идентификатор настройки
	 *
	 */
	function live_editorEdit($liveeditor_id)
	{
		global $AVE_DB, $AVE_Template;

		if (is_numeric($liveeditor_id))
		{
			$sql = $AVE_DB->Query("
				SELECT *
				FROM " . PREFIX . "_liveeditor
				WHERE id = '" . $liveeditor_id . "'
			");

			$row = $sql->FetchAssocArray();
		}
		else
		{
			$row['liveeditor_name'] = '';

			if ($liveeditor_id == 1)
			{
				$str = $_POST['liveeditor_real_toolbar'];

				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_set_mf.php', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_set_mf.php', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}
			else if ($liveeditor_id == 2)
			{
				$str = $_POST['liveeditor_real_toolbar'];
				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_set_smf.php', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_set_smf.php', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}
			else if ($liveeditor_id == 3)
			{
				$str = $_POST['liveeditor_real_toolbar'];
				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_set_sys.php', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_set_sys.php', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}

			if ($liveeditor_id == 1)
			{
				$str = $_POST['liveeditor_available_toolbar'];
				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_available_mf.tpl', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_available_mf.tpl', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}
			else if ($liveeditor_id == 2)
			{
				$str = $_POST['liveeditor_available_toolbar'];
				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_available_smf.tpl', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_available_smf.tpl', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}
			else if ($liveeditor_id == 3)
			{
				$str = $_POST['liveeditor_available_toolbar'];
				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_available_sys.tpl', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_available_sys.tpl', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}
			if ($liveeditor_id == 1)
			{
				$str = $_POST['liveeditor_new_toolbar'];
				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_new_mf.tpl', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_new_mf.tpl', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}
			else if ($liveeditor_id == 2)
			{
				$str = $_POST['liveeditor_new_toolbar'];
				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_new_smf.tpl', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_new_smf.tpl', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}
            else if ($liveeditor_id == 3)
			{
				$str = $_POST['liveeditor_new_toolbar'];
				$fp = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/li_new_sys.tpl', 'w');
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/li_new_sys.tpl', 0775);
				$contents =  stripslashes($str);
				$res = fwrite($fp, $contents);
				fclose($fp);
			}
			if ($liveeditor_id == 1)
			{
				$sys_settings = array (0=>"$_POST[li_insert_tag]\r\n", 1=>"$_POST[li_clear_text]\r\n", 2=>"$_POST[li_load_flickr]\r\n", 3=>"\"$_POST[li_input_name_flickr]\"\r\n", 4=>"$_POST[li_use_css_btn]\r\n", 5=>"$_POST[li_use_autoformat_table]\r\n", 6=>"\"$_POST[li_input_name_cssprefix]\"\r\n", 7=>"$_POST[li_use_focus]\r\n");
				$fp_sys = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/sys_settings.txt', 'w');
				$res_sys = fwrite($fp_sys, $sys_settings[0] . $sys_settings[1] . $sys_settings[2] . $sys_settings[3] . $sys_settings[4] . $sys_settings[5] . $sys_settings[6] . $sys_settings[7]);
				fclose($fp_sys);
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/sys_settings.txt', 0775);
			}
			else if ($liveeditor_id == 2)
			{
				$sys_settings = array (0=>"$_POST[li_insert_tag]\r\n", 1=>"$_POST[li_clear_text]\r\n", 2=>"$_POST[li_load_flickr]\r\n", 3=>"\"$_POST[li_input_name_flickr]\"\r\n", 4=>"$_POST[li_use_css_btn]\r\n", 5=>"$_POST[li_use_autoformat_table]\r\n", 6=>"\"$_POST[li_input_name_cssprefix]\"\r\n", 7=>"$_POST[li_use_focus]\r\n");
				$fp_sys = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/sys_settings.txt', 'w');
				$res_sys = fwrite($fp_sys, $sys_settings[0] . $sys_settings[1] . $sys_settings[2] . $sys_settings[3] . $sys_settings[4] . $sys_settings[5] . $sys_settings[6] . $sys_settings[7]);
				fclose($fp_sys);
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/sys_settings.txt', 0775);
			}
			else if ($liveeditor_id == 3)
			{
				$sys_settings = array (0=>"$_POST[li_insert_tag]\r\n", 1=>"$_POST[li_clear_text]\r\n", 2=>"$_POST[li_load_flickr]\r\n", 3=>"\"$_POST[li_input_name_flickr]\"\r\n", 4=>"$_POST[li_use_css_btn]\r\n", 5=>"$_POST[li_use_autoformat_table]\r\n", 6=>"\"$_POST[li_input_name_cssprefix]\"\r\n", 7=>"$_POST[li_use_focus]\r\n");
				$fp_sys = fopen(BASE_DIR . '/admin/templates/liveeditor/f_config/sys_settings.txt', 'w');
				$res_sys = fwrite($fp_sys, $sys_settings[0] . $sys_settings[1] . $sys_settings[2] . $sys_settings[3] . $sys_settings[4] . $sys_settings[5] . $sys_settings[6] . $sys_settings[7]);
				fclose($fp_sys);
				chmod(BASE_DIR . '/admin/templates/liveeditor/f_config/sys_settings.txt', 0775);
			}
		}

		if(file_exists(BASE_DIR . '/admin/templates/liveeditor/f_config/sys_settings.txt'))
		{
			$f_sys_settings = file(BASE_DIR . '/admin/templates/liveeditor/f_config/sys_settings.txt');
		}
		$AVE_Template->assign('f_sys_settings', $f_sys_settings);

		$AVE_Template->assign($row);
		$AVE_Template->assign('content', $AVE_Template->fetch('liveeditor/templates/edit.tpl'));
	}

	/**
	 * Создание настройки
	 *
	 
	function live_editorNew()
	{
		global $AVE_DB, $AVE_Template;

		$row['liveeditor_name'] = '';
		$row['liveeditor_text'] = '';

		$AVE_Template->assign($row);
		$AVE_Template->assign('content', $AVE_Template->fetch(BASE_DIR . '/admin/templates/liveeditor/templates/edit.tpl'));
	}*/

	/**
	 * Удаление настройки
	 *
	 * @param int $liveeditor_id идентификатор настройки
	 
	function live_editorDelete($liveeditor_id)
	{
		global $AVE_DB, $AVE_Template;

		if (is_numeric($liveeditor_id))
		{
			 $sql= $AVE_DB->Query("
				SELECT *
				FROM " . PREFIX . "_liveeditor
				WHERE id = '" . $liveeditor_id . "'
			")->FetchRow();

			$AVE_DB->Query("
				DELETE
				FROM " . PREFIX . "_liveeditor
				WHERE id = '" . $liveeditor_id . "'
			");

			// Сохраняем системное сообщение в журнал
			reportLog($_SESSION['user_name'] . " - " . $AVE_Template->get_config_vars('LIVEEDITOR_SQLDEL') . " (" . stripslashes($sql->liveeditor_name) . ") (id: $liveeditor_id)", 2, 2);
		}
		header('Location:index.php?do=liveeditor&cp=' . SESSION);
	}*/
}

?>