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

        let errorMessage = "";

        if (name.length < 3) {
            errorMessage += "Họ và tên phải có ít nhất 3 ký tự.<br/>";
        }

        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            errorMessage += "Email không hợp lệ.<br/>";
        }

        if (password.length < 6) {
            errorMessage += "Mật khẩu phải có ít nhất 6 ký tự.<br/>";
        }

        if (password !== confirmPassword) {
            errorMessage += "Mật khẩu xác nhận không khớp.<br/>";
        }

        if (!checkbox1 || !checkbox2) {
            errorMessage += "Bạn phải đồng ý với các điều khoản.<br/>";
        }

        if (errorMessage !== "") {
            toastr.error(errorMessage, "Lỗi đăng ký");
            e.preventDefault();
        }
    });

    //validate form login
    $("#login-form").submit(function (e) {
        toastr.clear();
        let email = $('input[name="email"]').val();
        let password = $('input[name="password"]').val();

        let errorMessage = "";

        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            errorMessage += "Email không hợp lệ.<br/>";
        }

        if (password.length < 6) {
            errorMessage += "Mật khẩu phải có ít nhất 6 ký tự.<br/>";
        }

        if (errorMessage !== "") {
            toastr.error(errorMessage, "Lỗi");
            e.preventDefault();
        }
    });

    //validate reset-password-form
    $("#reset-password-form").submit(function (e) {
        toastr.clear();
        let email = $('input[name="email"]').val();
        let password = $('input[name="password"]').val();
        let confirmPassword = $('input[name="password_confirmation"]').val();

        let errorMessage = "";

        let emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            errorMessage += "Email không hợp lệ.<br/>";
        }

        if (password.length < 6) {
            errorMessage += "Mật khẩu phải có ít nhất 6 ký tự.<br/>";
        }
        if (password !== confirmPassword) {
            errorMessage += "Mật khẩu xác nhận không khớp.<br/>";
        }
        if (errorMessage !== "") {
            toastr.error(errorMessage, "Lỗi");
            e.preventDefault();
        }
    });

    // page account

    //when clicking on the image => open input file
    $(".profile-pic").click(function () {
        $("#avatar").click();
    });

    // khi chọn một tấm hình => nó hiện lên hình ảnh
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
                $(".btn-wrapper button").text("Đang cập nhật....").attr("disabled", true);
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
        let current_password = $('input[name="current_password"]').val().trim();
        let new_password = $('input[name="new_password"]').val().trim();
        let confirm_new_password = $('input[name="confirm_new_password"]').val().trim();

        let errorMessage = "";

        if (current_password.length < 6) {
            errorMessage += "Mật khẩu cũ có ít nhất 6 ký tự.<br/>";
        }

        if (new_password.length < 6) {
            errorMessage += "Mật khẩu mới có ít nhất 6 ký tự.<br/>";
        }

        if (new_password !== confirm_new_password) {
            errorMessage += "Mật khẩu xác nhận không khớp.<br/>";
        }
        if (errorMessage !== "") {
            toastr.error(errorMessage, "Lỗi");
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
                    $('#change-password-form')[0].reset();
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

    //validate change address
    $("#addAddressForm").submit(function (e) {
        e.preventDefault();
        // delete old error

        let isValid = true;
        $('.error-message').remove();
        $('.erro-text-danger').remove();
        $('.erro-message-text-danger').remove();

        let fullName = $("#full_name").val().trim();
        let phone = $("#phone").val().trim();
        let address = $("#address").val().trim();
        let city = $("#city").val().trim();

        // Validate full name
        if (fullName.length < 3) {
            isValid = false;
            $('#full_name').after(
                '<p class="error-message text-danger">Họ và tên phải có ít nhất 3 ký tự</p>'
            );
        }

        // Validate phone
        let phoneRegex = /^[0-9]{10,11}$/;
        if (!phoneRegex.test(phone)) {
            isValid = false;
            $('#phone').after(
                '<p class="error-message text-danger">Số điện thoại phải có 10-11 chữ số</p>'
            );
        }

        // Validate address
        if (address.length === 0) {
            isValid = false;
            $('#address').after(
                '<p class="error-message text-danger">Vui lòng nhập địa chỉ</p>'
            );
        }

        // Validate city
        if (city.length === 0) {
            isValid = false;
            $('#city').after(
                '<p class="error-message text-danger">Vui lòng nhập thành phố/tỉnh</p>'
            );
        }

        if (isValid) {
            this.submit();
        }
    });

    //Page products - chỉ chạy khi element tồn tại
    let currentPage = 1;
    $(document).on('click', '.pagination-link', function (e) {
        e.preventDefault();
        let pageUrl = $(this).attr('href');
        let page = pageUrl.split('page=')[1];
        currentPage = page;
        fetchProducts();
    });


//product load function (combining filter + pagination)
    function fetchProducts() {
        let category_id = $(".category-filter.active").data('id') || '';
        let min_price = $("#min_price").val();
        let max_price = $("#max_price").val();
        let sort_by = $("#sort-by").val();

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
            }
        });
        $.ajax({
            url: 'products/filter?page=' + currentPage,
            type: 'GET',
            data: {
                category_id: category_id,
                min_price: min_price,
                max_price: max_price,
                sort_by: sort_by,
            },
            beforeSend: function () {
                $("#loading-spinner").show();
                $("#liton_product_grid").hide();

            },
            success: function (response) {
                $("#liton_product_grid").html(response.products);
                 $(".ltn__pagination").html(response.pagination);
            },
            complete: function () {
                $("#loading-spinner").hide();
                $("#liton_product_grid").show();
            },
            error: function (xhr) {
                alert('có lỗi xảy ra với ajax fetchProducts');
            },
        });
    }

    $(".category-filter").click(function () {
        $(".category-filter").removeClass("active");
        $(this).addClass("active");
        currentPage = 1; // reset về trang 1 khi lọc
        fetchProducts();
    })

    $("#sort-by").change(function () {
          currentPage = 1;
        fetchProducts();
    })


    $(".slider-range").slider({
        range: true,
        min: 0,
        max: 3000000,
        values: [0, 3000000],
        slide: function (event, ui) {
            // Format số với dấu phân cách hàng nghìn
            const formatPrice = (price) => {
                return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
            };

            $(".amount").val(formatPrice(ui.values[0]) + " - " + formatPrice(ui.values[1]) + " vnđ");

            // Cập nhật giá trị vào hidden inputs
            $("#min_price").val(ui.values[0]);
            $("#max_price").val(ui.values[1]);
        },
        change: function (event, ui) {
              currentPage = 1;
            fetchProducts();
        }
    });
    // Khởi tạo giá trị ban đầu
    const formatPrice = (price) => {
        return price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
    };
    $(".amount").val(
        formatPrice($(".slider-range").slider("values", 0)) +
        " - " +
        formatPrice($(".slider-range").slider("values", 1)) +
        " vnđ"
    );
    // Cập nhật hidden inputs ban đầu
    $("#min_price").val($(".slider-range").slider("values", 0));
    $("#max_price").val($(".slider-range").slider("values", 1));
});