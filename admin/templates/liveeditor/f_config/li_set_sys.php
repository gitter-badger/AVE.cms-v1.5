<?php
 $large = '
<script type="text/javascript">
var oEdit = new InnovaEditor("oEdit");
oEdit.css = ["/admin/liveeditor/LiveEditor/styles/default.css","/admin/liveeditor/AddOns/bootstrap/css/bridge.css"]; 
oEdit.arrCustomButtons = [["Snippets", "modalDialog(\'/admin/liveeditor/AddOns/bootstrap/snippets.htm\',900,658,\'Insert Snippets\');", "Bootstrap", "btnContentBlock.gif"]];
oEdit.returnKeyMode = 1;
oEdit.pasteTextOnCtrlV = true;
oEdit.enableFlickr = true;
oEdit.flickrUser = "ysw.insite";
oEdit.enableCssButtons = true;
oEdit.enableTableAutoformat = true;
oEdit.styleSelectorPrefix = "";
oEdit.disableFocusOnLoad = true;
oEdit.fileBrowser = "/admin/liveeditor/AddOns/assetmanager/asset.php";
oEdit.width = "100%";
oEdit.height = "450px";
oEdit.groups = [
        ["group1", "", ["FontName", "FontSize", "Superscript", "ForeColor", "BackColor", "FontDialog", "Quote", "CompleteTextDialog", "BRK", "Bold", "Italic", "Underline", "Strikethrough", "TextDialog", "Styles", "RemoveFormat"]],
        ["group2", "", ["JustifyLeft", "JustifyCenter", "JustifyRight", "Paragraph", "BRK", "Bullets", "Numbering", "Indent", "Outdent"]],
        ["group3", "", ["Table", "TableDialog", "Emoticons", "FlashDialog", "BRK", "LinkDialog", "ImageDialog", "YoutubeDialog"]],
        ["group4", "", ["CharsDialog", "Line", "BRK", "Snippets"]],
        ["group5", "", ["SearchDialog", "SourceDialog", "ClearAll", "BRK", "Undo", "Redo", "FullScreen"]]
];
oEdit.REPLACE("sysblock_text");
</script>
';
 $innova = array (1 =>"$large");
 ?>