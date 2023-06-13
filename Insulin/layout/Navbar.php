<div class="Navbar">
  <div class="container">
      <a href="index.php">
        <img src="images/Logo.PNG" class="Logo"/>
      </a>
      <ul class="Links">
      <li><a href="http://calculatecarbo.epizy.com/post.php?Who=23" style="color:red">دليل استخدام الموقع</a></li>
        <?php
          if(isset($_SESSION["isadmin"]) && $_SESSION["isadmin"]<=2)
          {
            ?>
              <li><a href="exp.php?type=WithInsulin">حساب كمية الانسولين</a></li>
              <li><a href="exp.php">حساب كمية الكربوهيدرات</a></li>
              <li><a href="exp.php?do=addexp2">منحنى سكر الدم</a></li>
            <?php
          }
          
        ?>
        <li><a href="post.php?Who=1">معلومات تثقيفية</a></li>
        <?php
          if(isset($_SESSION["username"]))
          {
            ?>
              <li class="DropDown">
                <a>اهلا <?php echo $_SESSION["username"]?></a>
                <ul class="">
                  <a href="users.php?do=update&id=<?php echo $_SESSION["id"] ?>">تعديل بياناتي</a>
                  <?php
                      if($_SESSION["isadmin"]<=2)
                      {
                        ?>
                        <a href="users.php">ادارة المستخدمين</a>
                          <a href="categories.php">ادارة الاصناف</a>
                          <a href="food.php">ادارة المأكولات</a>
                          <a href="categoriesposts.php">ادارة اصناف المنشورات</a>
                          <a href="quotes.php">ادارة الاقتباسات</a>
                          <?php
                      }
                      else 
                      {
                        ?>
                          <a href="exp.php?type=WithInsulin">حساب كمية الانسولين</a>
                          <a href="exp.php">حساب كمية الكربوهيدرات</a>
                          <a href="exp.php?do=addexp2&userid=<?php echo $_SESSION["id"]?>">منحنى سكر الدم</a>
                          <a href="exp.php?do=showMeasu&userid=<?php echo $_SESSION["id"]?>">قياساتي</a>
                          <a href="exp.php?do=showExp&userid=<?php echo $_SESSION["id"] ?>">تجارب الانسولين</a>
                          <a href="exp.php?do=showExp&userid=<?php echo $_SESSION["id"] ?>&type=0">تجارب الكربوهيدرات</a>
                          <?php
                      }
                      ?>
                          
                          <a href="Logout.php">تسجيل الخروج</a>
                      <?php
                  ?>
                </ul>
              </li>
            <?php
          }
          else
          {
            ?>
              <li><a href="SignUp.php">انشاء حساب</a></li>
              <li><a href="Login.php">تسجيل الدخول</a></li>
            <?php
          }
        ?>
      </ul>
  </div>
</div>