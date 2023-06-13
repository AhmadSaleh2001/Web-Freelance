<?php
require_once("./init.php");
require_once("./layout/Navbar.php");
if($_SERVER["REQUEST_METHOD"] == "GET")
{
    if(isset($_GET["do"]))
    {
        if(isset($_SESSION["isadmin"]) && $_SESSION["isadmin"]>2)header("location:post.php");
        $Do = $_GET["do"];
        if($Do == "Update")
        {
            $Stmt = $Conn->prepare("SELECT * FROM posts WHERE id = ?");
            $Stmt->execute(array($_GET["id"]));
            $Data = $Stmt->fetch();

            $Stmt = $Conn->prepare("SELECT * FROM categoriesposts");
            $Stmt->execute();
            $CategoriesPosts = $Stmt->fetchAll();
            ?>
                  <div class="Login">
                    <div class="container">
                    <h3 class="MyTitle">تعديل منشور</h3>
                    <form class="LoginSignUp" action="?do=ConfirmUpdate&id=<?php echo $Data["id"] ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="oldimg" value="<?php echo $Data["img"] ?>">
                        <div>
                            <label >العنوان</label>
                            <input type="text" value="<?php echo $Data["title"] ?>" name="title" class="MyInput" required/>
                        </div>

                        <div>
                            <label >المحتوى</label>
                            <textarea name="body" id="editor" cols="30" rows="10" class="MyTextArea">
                                <?php echo $Data["body"] ?> 
                            </textarea>
                        </div>
                        <div>
                            <div style="width:100%">
                                <label>الصورة</label>
                                <input type="file" name="img" class="MyInput"/>
                            </div>
                            <img src="uploads/Posts/<?php echo $Data["img"] ?>" alt="" style="max-width:150px;border-radius:10px">
                        </div>
                        <select name="type" class="MySelect" id="" required>
                            <option value=""></option>
                            <?php
                                
                                foreach($CategoriesPosts as $Value)
                                {
                                    echo "<option value='" . $Value["id"] . "'" . ($Value['id'] == $Data['type']?'selected':'') . ">" . $Value["categoryname"] . "</option>";
                                }
                            ?>
                        </select>

                        <div>
                            <input type="submit" value="حفظ التغييرات" class="MySubmit">
                        </div>
                    </form>
                    </div>
                </div>
                <script src="https://cdn.ckeditor.com/ckeditor5/33.0.0/classic/ckeditor.js"></script>
                <script src="./layout/scripts/Editor.js"></script>
            <?php   
        }
        else if($Do == "ConfirmDelete")
        {
            $imgurl = $_GET["img"];
            if($imgurl!="default.jpg")unlink("uploads/Posts/" . $imgurl);
            $Stmt = $Conn->prepare("DELETE FROM posts WHERE id = ?");
            $Stmt->execute(array($_GET["id"]));
            header("location:post.php?Who=1");
        }
    }
    else
    {   
        $Type = -1;
        if(isset($_GET["Who"]))
        {
            $Type = $_GET["Who"];
        }

        $Stmt = $Conn->prepare("SELECT * FROM posts where type = ? LIMIT 5");
        $Stmt->execute(array($Type));
        $Data = $Stmt->fetchAll();

        $Stmt = $Conn->prepare("SELECT * FROM categoriesposts");
        $Stmt->execute();
        $CategoriesPosts = $Stmt->fetchAll();
        ?>
            <div class="PostsAndTips Login">
                
                <h3 class="MyTitle">اخر المنشورات</h3>
                <div class="container">
                    <?php
                        if(isset($_SESSION["isadmin"]) && $_SESSION["isadmin"]<=2)
                        {
                            ?>
                                <div class="SharePost">
                                    <h2>نشر معلومة <i class="fa-solid fa-lightbulb" style="color:yellow"></i></h2>
                                    <form class="LoginSignUp" action="?do=ConfirmAdd" method="POST" enctype="multipart/form-data">
                                        <div>
                                            <label >العنوان</label>
                                            <br />
                                            <input type="text" name="title" class="MyInput" required/>
                                        </div>

                                        <div>
                                            <label>المحتوى</label>
                                            <br />
                                            <textarea name="body" id="editor" cols="30" rows="10" class="MyTextArea" style="dir:rtl"></textarea>
                                        </div>
                                        <div>
                                            <label >النوع</label>
                                            <br />
                                            <select name="type" class="MySelect" required>
                                                <option value=""></option>
                                                <?php
                                                    
                                                    foreach($CategoriesPosts as $Value)
                                                    {
                                                        echo "<option value='" . $Value["id"] . "'>" . $Value["categoryname"] . "</option>";
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <div>
                                            <label>الصورة</label>
                                            <br />
                                            <input type="file" name="img" class="MyInput"/>
                                        </div>
                                        <div>
                                            <input type="submit" class="MySubmit" value="نشر">
                                        </div>
                                    </form>
                                </div>
                                <script src="https://cdn.ckeditor.com/ckeditor5/33.0.0/classic/ckeditor.js"></script>
                                <script src="./layout/scripts/Editor.js"></script>
                            <?php
                        }
                        
                    ?>
                    <div class="TwoButtons">
                        <?php
                            foreach($CategoriesPosts as $Value)
                            {
                                echo "<a href='post.php?Who=" . $Value['id'] . "' class='" . ($Type == $Value['id']?'Active':'') . "'>" . $Value['categoryname'] . "</a>";
                            }
                        ?>
                    </div>
                    <div class="Body">
                        <?php
                            foreach($Data as $Post)
                            {
                                ?>
                                <div class="Post">
                                    <div class="Header">
                                        <h3><?php echo $Post["title"] ?></h3>
                                        <?php
                                            if(isset($_SESSION["isadmin"]) && $_SESSION["isadmin"]<=2)
                                            {
                                                ?>
                                                    <div class="Operations">
                                                        <a href="?do=Update&id=<?php echo $Post["id"] ?>">تعديل</a>
                                                        <a class="ConfirmBtns" href="?do=ConfirmDelete&id=<?php echo $Post["id"] ?>&img=<?php echo $Post["img"]; ?>">حذف</a>                            
                                                    </div>
                                                <?php
                                            }
                                        ?>
                                    </div>
                                    <p><?php echo $Post["body"] ?></p>
                                    <img src="uploads/Posts/<?php echo $Post["img"]; ?>" class="ImagePost" alt="" />
                                </div>
                                <?php
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
    if(isset($_SESSION["isadmin"]) && $_SESSION["isadmin"]>2)header("location:post.php?Who=1");
    if(isset($_GET["do"]))
    {
        $Do = $_GET["do"];
        if($Do == "ConfirmAdd")
        {
            $Data = [];
            $ImageUrl =  str_replace(' ', '_', $_FILES["img"]);
            if($ImageUrl['name']!="") { 
                $ImageName = rand()%10000 . $ImageUrl["name"];
                function compressedImage($source, $path, $quality) {

                    $info = getimagesize($source);
        
                    if ($info['mime'] == 'image/jpeg') 
                        $image = imagecreatefromjpeg($source);
        
                    elseif ($info['mime'] == 'image/gif') 
                        $image = imagecreatefromgif($source);
        
                    elseif ($info['mime'] == 'image/png') 
                        $image = imagecreatefrompng($source);
        
                    imagejpeg($image, $path, $quality);
        
                }
                array_push($Data , $ImageName);
                compressedImage($_FILES["img"]["tmp_name"],"uploads/Posts/" . $ImageName,10);
            }
            array_push($Data , $_POST["title"] , $_POST["body"] , $_SESSION["id"] , $_POST["type"]);
            $Query = "";
            if(count($Data) == 5)
            {
                $Query = "INSERT INTO posts(img , title , body , userid , type) VALUES (? , ? , ? , ? , ?)";
            }
            else $Query = "INSERT INTO posts(title , body , userid , type) VALUES (? , ? , ? , ?)";
            
            $Stmt = $Conn->prepare($Query);
            $Stmt->execute($Data);
        }
        else
        {
            $Data = [];
            $ImageUrl =  str_replace(' ', '_', $_FILES["img"]);
            if($ImageUrl['name']!="") { 
                if($_POST["oldimg"]!="default.jpg")
                {
                    unlink("uploads/Posts/" . $_POST["oldimg"]);
                }
                $ImageName = rand()%10000 . $ImageUrl["name"];
                function compressedImage($source, $path, $quality) {

                    $info = getimagesize($source);
        
                    if ($info['mime'] == 'image/jpeg') 
                        $image = imagecreatefromjpeg($source);
        
                    elseif ($info['mime'] == 'image/gif') 
                        $image = imagecreatefromgif($source);
        
                    elseif ($info['mime'] == 'image/png') 
                        $image = imagecreatefrompng($source);
        
                    imagejpeg($image, $path, $quality);
        
                }
                array_push($Data , $ImageName);
                compressedImage($_FILES["img"]["tmp_name"],"uploads/Posts/" . $ImageName,10);
            }
            array_push($Data , $_POST["title"] , $_POST["body"] , $_POST["type"] , $_GET["id"]);
            $Query = "";
            if(count($Data) == 5)
            {
                $Query = "UPDATE posts SET img = ? ,title = ? , body = ? , type = ? WHERE id = ?";
            }
            else $Query = "UPDATE posts SET title = ? , body = ? , type = ? WHERE id = ?";
            
            $Stmt = $Conn->prepare($Query);
            $Stmt->execute($Data);
        }
    }
    header("location:post.php?Who=1");
}
?>
<?php
    if(isset($_SESSION["isadmin"]))
    {
        $IsAdmin = $_SESSION["isadmin"];
    }
    else
    {
        $IsAdmin = 9999;
    }
    $TypeForJs = -1;
    if(isset($_GET["Who"]))$TypeForJs = $_GET["Who"];
?>

<script>
    let IsAdmin = <?php echo $IsAdmin; ?>;
    let type = <?php echo $TypeForJs; ?>;
    let Offset = <?php if(isset($Data)) echo count($Data)?>;
</script>


<script src="layout/scripts/Timeline.js"></script>
<?php
require_once("./layout/Footer.php");
?>