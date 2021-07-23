
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>DB2</title>
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
</head>

<div class="box" align="center">
<body bgcolor="#FFFFCC">
<style>
td {
    border: solid 1px black;
}
</style>
<table style="border: 5px solid #333; border-collapse: collapse">

<?php

require_once __DIR__ . '/src/autoload.php';

use \Erorus\DB2\Reader;

if (isset($argv[1])) {
    $path = $argv[1];
} else {
$parent_directory = 'tests\wdb2';
$dir = opendir($parent_directory);
echo '<form name="displayfile" action="" method="POST">';
echo '<select name="file2">';
echo '<option value="">Logfiles</option>';

while(false !== ($file = readdir($dir)))
{
	$files[] = $file;
	if(($file != ".") and ($file != ".."))
	{
		echo "<option value=".$file.">$file</option>";
	}
	//file2 is the name of the dropdown
	$selectedfile = $_POST['file2'];
	$path = __DIR__.'/tests/wdb2/'.$selectedfile;
}
echo '</select>';
}

echo "<button type=\"submit\" name=\"displayfile\">Submit</button>\n";
$reader = new Reader($path);
echo "Layout: ", dechex($reader->getLayoutHash()), "\n";
if (isset($argv[2])) {
    $reader->fetchColumnNames();
    print_r($reader->getRecord($argv[2]));
    exit;
}
echo "<table>";
echo "<tr>";

$recordNum = 0;
foreach ($reader->generateRecords() as $id => $record) {
    echo "<td>" .$id, ": ";//  implode(',', Reader::flattenRecord($record));

    $colNum = 0;
    foreach ($record as $colName => $colVal) {
        if ($colNum++ > 0) {
          //  echo "<td> </td>";
        };
        if (is_array($colVal)) {
            echo '[', implode(',', $colVal), ']';
        } else {
            echo "<td>" .$colVal, "</td>";
        }
    }

    echo "\n";
	echo "</tr>";

	if (++$recordNum <= 0) {
        break;
    }
}

echo "</table>";

?>

</body>
</html>
