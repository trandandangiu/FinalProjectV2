$(document).ready(function () {
    // Management Users
    $(document).on('click', '.upgradeStaff', function (e) {
        e.preventDefault();
        
        let button = $(this);
        let userId = button.data('user-id');
    

        $.ajax({
            url: "user/upgrade",
            method: 'POST',
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
            },
            data: {
                user_id: userId,
            },
            success: function (response) {
                
                if(response.status) {

                    alert('Thành công: ' + response.message); 
                    
                    button.closest('.profile_view').find('.brief i').text('STAFF');
                    button.hide();
                } else {
                    alert('Lỗi: ' + response.message);
                }
            },
        });
    });
});