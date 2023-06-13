let ConfirmBtns = Array.from(document.querySelectorAll(".ConfirmBtns"));
ConfirmBtns.forEach(X => {
    X.onclick = function(E)
    {
        if(!confirm("هل انت متأكد من هذا الاجراء"))E.preventDefault();
    }
});
