<?php 
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");

require_once("../config.php");

class myDB{

    public $_detail = [];
    public $models;

    public function __construct($_db){
    
        if( isset($_db->_detail) ){
            $info = [];
            $info["database_type"] 	= $_db->_detail["database_type"];
            $info["database_name"] 	= $_db->_detail["database_name"];
            $info["server"] 		= $_db->_detail["server"];
            $info["username"] 		= $_db->_detail["username"];
            $info["password"] 		= $_db->_detail["password"];
        }else{
            $info = [];
            $info["database_type"] 	= $_db["database_type"];
            $info["database_name"] 	= $_db["database_name"];
            $info["server"] 		= $_db["server"];
            $info["username"] 		= $_db["username"];
            $info["password"] 		= $_db["password"];
            $this->_detail			= $info;
        }
        
        $input = [	"database_type"	=>$info["database_type"],
                    "database_name"	=>$info["database_name"],
                    "server"		=>$info["server"],
                    "username"		=>$info["username"],
                    "password"		=>$info["password"],
                ];
                
        try{
            $this->pdo  = new PDO($input["database_type"].':host='.$input["server"].';dbname='.$input["database_name"].';charset=utf8', $input["username"], $input["password"]);	
        }catch (PDOException $e) {
            if($e->getMessage() == "SQLSTATE[HY000] [1049] Unknown database '{$info["database_name"]}'"){
                $pdo = new PDO($input["database_type"].':host='.$input["server"].';dbname='.';charset=utf8', $input["username"], $input["password"]);	
                $pdo->query("CREATE DATABASE {$input["database_name"]};");
            }else{
                $answer["message"] = "Connection failed: " . $e->getMessage();
                $answer["success"] = 0;
                exit(json_encode($answer));
            }
        }
    }


    public function ifExist($table="", $model){
        if(empty($table)){
            $answer["message"] = "Table name can't be empty";
            $answer["success"] = 0;
            exit(json_encode($answer));
        }

        $check = $this->prep("SHOW TABLES LIKE '{$table}'")->fetchColumn();

        if(empty($check)){
            $this->createTable($table, $model);
        }
    }



    public function createTable($table="", $model){
        if(empty($table)){
            $answer["message"] = "Table name can't be empty";
            $answer["success"] = 0;
            exit(json_encode($answer));
        }

        if(empty($model)){
            $answer["message"] = "Model '{$table}' is not exists";
            $answer["success"] = 0;
            exit(json_encode($answer));
        }

        $detail = "";
        $key = "";

        foreach($model as $index => $item){
            $detail .= $index==0?"":",";
            $detail .= "`{$item["column"]}` {$item["dataType"]}";

            if($item["key"]){
                $key = ", KEY `{$item["key"]}` (`{$item["key"]}`)";
            }
        }

        $this->prep("create table `{$table}`( {$detail} {$key}) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
    }



    public function prep($sql,$exe=[]){

        $sth = $this->pdo->prepare($sql);
        $chk = $sth->execute($exe);

        if( empty($chk) ){

            foreach( array_reverse($exe) as $key => $item ){
                $sql = str_replace($key, "'{$item}'", $sql);
            }

            exit(json_encode(["success"=>0, "sql"=>$sql, "message"=>"Error: ".json_encode($sth->errorInfo(),JSON_UNESCAPED_UNICODE)."<br>----------<br>".$sql]));
        }

        return $sth;
    }

}


$pdo2 =  new myDB([
    'database_type' => 'mysql',
    'database_name' => $database,
    'server' 		=> $hostname,
    'username' 		=> $username,
    'password' 		=> $password,
]);


if (!(isset($_POST['json']))){
    $answer["post"] = $_POST;
    $answer["message"] = "No data is received!";
    exit(json_encode($answer));
}

$data = json_decode($_POST['json'],true);
?>