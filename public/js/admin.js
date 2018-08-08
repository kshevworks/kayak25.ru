$("#mt-flag").change(function () {
    var value = $(this).val();
    $.ajax({
        url: "admin/switch-flag",
        type: "POST",
        data: {
            'value': value,
        },
        cache: false,
        success: function (data) {
            console.log(data);
            if (data["result"] == 1) {
                // Success message
                $('#mt-flag').checked = true;
            }
            else {
                $('#mt-flag').checked = false;
                //clear all fields
            }

        }
    });
});