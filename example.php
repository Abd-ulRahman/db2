
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>DB2</title>
<link rel="shortcut icon" type="image/x-icon" href="favicon.ico">
</head>

<div class="box" align="center">
<body bgcolor="#343a40">
<style>
body{
	Color: white;
}
h1{
	Color: green;
}
td {
    border: solid 1px white;
}
</style>
<table style="border: 5px solid #117a8b; border-collapse: collapse">

<?php

require_once __DIR__ . '/src/autoload.php';

use \Erorus\DB2\Reader;


$parent_directory = './tests';
$file_types = 'db2';

//===================================================//
// FUNCTION: directoryToArray                        //
//                                                   //
// Parameters:                                       //
//  - $root: The directory to process                //
//  - $to_return: f=files, d=directories, b=both     //
//  - $file_types: the extensions of file types to   //
//                 to return if files selected       //
//===================================================//
function directoryToArray($root, $to_return='b', $file_types=false) {
  $array_items = array();
  if ($file_types) { $file_types=explode(',',$file_types); }
  if ($handle = opendir($root)) {
    while (false !== ($file = readdir($handle))) {
      if ($file != "." && $file != "..") {

        $add_item = false;
        $type = (is_dir($root. "/" . $file))?'d':'f';
        $name = preg_replace("/\/\//si", "/", $file);

        if ($type=='d' && ($to_return=='b' || $to_return=='d') ) {
          $add_item = true;
        }

        if ($type=='f' && ($to_return=='b' || $to_return=='f') ) {
          $fext = (explode('.',$name));
          $ext = strtolower(end($fext));
      //  //  $ext = end(explode('.',$name));
          if ( !$file_types || in_array($ext, $file_types) ) {
            $add_item = true;
          }
        }

        if ($add_item) {
          $array_items[] = array ( 'name'=>$name, 'type'=>$type, 'root'=>$root);
        }
      }
    } // End While
    closedir($handle);
  } // End If
  return $array_items;
}

if (isset($_POST['pickfile'])) {

  // User has selected a file take whatever action you want based
  // upon the values for folder and file

    $path = $_GET['pickfile'];

} else {

    echo '
<html>
<head>
  <script type="text/javascript">
    function changeFolder(folder) {
      document.pickFile.submit();
    }
  </script>
</head>

<body>';


echo "<form name=\"pickFile\" method=\"POST\">\n";

$directoryList = directoryToArray($parent_directory,'d');

//echo "<select name=\"folder\" onBlur=\"changeFolder(this.value);\">\n";
echo "<select name=\"folder\" onchange=\"changeFolder(this.value);\">\n";
echo '<option value=\"\">Logfolder</option>\n';
foreach ($directoryList as $folder) {

  $selected = ($_POST[folder]==$folder[name])? 'selected' : '';
  echo "<option value=\"$folder[name]\" $selected>$folder[name]</option>\n";
}
echo '</select><br><br>';

$working_folder = ($_POST[folder]) ? '/'.$_POST[folder].'/' : '/'.$directoryList[0][name].'/';

$fileList = directoryToArray($parent_directory.'/'.$working_folder,'f',$file_types);

echo "<select name=\"file\">\n";
echo '<option value=\"\">Logfiles</option>\n';
foreach ($fileList as $file) {
	$selectedfile = $_POST['file'];
	echo "<option value=\"$file[name]\">$file[name]</option>\n";
}
echo '</select><br><br>';

$path = __DIR__.$parent_directory.$working_folder.$selectedfile;

echo "<button type=\"submit\" name=\"pickFile\">Submit</button>\n";

echo "</form>\n";
echo "</body>\n";
echo "</html>\n";
}
$reader = new Reader($path);
echo "<td>".$selectedfile," <<<<>>>> Layout: 0x", dechex($reader->getLayoutHash())," <<<<>>>> Layout in integer: ", ($reader->getLayoutHash()), nl2br("\n");
if (isset($_POST['pickfile'])) {
    $reader->fetchColumnNames();
    print_r($reader->getRecord($_GET['pickfile']));
    exit;
}
echo "<table>";
echo "<tr>";

$ColumnData = $reader->fetchColumnNames();
echo '<tr><td>', implode('<td>', $ColumnData), '<td></tr>';
$recordNum = 0;
foreach ($reader->generateRecords() as $id => $record) {
    echo "<td>" .$id, ": "; // implode(',', Reader::flattenRecord($record));

    $colNum = 0;
    foreach ($record as $colName => $colVal) {
        if ($colNum++ > 0) {
        	echo "<td>";
        };
        if (is_array($colVal)) {
            echo '[', implode(',', $colVal), ']';
        } else {
            echo $colVal, "</td>";
        }
        
    }

	echo "</tr>";

	if (++$recordNum <= 0) {
//	if (++$recordNum >= 10) {
        break;
    }
}

echo "</table>";

?>

</body>
</html>
