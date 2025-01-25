document.getElementById('search-bar').addEventListener('input', function(){
    var searchValue = this.value.toLowerCase();
    var notifications = document.querySelectorAll('.notification');

    notifications.forEach(function(notification) {
        var title = notification.querySelector('h3').textContent.toLocaleLowerCase();
        var shortText = notification.querySelector('p').textContent.toLocaleLowerCase();

        if (title.includes(searchValue) || shortText.includes(searchValue)) {
            notification.style.display = '';
        } else {
            notification.style.display = 'none';
        }

    });
});


document.addEventListener("DOMContentLoaded", function () {
    const notifications = document.querySelectorAll(".notification");

    notifications.forEach(function(notification) {
        const fullText = notification.querySelector("p.full-text");

        if (fullText) {
            fullText.style.display = "none";

            notification.addEventListener("click", function () {
                if (fullText.style.display === "none") {
                    fullText.style.display = "block";
                } else {
                    fullText.style.display = "none";
                }
            });
        }
    });
});