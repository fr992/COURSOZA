document.querySelectorAll('.subject').forEach(function(subject){
    subject.addEventListener('click',function(){
        alert('Ju klikuat ne:'+ subject.textContent)
    });
});