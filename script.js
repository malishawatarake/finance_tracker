$(document).ready(function() {
    // Add Form Validation
    if ($('#addForm').length) {
        $('#addForm').on('submit', function(e) {
            let valid = true;
            let errorMsg = '';

            if (!$('#date').val() || !$('#category').val() || !$('#amount').val() || !$('input[name="type"]:checked').val() || !$('#description').val()) {
                valid = false;
                errorMsg += 'All fields are required. ';
            }

            let amount = $('#amount').val();
            if (amount && (!/^\d+(\.\d{1,2})?$/.test(amount) || parseFloat(amount) <= 0)) {
                valid = false;
                errorMsg += 'Amount must be a positive number with up to 2 decimals. ';
            }

            let date = $('#date').val();
            if (date && !/^\d{4}-\d{2}-\d{2}$/.test(date)) {
                valid = false;
                errorMsg += 'Invalid date format. ';
            }

            if (!valid) {
                e.preventDefault();
                $('.error').html(errorMsg).show();
                return false;
            }
        });

        $('#amount').on('input', function() {
            let val = $(this).val();
            if (val && (!/^\d+(\.\d{1,2})?$/.test(val) || parseFloat(val) <= 0)) {
                $(this).css('border-color', 'red');
            } else {
                $(this).css('border-color', '#ddd');
            }
        });
    }

    // Login Form Validation
    if ($('#loginForm').length) {
        $('#loginForm').on('submit', function(e) {
            let valid = true;
            let errorMsg = '';

            if (!$('#username').val().trim()) {
                valid = false;
                errorMsg += 'Username is required. ';
            }

            let pass = $('#password').val();
            if (!pass || pass.length < 8 || !/^[a-zA-Z0-9]+$/.test(pass)) {
                valid = false;
                errorMsg += 'Password must be at least 8 alphanumeric characters. ';
            }

            if (!valid) {
                e.preventDefault();
                $('.error').html(errorMsg).show();
                return false;
            }
        });

        $('#password').on('input', function() {
            let val = $(this).val();
            if (val.length < 8 || !/^[a-zA-Z0-9]+$/.test(val)) {
                $(this).css('border-color', 'red');
            } else {
                $(this).css('border-color', '#ddd');
            }
        });
    }

    // Table Hover (UX bonus)
    $('table tbody tr').hover(function() {
        $(this).css('background', '#f0f0f0');
    }, function() {
        $(this).css('background', 'none');
    });
});