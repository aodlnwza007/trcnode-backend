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


/** revise searchText */
if(true){
    $input = $data["searchText"];
    if(strpos($input, ".") === false){
        $input = $input.".trcloud.co";
    }
    $input = str_replace("https://", "", $input);
    $input = str_replace("http://", "", $input);
    $input = explode("/", $input);
    $input = $input[0];
}


/** ping */
if(true){
    $ip = gethostbyname($input);
    if($ip === $input || $ip === "199.59.243.223"){
        $result = [
            "message"   => "Invalid URL '{$input}'",
            "error"     => 1,
        ];
        $answer["result"] = $result;
        $answer["message"] = "Invalid URL '{$input}'";
        $answer["success"] = 0;
        exit(json_encode($answer));
    }
}


/** search */
if(true){
    $search = $pdo2->prep("select * from node where ip = ?", [$ip])->fetch(PDO::FETCH_ASSOC);
}


/** result */
$result = [
    "url"   => $input,
    "ip"    => $ip,
    "status"=> empty($search["ip"]) ? "0" : "1",
    "node"  => empty($search["ip"]) ? "Unknown" : $search["name"],
];

$answer["result"] = $result;
$answer["message"] = "Successfully";
$answer["success"] = 1;
exit(json_encode($answer));

?>