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
            $Stmt = $Conn->prepare("SELECT * FROM categories");
            $Stmt->execute();
            $AllCats = $Stmt->fetchAll();
            ?>
                <div class="Login">
                    <div class="container">
                    <h3 class="MyTitle">اضافة طبق</h3>
                    <form class="LoginSignUp" action="?do=ConfirmAdd" method="POST" enctype="multipart/form-data">

                        <div>
                            <label >اسم الطبق</label>
                            <input type="text" name="name" class="MyInput" required/>
                        </div>

                        <div>
                            <label >(غم)الوزن</label>
                            <input type="number" step="0.01" name="weight" class="MyInput" required/>
                        </div>

                        <div>
                            <label >(غم)الكربوهيدرات</label>
                            <input type="number" step="0.01" name="carbo" class="MyInput" required/>
                        </div>
                        
                        <div>
                            <label>الكمية</label>
                            <input type="text" name="quantity" class="MyInput"/>
                        </div>

                        <div>
                            <label>الصورة</label>
                            <input type="file" name="img" class="MyInput"/>
                        </div>

                        <div>
                            <label>الصنف</label>
                            <select name="catid" id="" required class="MySelect">
                                <option value="" selected="true"></option>
                            <?php
                                foreach($AllCats as $Value)
                                {
                                    ?>
                                        <option value="<?php echo $Value["id"] ?>"><?php echo $Value["name"] ?></option>
                                    <?php
                                }
                            ?>
                            </select>
                        </div>

                        <div>
                            <input type="submit" value="اضافة الطبق" class="MySubmit">
                        </div>
                    </form>
                    </div>
                </div>
            <?php
        }
        else if($Do == "delete" && $_SESSION["isadmin"]<=2)
        {
            $Img = $_GET["img"];
            if($Img!="default.jpg")unlink("uploads/" . $Img);
            $Stmt = $Conn->prepare("DELETE FROM food WHERE id = ?");
            $Stmt->execute(array($_GET["id"]));
            $_SESSION["NumberOfFoods"]--;
            header("location:food.php");
        }
        else if($Do == "update" && $_SESSION["isadmin"]<=2)
        {
            $Stmt = $Conn->prepare("SELECT * FROM food WHERE id = ?");
            $Stmt->execute(array($_GET["id"]));
            $Data = $Stmt->fetch();
            ?>
                <div class="Login">
                    <div class="container">
                    <h3 class="MyTitle">تعديل طبق</h3>
                    <form class="LoginSignUp" action="?do=ConfirmUpdate&id=<?php echo $Data["id"] ?>" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="oldimg" value="<?php echo $Data["img"] ?>">
                        <div>
                            <label >اسم الطبق</label>
                            <input type="text" value="<?php echo $Data["name"] ?>" name="name" class="MyInput" required/>
                        </div>

                        <div>
                            <label >(غم)الوزن</label>
                            <input type="text" value="<?php echo $Data["weight"] ?>" name="weight" class="MyInput" required/>
                        </div>

                        <div>
                            <label >(غم)الكربوهيدرات</label>
                            <input type="text" value="<?php echo $Data["carbo"] ?>" name="carbo" class="MyInput" required/>
                        </div>
                        
                        <div>
                            <label>الكمية</label>
                            <input type="text" value="<?php echo $Data["quantity"] ?>" name="quantity" class="MyInput"/>
                        </div>

                        <div>
                            <div style="width:100%">
                                <label>الصورة</label>
                                <input type="file" name="img" class="MyInput"/>
                            </div>
                            <img src="uploads/<?php echo $Data["img"] ?>" alt="" style="max-width:150px;border-radius:10px">
                        </div>

                        <div>
                            <label>الصنف</label>
                            <select name="catid" id="" required class="MySelect">
                                <option value="" selected="true"></option>
                            <?php
                                $Stmt = $Conn->prepare("SELECT * FROM categories");
                                $Stmt->execute();
                                $AllCats = $Stmt->fetchAll();
                                foreach($AllCats as $Value)
                                {
                                    $Yes = $Value["id"] == $Data["catid"];
                                    ?>
                                        <option value="<?php echo $Value["id"];?>" <?php if($Yes) echo 'selected'  ?> ><?php echo $Value["name"] ?></option>
                                    <?php
                                }
                            ?>
                            </select>
                        </div>

                        <div>
                            <input type="submit" value="حفظ التغييرات" class="MySubmit">
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
        if($_SESSION["isadmin"] > 2)
        {
            header("Location:index.php");
        }
        $ShowSpecific = -1;
        $Offset = 0;
        $Data = [];
        if(isset($_GET["ShowSpecific"]))$ShowSpecific = $_GET["ShowSpecific"];
        if(isset($_GET["Offset"]))$Offset = $_GET["Offset"];

        $TotalButtons = 0;
        if($ShowSpecific != -1)
        {
            $Stmt = $Conn->prepare("SELECT * FROM food WHERE catid = ?");
            $Stmt->execute(array($ShowSpecific));
            $Total = $Stmt->rowCount();
            $TotalButtons = intval($Total/10) + ($Total%10 != 0);

            array_push($Data , $ShowSpecific);
            $Stmt = $Conn->prepare("SELECT * FROM food WHERE catid = ? LIMIT 10 OFFSET $Offset");
        }
        else 
        {
            $Stmt = $Conn->prepare("SELECT * FROM food");
            $Stmt->execute();
            $Total = $Stmt->rowCount();

            $TotalButtons = $Total/10 + ($Total%10 != 0);
            $Stmt = $Conn->prepare("SELECT * FROM food LIMIT 10 OFFSET $Offset");
        }
        
        
        $Stmt->execute($Data);
        $Data = $Stmt->fetchAll();
        
        ?>
         <div class="AllUsers">
                <div class="container">
                    <h3 class="MyTitle">الاطباق</h3>
                    <div style="overflow:auto">
                        <table class="MyTable">
                            <thead>
                                <th>الاسم</th>
                                <th>الوزن</th>
                                <th>الكربوهيدرات</th>
                                <th>الكمية</th>
                                <th>الصورة</th>
                                <th>عمليات</th>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($Data as $Value)
                                    {
                                        ?>
                                        <tr>
                                            <td><?php echo $Value["name"] ?></td>
                                            <td><?php echo $Value["weight"] ?></td>
                                            <td><?php echo $Value["carbo"] ?></td>
                                            <td><?php echo $Value["quantity"] ?></td>
                                            <td>
                                                <img src="uploads/<?php echo $Value["img"] ?>" alt="" style="max-width:80px" />
                                            </td>
                                            <td class="Buttons">
                                                <a href="food.php?do=update&id=<?php echo $Value["id"] ?>">تعديل</a>
                                                <a class="ConfirmBtns" href="food.php?do=delete&id=<?php echo $Value["id"] ?>&img=<?php echo $Value["img"] ?>">حذف</a>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                ?>
                            </tbody>
                        </table>
                        <a href="food.php?do=AddNew">اضافة طبق</a>
                    </div>
                    <div class="ShowSpecificData">
                        <?php
                            for($i = 1;$i<=$TotalButtons;$i++)
                            {
                                echo "<a class='" . (($i - 1)*10 == $Offset?'Active':'') . "' href='" . (~$ShowSpecific?"?ShowSpecific=" . $ShowSpecific . "&":"?") . "Offset=" . ($i - 1)*10 . "'>$i</a>";
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
            compressedImage($_FILES["img"]["tmp_name"],"uploads/" . $ImageName,10);
        }
        array_push($Data , $_POST["name"] , $_POST["weight"] , $_POST["carbo"] , $_POST["quantity"] , $_POST["catid"]);
        $Query = "";
        
        if(count($Data) == 6)
        {
            $Query = "INSERT INTO food(img , name , weight , carbo , quantity , catid) VALUES (? , ? , ? , ? , ? , ?)";
        }
        else $Query = "INSERT INTO food(name , weight , carbo, quantity , catid) VALUES (? , ? , ? , ? , ?)";
        $_SESSION["NumberOfFoods"]++;
        $Stmt = $Conn->prepare($Query);
        $Stmt->execute($Data);
    }
    else
    {
        $Data = [];
        $ImageUrl =  str_replace(' ', '_', $_FILES["img"]);
        if($ImageUrl['name']!="") { 
            $Old = $_POST["oldimg"];
            if($Old!="default.jpg")unlink("uploads/" . $Old);

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
            compressedImage($_FILES["img"]["tmp_name"],"uploads/" . $ImageName,10);
        }
        array_push($Data , $_POST["name"] , $_POST["weight"] , $_POST["carbo"] , $_POST["quantity"] , $_POST["catid"] , $_GET["id"]);
        $Query = "";
        
        if(count($Data) == 7)
        {
            $Query = "UPDATE food SET img = ? , name = ? , weight = ?, carbo = ? , quantity = ? , catid = ? WHERE id = ?";
        }
        else $Query = "UPDATE food SET name = ? , weight = ?, carbo = ? , quantity = ? , catid = ? WHERE id = ?";
        $Stmt = $Conn->prepare($Query);
        $Stmt->execute($Data);
    }

    header("Location:food.php");
}

?>

<?php
require_once("layout/Footer.php")
?>