1. Установите права на папку f_config и все вложенные в нее файлы права CHMOD 0777 путь до папки:  admin/templates/liveeditor/f_config
===============================================================================================================================
Не забывайте в шаблоне подключить (по мере использования):

<head>
<link href="[tag:path]admin/liveeditor/LiveEditor/styles/default.css" rel="stylesheet" type="text/css" />	
<script src="http://ajax.googleapis.com/ajax/libs/webfont/1.5.2/webfont.js" type="text/javascript"></script>
<script src="[tag:path]admin/liveeditor/LiveEditor/scripts/common/webfont.js" type="text/javascript"></script>
<script type="text/javascript" src="[tag:path]lib/bootstrap/js/bootstrap.min.js"></script>
<link rel="stylesheet" type="text/css" href="[tag:path]lib/bootstrap/css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="[tag:path]lib/bootstrap/css/bootstrap-responsive.min.css" />

Если будуте использовать миниатюры изображений , которые создает редактор 
так же подключите в head шаблона:

<script src="[tag:path]admin/liveeditor/AddOns/fancybox13/jquery.easing-1.3.pack.js" type="text/javascript"></script>
<script src="[tag:path]admin/liveeditor/AddOns/jquery.mousewheel-3.0.2.pack.js" type="text/javascript"></script>
<script src="[tag:path]admin/liveeditor/AddOns/jquery.fancybox-1.3.1.pack.js" type="text/javascript"></script>
<link href="[tag:path]admin/liveeditor/AddOns/jquery.fancybox-1.3.1.css" rel="stylesheet" type="text/css" />
<script language="javascript" type="text/javascript">
  $(document).ready(function () {
     $('a[rel=lightbox]').fancybox();
  });
</script>
</head>

==================================================================================================================================