var timeLeft = 10;
var updateTimeLeft = setInterval(function() {
    if (timeLeft < 0) {
        clearInterval(updateTimeLeft);
        window.location.href = "index.php";
    } else {
        document.getElementById("countdown").innerHTML = timeLeft;
        document.getElementById("progressBar").value = 10 - timeLeft;
    } 
    timeLeft -= 1;
}, 1000);