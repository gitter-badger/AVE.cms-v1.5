<?

/**
 * AVE.cms
 *
 * @package AVE.cms
 * @version 3.x
 * @filesource
 * @copyright © 2007-2014 AVE.cms, http://www.ave-cms.ru
 *
 * @license GPL v.2
 */

// Мульти список
function get_field_multi_select($field_value, $action, $field_id=0, $tpl='', $tpl_empty=0, &$maxlength=null, $document_fields=array(), $rubric_id=0, $default=null)
{
	global $AVE_Template;

	$fld_dir  = dirname(__FILE__) . '/';
	$tpl_dir  = $fld_dir . 'tpl/';
	$fld_name = basename($fld_dir);

	$lang_file = $fld_dir . 'lang/' . (defined('ACP') ? $_SESSION['admin_language'] : $_SESSION['user_language']) . '.txt';

	$AVE_Template->config_load($lang_file, 'lang');
	$AVE_Template->assign('config_vars', $AVE_Template->get_config_vars());
	$AVE_Template->config_load($lang_file, 'admin');

	$res = 0;

	switch ($action)
	{
		case 'edit':
			$items = array();

			$items = explode(',', $default);

			$items = array_diff($items, array(''));

			@$field_value = unserialize($field_value);

			$AVE_Template->assign('items', $items);
			$AVE_Template->assign('field_id', $field_id);
			$AVE_Template->assign('field_value', $field_value);

			$tpl_file = get_field_tpl($tpl_dir, $field_id, 'admin');
			return $AVE_Template->fetch($tpl_file);
			break;

		case 'doc':
			$items = (isset($field_value)) ? unserialize($field_value) : array();

			$res='';

			if($items != false)
			{
				foreach($items as $item)
				{
					$item = clean_php($item);

					$field_param = explode('|', $item);

					if($item)
					{
						if ($tpl_empty)
						{
							$item = $field_param[0];
						}
						else
						{
							$item = preg_replace('/\[tag:parametr:(\d+)\]/ie', '@$field_param[\\1]', $tpl);
						}
					}
					$res[] = $item;
				}
			}

			$tpl_file = get_field_tpl($tpl_dir, $field_id, 'doc');

			if($tpl_empty && $tpl_file)
			{
				$AVE_Template->assign('field_id', $field_id);
				$AVE_Template->assign('field_value', $res);
				$AVE_Template->assign('rubric_id', $rubric_id);
				$AVE_Template->assign('default', $default);

				return $AVE_Template->fetch($tpl_file);
			}

			return (! empty($res))
				? implode(PHP_EOL, $res)
				: $tpl;

			break;

		case 'req':
			$items = (isset($field_value)) ? unserialize($field_value) : array();

			$res = '';

			if($items != false)
			{
				foreach($items as $item)
				{
					$item = clean_php($item);

					$field_param = explode('|', $item);

					if($item)
					{
						if ($tpl_empty)
						{
							$item = $field_param[0];
						}
						else
						{
							$item = preg_replace('/\[tag:parametr:(\d+)\]/ie', '@$field_param[\\1]', $tpl);
						}
					}
					$res[] = $item;
				}
			}

			$tpl_file = get_field_tpl($tpl_dir, $field_id, 'req');

			if($tpl_empty && $tpl_file)
			{
				$AVE_Template->assign('field_id', $field_id);
				$AVE_Template->assign('field_value', $res);
				$AVE_Template->assign('rubric_id', $rubric_id);
				$AVE_Template->assign('default', $default);

				return $AVE_Template->fetch($tpl_file);
			}

			return (!empty($res)) ? implode(PHP_EOL, $res) : $tpl;
			break;

		case 'name':
			return $AVE_Template->get_config_vars('name');
			break;

	}

	return ($res ? $res : $field_value);
}
?>
