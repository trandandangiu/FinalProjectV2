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
$(".profile-pic").click(function(){
    $("#avatar").click();
});

// khi chon 1 tam hinh => no hien len hinh anh 
$("#avatar").change(function(){
    let input = this; 
    if(input.files && input.files[0]){
        let reader = new FileReader();
        reader.onload = function(e){
            $('#preview-image').attr('src', e.target.result); 
        }
        reader.readAsDataURL(input.files[0]);
    }
});

$("#update-account").on("submit",function(e){
    e.preventDefault();

    let formData = new FormData(this); // Fixed: formDara -> formData
    let urlUpdate = $(this).attr('action'); // Fixed: urlupdate -> urlUpdate

    $.ajaxSetup({ // Fixed: $.ajxaSetup -> $.ajaxSetup
        headers:{
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
        }
    });

   $.ajax({
    url: urlUpdate,
    type: 'POST', 
    data: formData,
    processData: false, 
    contentType: false, // Fixed: contenType -> contentType
    beforeSend: function(){ // Fixed: beforSend -> beforeSend
        $(".btn-wrapper button").text("Đang cập nhật....").attr("disabled", true); // Fixed: disable -> disabled
    },
    success: function(response){
        if(response.success){
            toastr.success(response.message);
            //upload new image
            if(response.avatar){
                $('#preview-image').attr('src', response.avatar);
            }
        }else{
            toastr.error(response.message);          
        }
    },
    error: function(xhr){
        let errors = xhr.responseJSON.errors; // Fixed: responseJASON -> responseJSON
        $.each(errors, function(key,value) {
            toastr.error(value[0]); // Fixed: toastr.errors -> toastr.error
        });
    }, 
    complete: function() {
        $(".btn-wrapper button")
        .text("cập nhập")
        .attr("disabled", false); // Fixed: disable -> disabled
    },
   });
});

});