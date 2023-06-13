let Categorie = document.querySelector(".Categorie");
let MyForm = document.querySelector(".MyForm");
let HtmlSelectedItems = document.querySelector(".SelectedItems");
let SelectedItems = {};

function Calc(Obj) {
  let MinTotal = Obj.type
    ? Obj.carbo * Obj.InputValue
    : (Obj.InputValue / Obj.weight) * Obj.carbo;
  return MinTotal;
}
function AddItemToSelectedList(Obj) {
  let Div = document.createElement("div");
  Div.setAttribute("class", "Item");
  Div.setAttribute("id", "Item-" + Obj.id);

  let H51 = document.createElement("h5");
  H51.appendChild(document.createTextNode(Obj.name));

  let H52 = document.createElement("h5");
  H52.setAttribute("class", "RealValue");
  H52.appendChild(
    document.createTextNode("اجمالي الكربوهيدرات : " + Calc(Obj))
  );

  Div.appendChild(H51);
  Div.appendChild(H52);

  HtmlSelectedItems.appendChild(Div);

  // <div class="Item">
  //     <h4>عوامة</h4>
  //     <h5>1/2كوب</h5>
  //     <h5>الكمية : 50</h5>
  //     <button>حذف</button>
  // </div>
}

function AddItem(Item, X, Input) {
  SelectedItems[X[0].toString()] = {
    id: X[0],
    name: X[1],
    carbo: X[3],
    weight: X[2],
    InputValue: Input.value ? Input.value : 0,
    type: X[4].length > 0,
  };
  Item.classList.toggle("selected");
  if (!Item.classList.contains("selected")) Item.classList.add("selected");
  if (!document.getElementById(X[0]))
    AddItemToSelectedList(SelectedItems[X[0].toString()]);
}
MyForm.onsubmit = function (E) {
  let CurrDate = new Date();
  let FullDate =
    CurrDate.getFullYear() +
    "-" +
    (parseInt(CurrDate.getMonth()) + 1) +
    "-" +
    CurrDate.getDate() +
    " ";
  let Hours = CurrDate.getHours();
  let Minutes = CurrDate.getMinutes();
  Hours %= 12;
  Hours = Hours ? Hours : 12;
  Hours = Hours < 10 ? "0" + Hours : Hours;
  Minutes = Minutes < 10 ? "0" + Minutes : Minutes;
  FullDate += Hours + ":" + Minutes + ":00";

  E.preventDefault();
  let DontCalc = !(document.getElementsByName("howtocalc")[0] ? 1 : 0);
  let UserId = document.getElementsByName("userid")[0].value;
  let HowToCalc = DontCalc || document.getElementsByName("howtocalc")[0].value;
  let SugarInBlood =
    DontCalc || document.getElementsByName("SugarInBlood")[0].value;
  let Correction =
    DontCalc || document.getElementsByName("Correction")[0].value;
  let AdditionalCarbo = document.getElementsByName("AdditionalCarbo")[0].value;
  let TotalCarbo = 0;

  let Foods = "";
  console.log(UserId + " " + HowToCalc + " " + SugarInBlood + " " + Correction);
  let Keys = Object.keys(SelectedItems);
  for (let Key of Keys) {
    let CurrObj = SelectedItems[Key];
    Foods += CurrObj.name + ",";
    if (CurrObj.type) {
      TotalCarbo += CurrObj.carbo * CurrObj.InputValue;
    } else {
      TotalCarbo +=
        (Math.round((CurrObj.InputValue / CurrObj.weight) * 100) / 100) *
        CurrObj.carbo;
    }
  }
  TotalCarbo += parseInt(AdditionalCarbo);
  let NeedInsulin = DontCalc
    ? 0
    : Math.round((TotalCarbo / HowToCalc) * 100) / 100;

  if (!DontCalc) {
    if (Correction == 100 && SugarInBlood > 100)
      NeedInsulin += Math.round(((SugarInBlood - 100) / 100) * 100) / 100;
    else if (Correction == 50 && SugarInBlood > 50)
      NeedInsulin += Math.round(((SugarInBlood - 50) / 50) * 100) / 100;
    else if (Correction == 25 && SugarInBlood > 25)
      NeedInsulin += Math.round(((SugarInBlood - 25) / 25) * 100) / 100;
  }

  // console.log("#########\n");

  // SelectedInPhp.value = JSON.stringify(SelectedItems);

  let Xml = new XMLHttpRequest();
  Xml.onreadystatechange = function () {
    if (Xml.readyState == 4 && Xml.status == 200) {
      console.log(Xml.responseText);
    }
  };

  Xml.open("POST", "api.php?do=SendExp", false);
  Xml.setRequestHeader(
    "Content-Type",
    "application/x-www-form-urlencoded;charset=UTF-8"
  );
  Xml.send(
    `userid=${UserId}&totalcarbo=${TotalCarbo}&needinsulin=${
      DontCalc ? 0 : NeedInsulin
    }&foods=${Foods}&sugarinblood=${
      DontCalc ? 0 : SugarInBlood
    }&created_at=${FullDate}&type=${!DontCalc}`
  );
  window.location.href = `exp.php?do=showExp&userid=${UserId}${
    DontCalc ? "&type=0" : ""
  }`;
};
Categorie.onchange = function () {
  let Value = Categorie.value;
  let Xml = new XMLHttpRequest();
  Xml.onreadystatechange = function () {
    if (Xml.readyState == 4 && Xml.status == 200) {
      let Data = JSON.parse(Xml.responseText);
      let Food = document.querySelector(".Foods");
      Food.innerHTML = "";
      for (let X of Data) {
        let Can = 1;
        let Item = document.createElement("div");
        Item.setAttribute("class", "Item");

        let Title = document.createElement("h4");
        Title.appendChild(document.createTextNode(X[1]));

        let Desc = document.createElement("h5");
        let TxtDesc = "";
        if (X[4].length > 0) TxtDesc = "الكمية : " + X[4];
        else TxtDesc = "الوزن : " + X[2];
        Desc.appendChild(document.createTextNode(TxtDesc));

        let Input = document.createElement("Input");

        Input.setAttribute(
          "placeholder",
          X[4].length > 0 ? "ادخل الكمية" : "ادخل الوزن"
        );

        Input.oninput = function () {
          if (SelectedItems.hasOwnProperty(X[0].toString())) {
            SelectedItems[X[0].toString()].InputValue = Input.value;
            document.querySelector(`#Item-${X[0]} > .RealValue`).innerHTML =
              "اجمالي الكربوهيدرات : " + Calc(SelectedItems[X[0].toString()]);
          } else {
            AddItem(Item, X, Input);
          }
        };
        Input.onfocus = () => {
          Can = 0;
        };

        let Img = document.createElement("img");
        Img.setAttribute("src", "uploads/" + X[5]);
        Img.setAttribute("class", "ImgItem");

        Item.appendChild(Title);
        Item.appendChild(Desc);
        Item.appendChild(Input);
        Item.appendChild(Img);

        Item.addEventListener("click", function () {
          if (Can) {
            if (SelectedItems.hasOwnProperty(X[0].toString())) {
              delete SelectedItems[X[0].toString()];
              Item.classList.toggle("selected");
              document.getElementById("Item-" + X[0]).remove();
            } else {
              AddItem(Item, X, Input);
            }
          } else Can = 1;
        });

        if (SelectedItems.hasOwnProperty(X[0].toString())) {
          Item.classList.toggle("selected");
        }

        Food.appendChild(Item);
      }
    }
  };

  Xml.open("GET", "api.php?do=getAll&catid=" + Value, false);
  Xml.send();
};

let HelpBtn = document.querySelector(".Help");
let CloseHelpBtn = document.querySelector(".CloseHelp");
let ShowHelp = document.querySelector(".ShowHelp");
HelpBtn.onclick = function () {
  ShowHelp.classList.toggle("Show");
};

CloseHelpBtn.onclick = function () {
  ShowHelp.classList.toggle("Show");
};
let RemoveActiveFromAll = (Items) => {
  Items.forEach((X) => {
    if (X.lastElementChild.classList.contains("ActiveForHelpItem"))
      X.lastElementChild.classList.remove("ActiveForHelpItem");
  });
};
let ItemsForHelp = Array.from(document.querySelectorAll(".ForHelp > .Item"));
let Overlay = document.querySelector(".Overlay");
Overlay.onclick = function () {
  Overlay.classList.toggle("Hide");
  RemoveActiveFromAll(ItemsForHelp);
};
ItemsForHelp.forEach((Item) => {
  Item.addEventListener("click", () => {
    RemoveActiveFromAll(ItemsForHelp);
    Item.lastElementChild.classList.add("ActiveForHelpItem");
    Overlay.classList.toggle("Hide");
  });
});
