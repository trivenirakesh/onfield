let update_url = $("#module_update_url").val();


$("#updateScheduleTimingFrm").submit(function(e){
    e.preventDefault();
    let requestArr  = [];
    $(".dayWiseRow").each(function(key,val){
        let fieldArr = new Object();
        let startTime = $(this).find('.start_time').val();
        let endTime = $(this).find('.end_time').val();
        let dayStatus = $(this).find('.day_status');
        dayStatus = $(dayStatus).prop('checked') ? 1 : 0;
        let dayName = $(this).find('.day_name').val();
        let scheduleId = $(this).find('.schedule_id').val();
        fieldArr.id =  scheduleId;
        fieldArr.work_day =  dayName;
        fieldArr.start_time =  startTime;
        fieldArr.end_time =  endTime;
        fieldArr.status =  dayStatus;
        requestArr.push(fieldArr);
    })
    $.ajax({
        url : update_url,
        dataType: "json",
        type : 'POST',
        data : {schedule : requestArr},
        headers: {
            "X-CSRF-TOKEN": csrf_token,
        },
        success: function (response) {
            console.log(response);
        },
        error: function () {
            toastr.error("Please Reload Page.");
        },
    })
})