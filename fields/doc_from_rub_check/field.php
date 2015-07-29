<?

/**
 * AVE.cms
 *
 * @package AVE.cms
 * @version 3.x
 * @filesource
 * @copyright © 2007-2015 AVE.cms, http://www.ave-cms.ru
 *
 * @license GPL v.2
 */

// Документ из рубрики (Checkbox)
function get_field_doc_from_rub_check($field_value, $action, $field_id=0, $tpl='', $tpl_empty=0, &$maxlength=null, $document_fields=array(), $rubric_id=0, $default=null){

	global $AVE_DB, $AVE_Template;

	$fld_dir  = dirname(__FILE__) . '/';
	$tpl_dir  = $fld_dir . 'tpl/';

	$lang_file = $fld_dir . 'lang/' . (defined('ACP') ? $_SESSION['admin_language'] : $_SESSION['user_language']) . '.txt';

	$AVE_Template->config_load($lang_file, 'lang');
	$AVE_Template->assign('config_vars', $AVE_Template->get_config_vars());
	$AVE_Template->config_load($lang_file, 'admin');

	$res = 0;

	switch ($action)
	{
		case 'edit':
			if (isset($default) && is_numeric($default))
			{
				$parent = $AVE_DB->Query("
						SELECT
							MIN(document_parent) AS min
						FROM
							". PREFIX ."_documents
						WHERE
							rubric_id IN (" . $default . ")
				")->GetCell();

				$sql = $AVE_DB->Query("
					SELECT
						Id, document_parent, document_title
					FROM
						". PREFIX ."_documents
					WHERE
						rubric_id IN (" . $default . ")
				");

				$field_value_array = explode('|', $field_value);
				$field_value_array = array_values(array_diff($field_value_array, array('')));

				$cats = array();

				while($cat = $sql->FetchAssocArray())
				{
					$cat['checked'] = ((in_array($cat['Id'], $field_value_array) == false) ? "0" : "1");
					$cats_ID[$cat['Id']][] = $cat;
					$cats[$cat['document_parent']][$cat['Id']] = $cat;
				}

				$AVE_Template->assign('subtpl', $tpl_dir."list.tpl");
				$AVE_Template->assign('fields', doc_from_rub_check_tree($cats, $parent));
				$AVE_Template->assign('field_id', $field_id);
				$AVE_Template->assign('doc_id', (isset($_REQUEST['Id']) ? (int)$_REQUEST['Id'] : 0));
				$AVE_Template->assign('field_value', $field_value);
			}
			else
			{
				$AVE_Template->assign('error', $AVE_Template->get_config_vars('error'));
			}

			$tpl_file = get_field_tpl($tpl_dir, $field_id, 'admin');

			$AVE_Template->assign('subtpl', $tpl_dir."list.tpl");

			return $AVE_Template->fetch($tpl_file);
			break;

		case 'doc':
		case 'req':
			$res = get_field_default($field_value, $action, $field_id, $tpl, $tpl_empty, $maxlength, $document_fields, $rubric_id, $default);
			break;

		case 'name' :
			return $AVE_Template->get_config_vars('name');
			break;

	}

	return ($res ? $res : $field_value);
}

function doc_from_rub_check_tree($cats, $parent)
{
	if(is_array($cats) and isset($cats[$parent]))
	{
		foreach($cats[$parent] as $cat)
		{
			$array[$cat['Id']]['Id'] = $cat['Id'];
			$array[$cat['Id']]['checked'] = $cat['checked'];
			$array[$cat['Id']]['document_title'] = $cat['document_title'];
			$array[$cat['Id']]['child'] = doc_from_rub_check_tree($cats, $cat['Id']);
		}
	}
	else
	{
		return null;
	}

	return $array;
}
?>
