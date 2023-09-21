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

$pdo2->prep("start transaction;");


/** delete */
if(true){
    $pdo2->prep("truncate table node");
}


/** insert */
if(!empty($data["nodes"])){
    $sql = "INSERT into `node` (`name`, `ip`) values ";

    foreach($data["nodes"] as $index => $item){
        $sql .= $index==0?"":",";
        $sql .= "('{$item["name"]}', '{$item["ip"]}')";
    }

    $pdo2->prep($sql);
}

$pdo2->prep("commit;");

$answer["data"] = $data;
$answer["message"] = "Successfully";
$answer["success"] = 1;
exit(json_encode($answer));

?>