<?php
require_once("./init.php");
require_once("./layout/Navbar.php");
// if(isset($_SESSION["username"]))header("Location:index.php");
if($_SERVER["REQUEST_METHOD"] == "POST")
{
    $Username = $_POST["username"];
    $Email = $_POST["email"];
    $Password = $_POST["password"];
    $Weight = $_POST["weight"];
    $Height = $_POST["height"];
    $Age = $_POST["age"];
    $Gender = $_POST["gender"];

    $Stmt = $Conn->prepare("SELECT email FROM users WHERE LOWER(email) = LOWER(?)");
    $Stmt->execute(array($Email));
    $Rows = $Stmt->rowCount();
    if($Rows > 0)
    {
        echo 
        '<script>
            alert("هذا البريد الالكتروني مستخدم");
        </script>';
        
    }
    else
    {
        $Stmt = $Conn->prepare("INSERT INTO users(username , email , password , weight , height , age , gender) VALUES (? , ? , ? , ? , ? , ? , ?)");
        $Stmt->execute(array($Username , $Email , $Password , $Weight , $Height , $Age , $Gender));

        header("Location:index.php");
    }

    
}
?>
     <div class="Login">
        <div class="container">
            <h3 class="MyTitle">انشاء الحساب</h3>
            <form class="LoginSignUp" action="?" method="POST">

                <div>
                    <label >اسم المستخدم</label>
                    <input type="text" name="username" class="MyInput" required/>
                </div>

                <div>
                    <label >البريد الالكتروني</label>
                    <input type="text" name="email" class="MyInput" required/>
                </div>

                <div>
                    <label >كلمة المرور</label>
                    <input type="password" name="password" class="MyInput" required/>
                </div>
                
                <div>
                    <label>الوزن</label>
                    <input type="number" step="0.01" name="weight" class="MyInput"/>
                </div>

                <div>
                    <label>الطول</label>
                    <input type="number" step="0.01" name="height" class="MyInput"/>
                </div>

                <div>
                    <label>العمر</label>
                    <input type="number" step="0.01"name="age" class="MyInput"/>
                </div>

                <div>
                    <label>الجنس</label>
                    <input type="text" name="gender" class="MyInput" required/>
                </div>

                <div>
                    <input type="submit" value="<?php if(isset($_SESSION["isadmin"])) echo 'اضافة مستخدم'; else echo 'انشاء حساب' ?>"  class="MySubmit"/>
                </div>
            </form>
        </div>
    </div>

<?php
require_once("layout/Footer.php")
?>