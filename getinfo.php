<?php
require_once("data.php");

$ArrayURL = explode('/', $_SERVER[REQUEST_URI]); // correction : change split to explode  | reason : split function is deprecated.
$id = $ArrayURL[2]; // correction : change array index 1 to index 2 : when explode '/getinfo/111', the result array is [0 => '',1 => getinfo, ]
$data = new baseObj(); // correction : change dataObj to baseObj | reason : dataObj class doesn't exist.
$data->setTable('property'); // correction : need to set the table value  | reason : because default value of tabal in baseObj class is null

if (is_object($data) == true) {
    $status = '200 OK';
    $status_header = "HTTP/1.1 $status"; // correction : change single quotes to double quotes  | reason : php variables within single quotes won't be recognize
}

header($status_header);
echo json_encode($data->getAll($id));

?>