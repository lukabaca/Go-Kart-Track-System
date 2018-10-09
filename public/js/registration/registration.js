$( document ).ready(function() {
    $('.arrowBack').on('click', function (e) {
       e.preventDefault();
       window.location.href = '/login';
    });

    let actualYear = new Date();
    for(let i = 1898; i <= actualYear.getFullYear(); i++) {
        let optionYear = '<option value="'+i+'">'+i+'</option>';
        $('#user_birthDate_year').append(optionYear);
    }
    for(i = 1; i <= 12; i++) {
        let monthNumber;
        if(i < 10) {
            monthNumber = '0' + i;
        } else {
            monthNumber = i;
        }

        let optionMonth = '<option value="'+i+'">'+monthNumber+'</option>';
        $('#user_birthDate_month').append(optionMonth);
    }
    for(i = 1; i <= 31; i++) {
        let dayNumber;
        if(i < 10) {
            dayNumber = '0' + i;
        } else {
            dayNumber = i;
        }
        let optionDay = '<option value="'+i+'">'+dayNumber+'</option>';
        $('#user_birthDate_day').append(optionDay);
    }

    $('#user_birthDate_month').on('change', function (e) {
       e.preventDefault();
        let selectedMonth = $(this).find(":selected").val();
        let selectedYear = $('#user_birthDate_year').find(':selected').val();
        console.log(selectedMonth);
        let dayCount = calculateDays(selectedMonth, selectedYear);
        console.log(dayCount);
    });
});

function calculateDays(monthNumber, yearNumber) {
    let dayNumber;
    if(monthNumber == 1 || monthNumber == 3 || monthNumber == 5 || monthNumber == 7 || monthNumber == 8
    || monthNumber == 10 || monthNumber == 12) {
        dayNumber = 31;
    } else if (monthNumber == 4 || monthNumber == 6 || monthNumber == 9 || monthNumber == 11) {
        dayNumber = 30;
    } else {
        let leap = (yearNumber % 4 == 0 && yearNumber % 100 != 0) || yearNumber % 400 == 0;
        dayNumber = leap ? 29 : 28;
    }
    return dayNumber;
}