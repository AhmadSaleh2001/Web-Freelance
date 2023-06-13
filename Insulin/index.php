<?php
require_once("./init.php");
require_once("layout/Navbar.php");
// $Stmt = $Conn->prepare("SELECT * FROM posts ORDER BY id DESC LIMIT 5");
// $Stmt->execute();
// $Posts = $Stmt->fetchAll();

$Stmt = $Conn->prepare("SELECT * FROM quotes ORDER BY RAND() LIMIT 1");
$Stmt->execute();
$Quote = $Stmt->fetch();
?>
<?php
    if($Quote)
    {
        ?>
            <div class='Quote'>
                <?php echo $Quote["data"]; ?>
                <i class="fa-solid fa-xmark fa-2x CloseQuote"></i>
                <i class="Progress"></i>
            </div>
        <?php
    }
?>
<div class="Landing">
    <div class="Overlay"></div>
    <img src="images/Landing.jpg" class="ImgLanding" alt="">
    <div class="container">
        <div>
            <h2>PAL.CARBS DM1</h2>
        </div>
    </div>
</div>


<div class="Features">
    <h2 class="MyTitle">المميزات</h2>
    <h3>اصبح حساب الانسولين</h3>
    <div class="container">
        <div class="Item">
            <i class="fa-solid fa-heart-pulse fa-2x"></i>
            <h3>اسهل</h3>
        </div>

        <div class="Item">
            <i class="fa-solid fa-briefcase-medical fa-2x"></i>
            <h3>ادق</h3>
        </div>


        <div class="Item">
            <i class="fa-solid fa-clock fa-2x"></i>
            <h3>وقت اقل</h3>
        </div>

        <div class="Item">
            <i class="fa-solid fa-face-smile fa-2x"></i>
            <h3>اكثر متعة</h3>
        </div>
    </div>
</div>

<!-- 
    <div class="Posts">
    <h2 class="MyTitle">اخر المنشورات</h2>
    <div class="container">
    <?php
            $SetFirst = 0;
            foreach($Posts as $Post)
            {
                ?>
                    <span>
                        <div class="Item <?php if(!$SetFirst)echo 'Active'; ?>">
                            <div>
                                <h3><?php echo $Post["title"] ?></h3>
                                <p><?php echo $Post["body"] ?></p>
                                <img src="uploads/Posts/<?php echo $Post["img"] ?>" style="max-width:200px" alt="">
                            </div>
                        </div>
                    </span>
                <?php
                $SetFirst = 1;
            }
        ?>
        
        <i class="fa-solid fa-arrow-right fa-2x Right"></i>
        <i class="fa-solid fa-arrow-left fa-2x Left"></i>
    </div>
</div>

    -->

<script src="<?php echo $js ?>/BackEnd.js"></script>
<?php
require_once("./layout/Footer.php")
?>