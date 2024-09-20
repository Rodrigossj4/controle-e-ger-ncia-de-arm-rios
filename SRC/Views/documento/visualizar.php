
<?php
//$file = "C:\apache\htdocs\Documentos\\teste.pdf";
$file = $arquivo;
$filename = 'Custom file name for the.pdf'; 

header('Content-type: application/pdf');
header('Content-Disposition: inline; filename="' . $filename . '"');
header('Content-Transfer-Encoding: binary');
header('Content-Length: ' . filesize($file));
header('Accept-Ranges: bytes');
@readfile($file);
sleep(10);
unlink("{$arquivo}");
//var_dump($file);
?>
