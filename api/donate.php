<?php 
require_once("db.php");

/** check table if exist */
if(true){
    $model = [
        [ "column" => "name", "dataType" => "varchar(255)" ],
        [ "column" => "amount", "dataType" => "decimal(11,2)" ],
    ];

    $pdo2->ifExist("donate", $model);
}


$result = $pdo2->prep("select * from donate order by amount desc limit 10")->fetchAll(PDO::FETCH_ASSOC);


$answer["result"] = $result;
$answer["message"] = "Successfully";
$answer["success"] = 1;
exit(json_encode($answer));

?>