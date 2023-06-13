let Max = 0;
let OffSet = 0;
let BodyPosts = document.querySelector(".PostsAndTips .Body");

window.onscroll = function () {
  if (!BodyPosts) return;

  let Top = document.documentElement.scrollTop;

  let ClientHeight = document.documentElement.clientHeight;

  let AllHeight = document.documentElement.offsetHeight;

  if (Top + ClientHeight >= AllHeight - 300) {
    var Xml = new XMLHttpRequest();

    Xml.onreadystatechange = function () {
      if (Xml.readyState == 4 && Xml.status == 200) {
        let JsObj = JSON.parse(this.responseText);

        Offset += JsObj.length;

        for (let Post of JsObj) {
          BodyPosts.innerHTML += `

                    <div class="Post">

                        <div class="Header">

                            <h3>${Post[1]}</h3>

                            ${
                              IsAdmin <= 2
                                ? `<div class="Operations">

                            <a href="?do=Update&id=${Post[0]}">تعديل</a>

                            <a class='ConfirmBtns' href="?do=ConfirmDelete&id=${Post[0]}>&img=${Post[3]}">حذف</a>                            

                        </div>`
                                : ``
                            }

                        </div>

                        <p>${Post[2]}</p>

                        <img src="uploads/posts/${
                          Post[3]
                        }" class="ImagePost" alt="">

                    </div>

                `;
        }
      }
    };

    let ConfirmBtns = Array.from(document.querySelectorAll(".ConfirmBtns"));

    ConfirmBtns.forEach((X) => {
      X.onclick = function (E) {
        if (!confirm("هل انت متأكد من هذا الاجراء")) E.preventDefault();
      };
    });

    Xml.open(
      "GET",
      "api.php?do=getPosts&Offset=" + Offset + "&Type=" + type,
      false
    );

    Xml.send();
  }
};
