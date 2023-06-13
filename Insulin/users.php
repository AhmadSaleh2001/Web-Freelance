<?php
require_once("./init.php");
require_once("./layout/Navbar.php");
if(!isset($_SESSION["isadmin"]))header("location:index.php");
if($_SERVER["REQUEST_METHOD"] == "GET")
{
    if(isset($_GET["do"]))
    {
        $Do = $_GET["do"];
        
        if($Do == "delete" && $_SESSION["isadmin"]==1)
        {
            $Stmt = $Conn->prepare("DELETE FROM users WHERE id = ?");
            $Stmt->execute(array($_GET["id"]));
            header("location:users.php");
        }
        else if($Do == "update")
        {
            $Stmt = $Conn->prepare("SELECT * FROM users WHERE id = ?");
            $Stmt->execute(array($_GET["id"]));
            $Data = $Stmt->fetch();
            ?>
                <div class="Login">
                    <div class="container">
                        <h3 class="MyTitle">تعديل بيانات الحساب</h3>
                        <form class="LoginSignUp" action="?" method="POST">
                            <input type="hidden" name="id" value="<?php echo $Data["id"] ?>" />
                            <div>
                                <label >اسم المستخدم</label>
                                <input type="text" name="username" value="<?php echo $Data["username"] ?>" class="MyInput" required/>
                            </div>

                            <div>
                                <label >البريد الالكتروني</label>
                                <input type="text" name="email" value="<?php echo $Data["email"] ?>" class="MyInput" required/>
                            </div>

                            <div>
                                <label >كلمة المرور</label>
                                <input type="text" name="password" value="<?php echo $Data["password"] ?>" class="MyInput" required/>
                            </div>
                            
                            <div>
                                <label>الوزن</label>
                                <input type="text" name="weight" value="<?php echo $Data["weight"] ?>" class="MyInput"/>
                            </div>

                            <div>
                                <label>الطول</label>
                                <input type="text" name="height" value="<?php echo $Data["height"] ?>" class="MyInput"/>
                            </div>

                            <div>
                                <label>العمر</label>
                                <input type="text" name="age" value="<?php echo $Data["age"] ?>" class="MyInput"/>
                            </div>

                            <div>
                                <label>الجنس</label>
                                <input type="text" name="gender" value="<?php echo $Data["gender"] ?>" class="MyInput" required/>
                            </div>
                            <?php
                                if($_SESSION["isadmin"] == 1)
                                {
                                    ?>
                                    <div>
                                        <label>الرتبة</label>
                                        <select name="isadmin" class="MySelect" id="" required>
                                            <option value=""></option>
                                            <option value="1" <?php echo $Data['isadmin'] == 1?"selected":"" ?> >مدير</option>
                                            <option value="2" <?php echo $Data['isadmin'] == 2?"selected":"" ?>>مساعد</option>
                                            <option value="3" <?php echo $Data['isadmin'] == 3?"selected":"" ?>>مستخدم عادي</option>
                                        </select>
                                    </div>
                                    <?php
                                }
                            ?>

                            <div>
                                <input type="submit" value="حفظ التغييرات"  class="MySubmit"/>
                            </div>
                        </form>
                    </div>
                </div>
            <?php
        }
        else header("Location:index.php");
    }
    else
    {
        if($_SESSION["isadmin"]>2)
        {
            header("Location:index.php");
        }
        $Stmt = $Conn->prepare("SELECT id FROM users");
        $Stmt->execute();
        $All = $Stmt->rowCount();
        $TotalButtons = intval($All / 10) + ($All % 10 != 0);

        


        $Offset = 0;
        if(isset($_GET["offset"]))$Offset = $_GET["offset"];
        $Stmt = $Conn->prepare("SELECT * FROM users LIMIT 10 OFFSET $Offset");
        $Stmt->execute();
        $Data = $Stmt->fetchAll();
        ?>
            <div class="AllUsers">
                <div class="container">
                    <h3 class="MyTitle">المستخدمين</h3>
                    <div style="overflow:auto">
                        <table class="MyTable">
                            <thead>
                                <th>الاسم</th>
                                <th>البريد الالكتروني</th>
                                <th>كلمة المرور</th>
                                <th>الوزن</th>
                                <th>الطول</th>
                                <th>العمر</th>
                                <th>الرتبة</th>
                                <th>عمليات</th>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($Data as $Value)
                                    {
                                        if($Value["isadmin"] < $_SESSION["isadmin"] || $Value["id"] == $_SESSION["id"])continue;
                                        ?>
                                        <tr>
                                            <td><?php echo $Value["username"] ?></td>
                                            <td><?php echo $Value["email"] ?></td>
                                            <td>
                                                <?php 
                                                    if($_SESSION["isadmin"] == 1)echo $Value["password"];
                                                    else echo "";
                                                ?>
                                            </td>
                                            <td><?php echo $Value["weight"] ?></td>
                                            <td><?php echo $Value["height"] ?></td>
                                            <td><?php echo $Value["age"] ?></td>
                                               <td>
                                                <?php
                                                    $Res = "";
                                                    if($Value["isadmin"] == 1)$Res = "مدير";
                                                    else if($Value["isadmin"] == 2)$Res = "مساعد";
                                                    else $Res = "مستخدم عادي";
                                                    echo $Res;
                                                ?>
                                            </td>
                                            <td class="Buttons">
                                                <a href="exp.php?do=showExp&userid=<?php echo $Value["id"] ?>">تجارب الانسولين</a>
                                                <a href="exp.php?do=showExp&userid=<?php echo $Value["id"] ?>&type=0">تجارب الكربوهيدرات</a>
                                                <a href="exp.php?do=showMeasu&userid=<?php echo $Value["id"] ?>">فحوصات</a>
                                                <?php
                                                    if($_SESSION["isadmin"] == 1)
                                                    {
                                                        ?>
                                                            <a href="users.php?do=update&id=<?php echo $Value["id"] ?>">تعديل</a>
                                                            <a class="ConfirmBtns" href="users.php?do=delete&id=<?php echo $Value["id"] ?>" style="color:red;font-weight:bold">حذف</a>
                                                        <?php
                                                    }
                                                ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                        <a href="SignUp.php">اضافة مستخدم</a>           
                    </div>
                    <div class="ShowSpecificData">
                        <?php
                            for($i = 1;$i<=$TotalButtons;$i++)
                            {
                                echo "<a class='" . (($i - 1)*10 == $Offset?'Active':'') . "' href='" . "users.php?offset=" . ($i - 1)*10 . "'>$i</a>";
                            }
                        ?>
                    </div>
                </div>
            </div>
        <?php
    }
}
else
{
    $id = $_POST["id"];
    $Username = $_POST["username"];
    $Email = $_POST["email"];
    $Password = $_POST["password"];
    $Weight = $_POST["weight"];
    $Height = $_POST["height"];
    $Age = $_POST["age"];
    $Gender = $_POST["gender"];
    $IsAdmin = $_POST["isadmin"];

    $Stmt = $Conn->prepare("UPDATE users set username = ? , email = ? , password = ? , weight = ? , height = ? , age = ? , gender = ? , isadmin = ? WHERE id = ?");
    $Stmt->execute(array($Username , $Email , $Password , $Weight , $Height , $Age , $Gender , $IsAdmin , $id));

    header("Location:users.php");
}

?>

<?php
require_once("layout/Footer.php")
?>