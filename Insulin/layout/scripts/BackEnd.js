// let AllPosts = Array.from(document.querySelectorAll(".Posts .Item"));
// let RightControl = document.querySelector(".Posts .Right")
// let LeftControl = document.querySelector(".Posts .Left")
// let Cnt = 0;
// let LeftSlide = function()
// {
//     AllPosts[Cnt--].classList.remove("Active");
//     if(Cnt == -1)Cnt = AllPosts.length - 1;
//     AllPosts[Cnt].classList.add("Active");
// }

// let RightSlide = function()
// {
//     AllPosts[Cnt++].classList.remove("Active");
//     if(Cnt == AllPosts.length)Cnt = 0;
//     AllPosts[Cnt].classList.add("Active");
// }

// LeftControl.onclick = function()
// {
//     LeftSlide();
// }

// RightControl.onclick = function()
// {
//     RightSlide();
// }
let Quote = document.querySelector(".Quote");
let CloseQuote = document.querySelector(".CloseQuote");
let Progress = document.querySelector(".Progress");

if(Quote)
{
    let Width = Progress.offsetWidth;
    let CurrInt;
    CloseQuote.onclick = function()
    {
        Quote.classList.toggle("Show");
        clearInterval(CurrInt);
    }
    window.addEventListener("load" , ()=>{
        Quote.classList.toggle("Show");
        setTimeout(() => {
            CurrInt = setInterval(() => {
                Progress.style.width = Width + "px";
                if(Width < 0)clearInterval(CurrInt);
                Width-=0.375;
                
            }, 10);
            
        }, 2000);
        setTimeout(()=>{
            if(Quote.classList.contains("Show"))
            {
                Quote.classList.toggle("Show");
                clearInterval(CurrInt);
            }
        } , 10000)
        
    });
}
