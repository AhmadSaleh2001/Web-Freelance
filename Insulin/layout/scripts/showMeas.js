let ctx = document.getElementById("myChart").getContext('2d');
let myChart;
let MyTable = document.querySelector(".MyTable tbody");
let Loader = document.querySelector(".MyLoader");
function getDayName(Value)
{
    if(Value == 0)return "الاحد";
    else if(Value == 1)return "الاثنين";
    else if(Value == 2)return "الثلاثاء";
    else if(Value == 3)return "الاربعاء";
    else if(Value == 4)return "الخميس";
    else if(Value == 5)return "الجمعة";
    else if(Value == 6)return "السبت";
}
let AllItems = document.querySelectorAll(".ShowExp2 .Item");
AllItems.forEach(Item =>{
    
    Item.addEventListener("click" , ()=>{
        Loader.classList.toggle("HideIconAnimate");
        Item.classList.toggle("Active");
        let Xml = new XMLHttpRequest();
        let Days = [];
        let MeasData = [];
        let JsObj = [];
        Xml.onreadystatechange = function()
        {
            if(Xml.readyState == 4 && Xml.status == 200)
            {
                
                JsObj = JSON.parse(this.responseText);
                
                for(let X of JsObj)
                {
                    let CurrDate = new Date(X[3]);
                    Days.push(getDayName(CurrDate.getDay()));
                    MeasData.push(X[1]);
                }
                Loader.classList.toggle("HideIconAnimate");
            }
        }
        
        
        Xml.open("GET" , "api.php?do=getExp2&Userid=" + Userid + "&Offset=" + Item.getAttribute("data-offset") + "&Limit=" + Item.getAttribute("data-limit") , false);
        Xml.send();
        if(myChart instanceof Chart)myChart.destroy();
        myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels : Days,
                datasets: [{
                    label: 'فحص السكر على الصيام',
                    borderWidth: 3,
                    data : MeasData,
                    borderColor : 'red',
                    tension: 0.1
                }]
            },
        });
        MyTable.innerHTML = "";
        let Cnt = 1;
        for(let Item of JsObj)
        {
            MyTable.innerHTML += 
            `
                <tr>
                    <td>${Cnt++}</td>
                    <td>${Item[1]}</td>
                    <td>${Item[3]}</td>
                    <td>
                        <a class='ConfirmBtns' href='exp.php?do=DeleteMeasu&id=${Item[0]}&userid=${Item[2]}'>حذف</a>
                    </td>
                </tr>
            `;
        }
        let ConfirmBtns = Array.from(document.querySelectorAll(".ConfirmBtns"));
        ConfirmBtns.forEach(X => {
            X.onclick = function(E)
            {
                if(!confirm("هل انت متأكد من هذا الاجراء"))E.preventDefault();
            }
        });

    });
})

