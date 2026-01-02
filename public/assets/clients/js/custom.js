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
});