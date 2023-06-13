<?php
require_once("Connection.php");
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    header("Content-Type" , "application/json; charset=utf-8");
    $Stmt = $Conn->prepare("INSERT INTO exp(userid , totalcarbo , needinsulin , foods , sugarinblood , createdAt) VALUES(? , ? , ? , ? , ? , ?)");
    $Stmt->execute(array($_POST["userid"] , $_POST["totalcarbo"] , $_POST["needinsulin"] , $_POST["foods"] , $_POST["sugarinblood"] , $_POST["created_at"]));
    echo json_decode("Success");
}
else if(isset($_GET["do"]))
{
    $Do = $_GET["do"];
    if($Do == "getAll")
    {
        $CatId = $_GET["catid"];
        $Stmt = $Conn->prepare("SELECT * FROM food WHERE catid = ?");
        $Stmt->execute(array($CatId));
        $Data = $Stmt->fetchAll();
        $DataJson = json_encode($Data , JSON_UNESCAPED_UNICODE);
        echo $DataJson;
    }
    else if($Do == "getPosts")
    {
        $Offset = $_GET["Offset"];
        $Type = $_GET["Type"];
        $Stmt = $Conn->prepare("SELECT * FROM posts WHERE type = ? LIMIT 5 OFFSET " . $Offset);
        $Stmt->execute(array($Type));
        $Data = $Stmt->fetchAll();
        $DataJson = json_encode($Data , JSON_UNESCAPED_UNICODE);
        echo $DataJson;
    }
    else if($Do == "getExp2")
    {
        $Userid = $_GET["Userid"];
        $Offset = $_GET["Offset"];
        $Limit = $_GET["Limit"];
        
        $Stmt = $Conn->prepare("SELECT * FROM exp2 WHERE userid = ? LIMIT $Limit OFFSET $Offset ");
        $Stmt->execute(array($Userid));
        $Data = $Stmt->fetchAll();
        echo json_encode($Data);
    }
}
else
{
    
}
?>