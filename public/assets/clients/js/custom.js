$(document).ready(function () {
    // page login, register

    //validate form register
    $("#register-form").submit(function (e) {
        let name = $('input[name="name"]').val();
        let email = $('input[name="email"]').val();
        let password = $('input[name="password"]').val();
        let confirmPassword = $('input[name="confirmPassword"]').val();
        let checkbox1 = $('input[name="checkbox1"]').is(':checked');
        let checkbox2 = $('input[name="checkbox2"]').is(':checked');

        let erroMessage = "";

        if (name.length < 3) {
            erroMessage += "Họ và tên phải có ít nhất 3 ký tự.<br/>";
        }

        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            erroMessage += "Email không hợp lệ.<br/>";
        }

        if (password.length < 6) {
            erroMessage += "Mật khẩu phải có ít nhất 6 ký tự.<br/>";
        }

        if (password !== confirmPassword) {
            erroMessage += "Mật khẩu xác nhận không khớp.<br/>";
        }

        if (!checkbox1 || !checkbox2) {
            erroMessage +=
                "Bạn phải đồng ý với các điều khoản.<br/>";
        }

        if (erroMessage !== "") {
            toastr.error(erroMessage, "Lỗi đăng ký");
            e.preventDefault();
        }

    });

    //validate form login
    $("#login-form").submit(function (e) {
        toastr.clear();
        let email = $('input[name="email"]').val();
        let password = $('input[name="password"]').val();

        let erroMessage = "";


        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            erroMessage += "Email không hợp lệ.<br/>";
        }

        if (password.length < 6) {
            erroMessage += "Mật khẩu phải có ít nhất 6 ký tự.<br/>";
        }
        if (password !== confirmPassword) {
            erroMessage += "Mật khẩu xác nhận không khớp.<br/>";
        }
        if (erroMessage !== "") {
            toastr.error(erroMessage, "Lỗi");
            e.preventDefault();
        }

    });

    //validate reset-password-form
    $("#reset-password-form").submit(function (e) {
        toastr.clear();
        let email = $('input[name="email"]').val();
        let password = $('input[name="password"]').val();
        let confirmPassword = $('input[name="password_confirmation"]').val();

        let erroMessage = "";


        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            erroMessage += "Email không hợp lệ.<br/>";
        }

        if (password.length < 6) {
            erroMessage += "Mật khẩu phải có ít nhất 6 ký tự.<br/>";
        }
        if (password !== confirmPassword) {
            erroMessage += "Mật khẩu xác nhận không khớp.<br/>";
        }
        if (erroMessage !== "") {
            toastr.error(erroMessage, "Lỗi");
            e.preventDefault();
        }

    });

    // page account

    //where clicking on the image => open input file
    $(".profile-pic").click(function () {
        $("#avatar").click();
    });

    // khi chon 1 tam hinh => no hien len hinh anh 
    $("#avatar").change(function () {
        let input = this;
        if (input.files && input.files[0]) {
            let reader = new FileReader();
            reader.onload = function (e) {
                $('#preview-image').attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    });

    $("#update-account").on("submit", function (e) {
        e.preventDefault();

        let formData = new FormData(this);
        let urlUpdate = $(this).attr('action');

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
            }
        });

        $.ajax({
            url: urlUpdate,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                $(".btn-wrapper button").text("Đang cập nhật....").attr("disabled", true); // Fixed: disable -> disabled
            },
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message);
                    //upload new image
                    if (response.avatar) {
                        $('#preview-image').attr('src', response.avatar);
                    }
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (xhr) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function (key, value) {
                    toastr.error(value[0]);
                });
            },
            complete: function () {
                $(".btn-wrapper button")
                    .text("cập nhập")
                    .attr("disabled", false);
            },
        });
    });

    //validate change-password account
    $("#change-password-form").submit(function (e) {
        e.preventDefault();
        let cureent_password = $('input[name="current_password"]').val().trim();
        let new_password = $('input[name="new_password"]').val().trim();
        let confirm_new_password = $('input[name="confirm_new_password"]').val().trim();

        let erroMessage = "";


        if (cureent_password.length < 6) {
            erroMessage += "Mật khẩu cũ có ít nhất 6 ký tự.<br/>";
        }

        if (new_password.length < 6) {
            erroMessage += "Mật khẩu mới có ít nhất 6 ký tự.<br/>";
        }

        if (new_password !== confirm_new_password) {
            erroMessage += "Mật khẩu xác nhận không khớp.<br/>";
        }
        if (erroMessage !== "") {
            toastr.error(erroMessage, "Lỗi");
            return;
        }


        let formData = $(this).serialize();
     let urlUpdate = $("#change-password-form").attr('action');
         $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
            }
        });
        $.ajax({
            url: urlUpdate,
            type: 'POST',
            data: formData,
            beforeSend: function () {
                $(".btn-wrapper button").text("Đang cập nhật....").attr("disabled", true); 
            },
            success: function (response) {
                if (response.success) {
                    toastr.success(response.message);
                    $('#change-password-form')[0].reset()

                } else {
                    toastr.error(response.message);
                }
            },
            error: function (xhr) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function (key, value) {
                    toastr.error(value[0]);
                });
            },
            complete: function () {
                $(".btn-wrapper button")
                    .text("cập nhập")
                    .attr("disabled", false);
            },
    });
    });
})
