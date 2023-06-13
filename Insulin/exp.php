<?php
require_once("./init.php");
require_once("./layout/Navbar.php");

if(!isset($_SESSION["username"]))header("Location:index.php");
if($_SERVER["REQUEST_METHOD"] == "GET")
{
    if(isset($_GET["do"]))
    {
        $Do = $_GET["do"];
        if($Do == "showExp")
        {
            $Userid = $_GET["userid"];
            
            $Stmt = $Conn->prepare("SELECT id , username from users WHERE id = ?");
            $Stmt->execute(array($Userid));
            $User = $Stmt->fetch();

            $Type = 1;
            if(isset($_GET["type"]))$Type = 0;
            
            $Stmt = $Conn->prepare("SELECT * FROM exp WHERE userid = ? AND sugarinblood " . ($Type?"!":"") . "= ? ORDER BY id DESC");
            $Stmt->execute(array($Userid , 0));
            $Data = $Stmt->fetchAll();
            ?>
            <style>
                table 
                {
                    border-collapse: collapse;
                    border-spacing: 0;
                    width: 100%;
                    border: 1px solid #ddd;
                }

                th, td 
                {
                    text-align: center;
                    padding: 8px;
                }

                    tr:nth-child(even){background-color: #f2f2f2}
            </style>            
                <div class="Login ShowExp">
                    <h3 class="MyTitle">
                        <?php 
                            echo "تجارب" . " " . ($Type?"الانسولين" : "الكربوهيدرات");
                        ?>
                    </h3>
                    <div class="container">
                    <h3>اسم المريض : <span style="color:red"><?php echo $User["username"] ?></span></h3>
                        <div style="overflow-x:auto;">
                            <table>
                                <tr>
                                <th>اجمالي الكربوهيدرات</th>
                                <?php
                                    if($Type)
                                    {
                                        ?>
                                            <th>كمية الانسولين</th>
                                            <th>قياس السكر في الدم</th>
                                        <?php
                                    }
                                ?>
                                <th>المأكولات</th>
                                <th>التاريخ</th>
                                <th>عمليات</th>
                                </tr>
                                <?php
                                    foreach($Data as $Value)
                                    {
                                        $Full = strtotime($Value["createdAt"]);
                                        $BeautifulDate = date("Y-m-d" , $Full);
                                        $BeautifulTime = date("H:i:s" , $Full);
                                        echo "<tr>";
                                        echo "<td>" . $Value["totalcarbo"] . "</td>";
                                        if($Type)
                                        {
                                            echo "<td>" . $Value["needinsulin"] . "</td>";
                                            echo "<td>" . $Value["sugarinblood"] . "</td>";
                                        }
                                        echo "<td>" . $Value["Foods"] . "</td>";
                                        echo "<td>" . $BeautifulDate . "<br />" . $BeautifulTime . "</td>";
                                        echo "<td>";
                                        ?>
                                            <a class="ConfirmBtns" href="?do=delete&id=<?php echo $Value["id"] ?>&userid=<?php echo $User["id"] . ($Type?"":"&type=0"); ?>" class="MyBtns">حذف</a>
                                        <?php
                                        echo "</td>";

                                        echo "</tr>";
                                    }
                                ?>
                            </table>
                        </div>
                    </div>
                </div>
            <?php
        }
        else if($Do == "addexp2")
        {
            ?>
                <div class="Login">
                    <div class="container">
                        <h3 class="MyTitle">اضافة قياس لفحص السكر</h3>
                        <form class="LoginSignUp" action="?do=ConfirmAddExp2" method="POST">
                            <?php
                                if($_SESSION["isadmin"]<=2)
                                {
                                    $Stmt = $Conn->prepare("SELECT * FROM users WHERE isadmin > 2");
                                    $Stmt->execute();
                                    $AllUsers = $Stmt->fetchAll();
                                    ?>
                                        <div>
                                            <label>اسم المستخدم</label>
                                            <select name="userid" id="" required class="MySelect">
                                                <option value="" selected="true"></option>
                                            <?php
                                                foreach($AllUsers as $Value)
                                                {
                                                    ?>
                                                        <option value="<?php echo $Value["id"] ?>"><?php echo $Value["username"] ?></option>
                                                    <?php
                                                }
                                            ?>
                                            </select>
                                        </div>  
                                    <?php
                                }
                                else
                                {
                                    ?>
                                        <input type="hidden" name="userid" value="<?php echo $_SESSION["id"]?>" />
                                    <?php
                                }
                            ?>
                            <div>
                                <label >القيمة</label>
                                <input type="number" name="value"  step="0.01" class="MyInput" requried />
                            </div>
                            <div>
                                <input type="submit" value="اضافة"  class="MySubmit"/>
                            </div>
                        </form>
                    </div>
                </div>
            <?php
        }
        else if($Do == "delete")
        {
            $Stmt = $Conn->prepare("DELETE FROM exp WHERE id = ?");
            $Stmt->execute(array($_GET["id"]));
            header("location:exp.php?do=showExp&userid=" . $_GET["userid"] . (isset($_GET["type"])?"&type=0":""));
        }
        else if($Do == "showMeasu")
        {
            $userid = $_GET["userid"];
            $AllData = array();
            $Offset = 0;
            while(1)
            {

                $Stmt = $Conn->prepare("SELECT * FROM exp2 WHERE userid = ? LIMIT 7 OFFSET $Offset");
                $Stmt->execute(array($userid));
                $Data = $Stmt->fetchAll();
                $Count = $Stmt->rowCount();
                if(!$Count)break;
                array_push($AllData , $Data);
                $Offset+=$Count;
            }
            ?>
                <div class="ShowExp2">
                    <div class="container">
                        <?php
                            if($Offset)
                            {
                                $Offset = 0;
                                foreach($AllData as $Value)
                                {
                                    $Limit = count($Value);
                                    
                                    echo "<div class='Item' data-offset='" . $Offset . "' data-limit='" . $Limit . "'>من " . $Value[0]["expdate"] . " الى " . $Value[count($Value) - 1]["expdate"] . "</div>";
                                    $Offset+=7;
                                }
                                echo '<div class="MyLoader HideIconAnimate"></div>';
                            }
                            else
                            {
                                echo "<h2>لم يتم عمل فحوصات حتى الان</h2>";
                            }
                        ?>
                        <canvas id="myChart" width="100vh" height="40vh" ></canvas>
                        <div class="AllUsers">
                        <div class="container">
                            <div style="overflow:auto">
                                <table class="MyTable">
                                    <thead>
                                        <th>رقم القياس</th>
                                        <th>القيمة</th>
                                        <th>التاريخ</th>
                                        <th>عمليات</th>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                                
                            </div>
                        </div>
                    </div>
                    </div>
                </div>
            <?php
            ?>  
                <script>
                    let Userid = <?php echo $userid;?>;
                </script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.1/chart.min.js"></script>
                <script src="./layout/scripts/showMeas.js"></script>
            <?php
        }
        else if($Do == "DeleteMeasu")
        {
            $Id = $_GET["id"];
            $Stmt = $Conn->prepare("DELETE FROM exp2 WHERE id = ?");
            $Stmt->execute(array($Id));
            MyRedirect("exp.php?do=showMeasu&userid=" . $_GET["userid"]);
        }
    }
    else
    {
        $Stmt = $Conn->prepare("SELECT * FROM users");
        $Stmt->execute();
        $Users = $Stmt->fetchAll();
        

        $Stmt = $Conn->prepare("SELECT * FROM categories");
        $Stmt->execute();
        $Categories = $Stmt->fetchAll();
        
        ?>
            <div class="Login StartExp">
                <div class="Overlay Hide"></div>
                <div class="container">
                    <h2 class="MyTitle">اضافة تجربة</h2>    
                    <form action="?do=ConfirmAddExp" class="MyForm LoginSignUp" method="POST">
                        <div class="Top">
                            <div class="Right">
                                <?php
                                    if($_SESSION["isadmin"] == 3)
                                    {
                                        ?>
                                            <input type="hidden" name="userid" value="<?php echo $_SESSION["id"] ?>">
                                        <?php
                                    }
                                    else
                                    {
                                        ?>
                                            <div>
                                                <label for="">المستخدم</label>
                                                <select name="userid" id="" required class="MySelect">
                                                    <option value=""></option>
                                                    <?php
                                                        foreach($Users as $User)
                                                        {
                                                            if($User["id"] == $_SESSION["id"] || $User["isadmin"]<=2)continue;
                                                            echo "<option value=" . $User["id"] . ">" . $User["username"] . "</option>";
                                                        }
                                                    ?>
                                                </select>
                                            </div>
                                        <?php
                                    }

                                ?>

                                <div>
                                    <label for="">الصنف</label>
                                    <select name="" id="" class="MySelect Categorie">
                                        <option value=""></option>
                                        <?php
                                            foreach($Categories as $Categorie)
                                            {
                                                echo "<option value=" . $Categorie["id"] . ">" . $Categorie["name"] . "</option>";
                                            }
                                        ?>
                                    </select>
                                </div>
                                    
                                <?php
                                    if(isset($_GET["type"]) && $_GET["type"] == "WithInsulin")
                                    {
                                        ?>
                                        <div>
                                            <label for="">الية الحساب</label>
                                            <select name="howtocalc" id="" class="MySelect" required>
                                                <option value=""></option>
                                                <option value="15">كل 15 غم كربوهيدرات ب1 انسولين</option>
                                                <option value="10">كل 10 غم كربوهيدرات ب1 انسولين</option>
                                                <option value="5">كل 5 غم كربوهيدرات ب1 انسولين</option>
                                            </select>
                                        </div>
                                        
                                        <div>
                                            <label>قياس السكر في الدم</label>
                                            <input type="number" step="0.1" name="SugarInBlood" class="MyInput" required />
                                        </div>

                                        <div>
                                            <label for="">جرعة التصحيح</label>
                                            <select name="Correction" id="" class="MySelect" required>
                                                <option value=""></option>
                                                <option value="100">كل ارتفاع  100 وحده عن 100   يحتاج 1 وحده انسولين </option>
                                                <option value="50">كل ارتفاع  50 وحده عن 100   يحتاج 1 وحده انسولين </option>
                                                <option value="25">كل ارتفاع  25 وحده عن 100   يحتاج 1 وحده انسولين </option>
                                            </select>
                                        </div>
                                    <?php
                                    }
                                ?>

                                <div>
                                    <label for="">كربوهيدرات اضافية</label>
                                    <input type="number" value="0" required step="0.01" name="AdditionalCarbo" class="MyInput" />
                                    <label for="" class="Help">تعليمات</label>
                                </div>
                                <div class="ForHelp">
                                <div class="Item">
                                    <h4>الكأس المستخدمة في اللبن والعصير</h4>
                                    <img class="" src="./images/JuiceAndMilk.jpg"/>
                                </div>
                                <div class="Item">
                                    <h4>صحن يعبر عن مقدار كوب خضروات</h4>
                                    <img src="./images/ValueCubeVeg.jpg"/>
                                </div>
                                <div class="Item">
                                    <h4>الكوب المعياري المستخدم في تقدير الخضورات</h4>
                                    <img src="./images/Veg.jpg"/>
                                </div>
                            </div>
                            </div>
                            
                            <div class="Left">
                                <div class="MyTitle">العناصر المختارة</div>
                                <div class="SelectedItems">
                                    <!-- <div class="Item">
                                        <h4>عوامة</h4>
                                        <h5>1/2كوب</h5>
                                        <h5>الكمية : 50</h5>
                                        <button>حذف</button>
                                    </div> -->
                                </div>
                            </div>
                        </div>
                        <div class="Foods">
                            <!-- <div class="Item">
                                <h4>عوامة</h4>
                                <h5>1/2كوب</h5>
                                <input type="number">
                                <img src="uploads/default.jpg" class="ImgItem">
                            </div> -->
                        </div>

                        <div>
                            <input type="submit" value="اضافة" class="MySubmit">
                        </div>
                        <div class="ShowHelp">
                            <i class="fa-solid fa-xmark fa-3x CloseHelp" style="margin-right:20px;color:red;cursor:pointer;"></i>
                            <div class="Header">
                                <h3>تعريفات الملصق الغذائي</h3>
                                <p>تتواجد الملصقات الغذائية على الأطعمة المعبئة والمغلفة على ظهر أو جانب العبوة الغذائية مثل (أكياس الشيبس، الشوكولاتة، الحليب المنكه، بسكويت...إلخ) كدليل عن الحقائق التغذوية للمنتج تتضمن تقديم معلومات عن كمية الكربوهيدرات الموجودة في هذا النوع من الطعام. </p>
                            </div>
                            <div class="Content">
                                <h4 style="text-decoration: underline;">طريقة الحساب</h4>
                                <ul>
                                    <li>انظر دائماً الى اجمالي الكربوهيدرات وليس الى محتوى السكر عند حساب الكربوهيدرات. </li>
                                    <li>أدخل كمية الكربوهيدرات الموجودة بالمنتج في المكان المخصص <span style="color:red">"كربوهيدرات إضافية" </span></li>
                                    <li>ازا تناولت الكمية كاملة من المنتج ادخل نفس كمية الكربوهيدرات الموضحة على الملصق لكن بحال تناولت نصف الكمية قم بإدخال نصف عدد الكربوهيدرات الموجود</li>
                                    <li>
                                        <h4 style="color:red">ملاحظة</h4>
                                        عند تناول أي نوع من الشكولاتة أو الكاندي اوالملبس التي لا تحتوي على ملصق غذائي، يتم حساب كمية الكربوهيدرات لها عن طريق اعتبار                                       ان نصف وزنها يمثل كمية الكربوهيدرات الموجودة فيها .
                                    </li>

                                    <li>
                                        <img src="images/Help.png"/>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <script src="layout/scripts/Exp.js?v=1.3"></script>
        <?php
    } 
}
else
{
    $Do = $_GET["do"];
    if($Do == "ConfirmAddExp")
    {
        $Data = $_POST["Items"];
        $Data = json_decode($Data , JSON_UNESCAPED_UNICODE);
        $AllFoods = "";
        $totalcarbo = 0;
        foreach($Data as $Value)
        {
            $AllFoods.=$Value["name"] . " , ";
            $totalcarbo+=floatval($Value["carbo"])*floatval($Value["InputValue"]);
        }
        $userid = $_POST["userid"];
        $HowToCalc = $_POST["howtocalc"];
        // echo $totalcarbo . "<br />";
        $NeedInsulin = round($totalcarbo / $HowToCalc , 2);
        // echo $NeedInsulin . "<br />";
        $SugarInBlood = $_POST["SugarInBlood"];
        $Correction = $_POST["Correction"];
        if($Correction == 100 && $SugarInBlood>100)$NeedInsulin+=round(($SugarInBlood - 100) / 100 , 2);
        else if($Correction == 50 && $SugarInBlood>50)$NeedInsulin+=round(($SugarInBlood - 50)/ 50 , 2);
        else if($SugarInBlood > 25)$NeedInsulin+=round(($SugarInBlood - 25)/ 25 , 2);

        echo $NeedInsulin . "<br />";
        // echo $userid . "<br />" . $totalcarbo . "<br />" . $NeedInsulin . "<br />" . date("Y-m-d h:i");
        $Stmt = $Conn->prepare("INSERT INTO exp(userid , totalcarbo , needinsulin , Foods , sugarinblood , createdAt) VALUES (? , ? , ? , ? , ? , ?)");
        $Stmt->execute(array($userid , $totalcarbo , $NeedInsulin , $AllFoods , $SugarInBlood , date("Y-m-d h:i")));

        MyRedirect("exp.php?do=showExp&userid=" . $userid);
    }
    else if($Do == "ConfirmAddExp2")
    {
        $Stmt = $Conn->prepare("INSERT INTO exp2(value , userid , expdate) VALUES (? , ? , ?)");
        $Stmt->execute(array($_POST["value"] , $_POST["userid"] , date("Y-m-d h:i")));
        MyRedirect("exp.php?do=showMeasu&userid=" . $_POST["userid"]);
    }
}
?>



<?php
require_once("./layout/Footer.php");
?>