<?php 
require_once("db.php");

/** check table if exist */
if(true){
    $model = [
        [ "column" => "name", "dataType" => "varchar(255)" ],
        [ "column" => "ip", "dataType" => "varchar(50)" ],
    ];

    $pdo2->ifExist("node", $model);
}


$result = $pdo2->prep("select * from node")->fetchAll(PDO::FETCH_ASSOC);


$answer["result"] = $result;
$answer["message"] = "Successfully";
$answer["success"] = 1;
exit(json_encode($answer));

?>