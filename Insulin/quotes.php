<?php
require_once("./init.php");
require_once("./layout/Navbar.php");
if(!isset($_SESSION["username"]) || $_SESSION["isadmin"] > 2)header("Location:index.php");
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
                        <h3 class="MyTitle">اضافة اقتباس جديد</h3>
                        <form class="LoginSignUp" action="?do=ConfirmAdd" method="POST">
                            <div>
                                <label >نص الاقتباس</label>
                                <textarea name="name" id="editor" cols="30" rows="10" class="MyTextArea" style="dir:rtl"></textarea>
                            </div>
                            <div>
                                <input type="submit" value="اضافة"  class="MySubmit"/>
                            </div>
                        </form>
                    </div>
                </div>
                <script src="https://cdn.ckeditor.com/ckeditor5/33.0.0/classic/ckeditor.js"></script>
                <script src="./layout/scripts/Editor.js"></script>

            <?php
        }
        else if($Do == "delete" && $_SESSION["isadmin"]<=2)
        {
            $Stmt = $Conn->prepare("DELETE FROM quotes WHERE id = ?");
            $Stmt->execute(array($_GET["id"]));
            header("location:quotes.php");
        }
        else if($Do == "update" && $_SESSION["isadmin"]<=2)
        {
            $Stmt = $Conn->prepare("SELECT * FROM quotes WHERE id = ?");
            $Stmt->execute(array($_GET["id"]));
            $Data = $Stmt->fetch();
            ?>
               <div class="Login">
                    <div class="container">
                        <h3 class="MyTitle">تعديل اقتباس</h3>
                        <form class="LoginSignUp" action="?do=ConfirmUpdate" method="POST">
                            <input type="hidden" value="<?php echo $Data["id"] ?>" name="id" class="MyInput" />
                            <div>
                                <label >نص الاقتباس</label>
                                <textarea name="name" id="editor" cols="30" rows="10" class="MyTextArea" style="dir:rtl">
                                    <?php echo $Data["data"]; ?>
                                </textarea>
                            </div>
                            <div>
                                <input type="submit" value="حفظ التغييرات"  class="MySubmit"/>
                            </div>
                        </form>
                    </div>
                </div>
                <script src="https://cdn.ckeditor.com/ckeditor5/33.0.0/classic/ckeditor.js"></script>
                <script src="./layout/scripts/Editor.js"></script>
            <?php
        }
        else header("Location:index.php");
    }
    else
    {
        if($_SESSION["isadmin"] > 2)
        {
            header("Location:index.php");
        }
        $Stmt = $Conn->prepare("SELECT * FROM quotes");
        $Stmt->execute();
        $Data = $Stmt->fetchAll();
        ?>
             <div class="AllUsers">
                <div class="container">
                    <h3 class="MyTitle">الاقتباسات</h3>
                    <div style="overflow:auto">
                        <table class="MyTable">
                            <thead>
                                <th>رقم الاقتباس</th>
                                <th>اسم الاقتباس</th>
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
                                            <td><?php echo $Value["data"] ?></td>
                                            <td class="Buttons">
                                                <a href="quotes.php?do=update&id=<?php echo $Value["id"] ?>">تعديل</a>
                                                <a class="ConfirmBtns" href="quotes.php?do=delete&id=<?php echo $Value["id"] ?>">حذف</a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                        <a href="quotes.php?do=AddNew">اضافة اقتباس</a>
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
        $Stmt = $Conn->prepare("INSERT INTO quotes(data) VALUES (?)");
        $Stmt->execute(array($Name));
    }
    else
    {
        $Name = $_POST["name"];
        $id = $_POST["id"];
        $Stmt = $Conn->prepare("UPDATE quotes set data = ? WHERE id = ?");
        $Stmt->execute(array($Name , $id));
    }

    header("Location:quotes.php");
}

?>

<?php
require_once("layout/Footer.php")
?>