<?php
require_once("./init.php");
require_once("layout/Navbar.php");
if(isset($_SESSION["username"]))header("Location:index.php");
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $Email = $_POST["email"];
    $Password = $_POST["password"];

    $Stmt = $Conn->prepare("SELECT * FROM users where LOWER(email) = LOWER(?) AND password = ?");
    $Stmt->execute(array($Email , $Password));
    $Data = $Stmt->fetch();
    $Rows = $Stmt->rowCount();

    if($Rows > 0)
    {
        $_SESSION["id"] = $Data["id"];
        $_SESSION["username"] = $Data["username"];
        $_SESSION["isadmin"] = $Data["isadmin"];
        $_SESSION["email"] = $Data["email"];
        header("Location:index.php");
    }
    else
    {
        header("Location:Login.php");
    }
    
}
?>

<div class="Login">
    <div class="container">
        <h3 class="MyTitle">تسجيل الدخول</h3>
        <form class="LoginSignUp" action="?" method="POST">
            <div>
                <label >البريد الالكتروني</label>
                <input type="text" name="email" class="MyInput" />
            </div>

            <div>
                <label >كلمة المرور</label>
                <input type="password" name="password" class="MyInput" />
            </div>
            <div>
                <input type="submit" value="تسجيل الدخول"  class="MySubmit"/>
            </div>
        </form>
    </div>
</div>
    
<?php
require_once("layout/Footer.php")
?>