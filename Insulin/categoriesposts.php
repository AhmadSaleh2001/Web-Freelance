<?php
require_once("./init.php");
require_once("./layout/Navbar.php");
if(!isset($_SESSION["username"]))header("Location:index.php");
if($_SERVER["REQUEST_METHOD"] == "GET")
{
    if(isset($_GET["do"]))
    {
        $Do = $_GET["do"];

        if($Do == "AddNew" && $_SESSION["isadmin"]<=2)
        {
            ?>
                <div class="Login">
                    <div class="container">
                        <h3 class="MyTitle">اضافة صنف منشور</h3>
                        <form class="LoginSignUp" action="?do=ConfirmAdd" method="POST">
                            <div>
                                <label >الاسم</label>
                                <input type="text"  name="name" class="MyInput" />
                            </div>
                            <div>
                                <input type="submit" value="اضافة"  class="MySubmit"/>
                            </div>
                        </form>
                    </div>
                </div>

            <?php
        }
        else if($Do == "delete" && $_SESSION["isadmin"]<=2)
        {
            $Stmt = $Conn->prepare("DELETE FROM categoriesposts WHERE id = ?");
            $Stmt->execute(array($_GET["id"]));
            header("location:categoriesposts.php");
        }
        else if($Do == "update" && $_SESSION["isadmin"]<=2)
        {
            $Stmt = $Conn->prepare("SELECT * FROM categoriesposts WHERE id = ?");
            $Stmt->execute(array($_GET["id"]));
            $Data = $Stmt->fetch();
            ?>
               <div class="Login">
                    <div class="container">
                        <h3 class="MyTitle">تعديل الصنف</h3>
                        <form class="LoginSignUp" action="?do=ConfirmUpdate" method="POST">
                            <input type="hidden" value="<?php echo $Data["id"] ?>" name="id" class="MyInput" />
                            <div>
                                <label >الاسم</label>
                                <input type="text" value="<?php echo $Data["categoryname"] ?>" name="name" class="MyInput" />
                            </div>
                            <div>
                                <input type="submit" value="حفظ التغييرات"  class="MySubmit"/>
                            </div>
                        </form>
                    </div>
                </div>

            <?php
        }
        else header("Location:categoriesposts.php");
    }
    else
    {
        if($_SESSION["isadmin"] > 2)
        {
            header("Location:index.php");
        }
        $Stmt = $Conn->prepare("SELECT * FROM categoriesposts");
        $Stmt->execute();
        $Data = $Stmt->fetchAll();
        ?>
             <div class="AllUsers">
                <div class="container">
                    <h3 class="MyTitle">اصناف المنشورات</h3>
                    <div style="overflow:auto">
                        <table class="MyTable">
                            <thead>
                                <th>رقم الصنف</th>
                                <th>اسم الصنف</th>
                                <th>عمليات</th>
                            </thead>
                            <tbody>
                                <?php
                                    $Cnt = 0;
                                    foreach($Data as $Value)
                                    {
                                        ?>
                                        <tr>
                                            <td><?php echo ++$Cnt ?></td>
                                            <td><?php echo $Value["categoryname"] ?></td>
                                            <td class="Buttons">
                                                <a href="?do=update&id=<?php echo $Value["id"] ?>">تعديل</a>
                                                <a class="ConfirmBtns" href="?do=delete&id=<?php echo $Value["id"] ?>">حذف</a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                        <a href="categoriesposts.php?do=AddNew">اضافة صنف منشور</a>
                    </div>
                </div>
            </div>
        <?php
    }
}
else
{
    $Do = $_GET["do"];
    if($Do == "ConfirmAdd")
    {
        $Name = $_POST["name"];
        $Stmt = $Conn->prepare("INSERT INTO categoriesposts(categoryname) VALUES (?)");
        $Stmt->execute(array($Name));
    }
    else
    {
        $Name = $_POST["name"];
        $id = $_POST["id"];
        $Stmt = $Conn->prepare("UPDATE categoriesposts set categoryname = ? WHERE id = ?");
        $Stmt->execute(array($Name , $id));
    }

    header("Location:categoriesposts.php");
}

?>

<?php
require_once("layout/Footer.php")
?>