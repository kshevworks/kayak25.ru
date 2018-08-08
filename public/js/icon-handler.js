$(document).ready(function () {
    if (document.getElementById("iconSelector")) {
        var icon = document.getElementById('icon');
        var button = document.getElementById('toogler');
        if (icon.value != "None") {
            button.innerHTML = '<i class="fa ' + icon.value + '"> ' + icon.value + '</i>';
        }
    }
});

$('#iconSelector li a').click(function (e) {
    var selected_icon = $(this).text();
    var icon = document.getElementById('icon');
    var button = document.getElementById('toogler');
    if (icon.value != "None") {
        button.innerHTML = '<i class="fa ' + icon.value + '"> ' + icon.value + '</i>';
    }
    else {
        button.innerHTML = icon.value;
    }
    icon.value = selected_icon;
    if (icon.value != "None") {
        button.innerHTML = '<i class="fa ' + icon.value + '"> ' + icon.value + '</i>';
    }
    else {
        button.innerHTML = icon.value;
    }


});