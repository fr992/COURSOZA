document.addEventListener("DOMContentLoaded",function(){
    console.log("JavaScript loaded");

    
    document.querySelectorAll('.notification').forEach(notification =>{
        notification.addEventListener('click',function() {
            const fullText = this.querySelector('.full-text');


            if (fullText.style.display ==="none"  || fullText.style.display ===""){
                fullText.style.display = "block";
            }else{
                fullText.style.display="none";
            }
        });
    });
});