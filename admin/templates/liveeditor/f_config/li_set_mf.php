<?php
 $large = '
<script type="text/javascript">
var oEdit' . $field_id . ' = new InnovaEditor("oEdit' . $field_id . '");
oEdit' . $field_id . '.css = ["/admin/liveeditor/LiveEditor/styles/default.css","/admin/liveeditor/AddOns/bootstrap/css/bridge.css"]; 
oEdit' . $field_id . '.arrCustomButtons = [["Snippets", "modalDialog(\'/admin/liveeditor/AddOns/bootstrap/snippets.htm\',900,658,\'Insert Snippets\');", "Bootstrap", "btnContentBlock.gif"]];
oEdit' . $field_id . '.returnKeyMode = 1;
oEdit' . $field_id . '.pasteTextOnCtrlV = true;
oEdit' . $field_id . '.enableFlickr = true;
oEdit' . $field_id . '.flickrUser = "ysw.insite";
oEdit' . $field_id . '.enableCssButtons = true;
oEdit' . $field_id . '.enableTableAutoformat = true;
oEdit' . $field_id . '.styleSelectorPrefix = "";
oEdit' . $field_id . '.disableFocusOnLoad = true;
oEdit' . $field_id . '.fileBrowser = "/admin/liveeditor/AddOns/assetmanager/asset.php";
oEdit' . $field_id . '.width = "100%";
oEdit' . $field_id . '.height = "450px";
oEdit' . $field_id . '.groups = [["group1", "", [""]]];
oEdit' . $field_id . '.REPLACE("editor[' . $field_id . ']");
</script>
';
 $innova = array (1 =>"$large");
 ?>