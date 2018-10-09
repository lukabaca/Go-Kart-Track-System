$( document ).ready(function() {
    $('.arrowBack').on('click', function (e) {
       e.preventDefault();
       window.location.href = '/login';
    });

    let actualYear = new Date();
    let daysSelect = $('#user_birthDate_day');
    let monthSelect = $('#user_birthDate_month');
    let yearSelect = $('#user_birthDate_year');

    daysSelect.empty();
    monthSelect.empty();
    yearSelect.empty();

    initSelects(actualYear, yearSelect, monthSelect, daysSelect);

    yearSelect.on('change', function (e) {
        e.preventDefault();
        let selectedMonth = monthSelect.find(":selected").val();
        let selectedYear = $(this).find(':selected').val();

        if(selectedMonth == 2) {
            let dayCount = calculateDays(selectedMonth, selectedYear);
            daysSelect.empty();
            populateDays(dayCount, daysSelect);
        }
    });

    monthSelect.on('change', function (e) {
       e.preventDefault();
        let selectedMonth = $(this).find(":selected").val();
        let selectedYear = yearSelect.find(':selected').val();

        let dayCount = calculateDays(selectedMonth, selectedYear);
        daysSelect.empty();
        populateDays(dayCount, daysSelect);

    });
});
function initSelects(actualYear, yearSelect, monthSelect, daysSelect) {
    for(let i = 1898; i <= actualYear.getFullYear() - 1; i++) {
        let optionYear = '<option value="'+i+'">'+i+'</option>';
        yearSelect.append(optionYear);
    }
    for(i = 1; i <= 12; i++) {
        let monthNumber;
        if(i < 10) {
            monthNumber = '0' + i;
        } else {
            monthNumber = i;
        }

        let optionMonth = '<option value="'+i+'">'+monthNumber+'</option>';
        monthSelect.append(optionMonth);
    }
    for(i = 1; i <= 31; i++) {
        let dayNumber;
        if(i < 10) {
            dayNumber = '0' + i;
        } else {
            dayNumber = i;
        }
        let optionDay = '<option value="'+i+'">'+dayNumber+'</option>';
        daysSelect.append(optionDay);
    }
}

function populateDays(dayCount, daysSelect) {
    for(let i = 1; i <= dayCount; i++ ) {
        let dayNumber;
        if(i < 10) {
            dayNumber = '0' + i;
        } else {
            dayNumber = i;
        }
        let optionDay = '<option value="'+i+'">'+dayNumber+'</option>';
        daysSelect.append(optionDay);
    }
}

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