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

    //DETAIL PAGE
    // *********************
    if (window.location.pathname !== '/cart') {
        $(document).on('click', '.qtybutton', function () {
            var $button = $(this);
            var $input = $button.siblings('input');
            var oldValue = parseInt($input.val());
            var maxStock = parseInt($input.data('max'));

            if ($button.hasClass('inc')) {
                if (oldValue < maxStock) {
                    $input.val(oldValue + 1);
                }
            } else {
                if (oldValue > 1) {
                    $input.val(oldValue - 1);
                }
            }
        });
    } else {
        $(document).on('click', '.qtybutton', function () {
            let $button = $(this);
            let $input = $button.siblings('input');
            let oldValue = parseInt($input.val());
            let maxStock = parseInt($input.data('max'));
            let productId = $input.data('id');
            let newValue = oldValue;


            if ($button.hasClass('inc') && oldValue < maxStock) {
                newValue = oldValue + 1;

            } else if ($button.hasClass('dec') && oldValue > 1) {
                newValue = oldValue - 1;
            }

            if (newValue != oldValue) {
                updateCart(productId, newValue, $input);
            }
        });
    }
    //add_to_cart
    $(document).on('click', '.add-to-cart-btn', function (e) {
        e.preventDefault();

        let $button = $(this);
        let productId = $button.data('id');
        let quantityElement = $button.closest('li').prev().find('.cart-plus-minus-box');
        let quantity = quantityElement.val() ? parseInt(quantityElement.val()) : 1;

        // Lưu text gốc của button
        let originalText = $button.html();

        // Hiển thị trạng thái loading
        $button.prop('disabled', true).html('Đang thêm...');

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
            }
        });

        $.ajax({
            url: '/cart/add',
            type: 'POST',
            data: {
                product_id: productId,
                quantity: quantity,
            },
            success: function (response) {
                $('#add_to_cart_modal-' + productId).modal('show');
                $('#quick_view_modal-' + productId).modal('hide');
                $('#cart_count').text(response.cart_count);

            },
            error: function (xhr) {
                console.error(xhr);

                // Hiển thị lỗi từ server nếu có
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    alert('Lỗi: ' + xhr.responseJSON.message);
                } else {
                    alert('Có lỗi xảy ra khi thêm vào giỏ hàng');
                }
            },
            complete: function () {
                // Khôi phục button về trạng thái ban đầu
                $button.prop('disabled', false).html(originalText);
            }
        });
    });


    //Cart
    // *********************
    //mini cart
    $('.mini-cart-icon').on('click', function (e) {

        $.ajax({
            url: '/mini-cart',
            type: 'GET',
            success: function (response) {
                if (response.status) {
                    $('#ltn__utilize-cart-menu .ltn__utilize-menu-inner').html(response.html);
                    $('#ltn__utilize-cart-menu').addClass('ltn__utilize-open');
                } else {
                    toastr.error('Không thể tải giỏ hàng nhỏ.');
                }
            }
        });
    });

    $(document).on('click', '.ltn__utilize-close', function (e) {
        $('#ltn__utilize-cart-menu').removeClass('ltn__utilize-open');
        $('#ltn__utilize-overlay').hide();
    });


    //remove product form cart
    $(document).on('click', '.mini-cart-item-delete', function (e) {
        let productId = $(this).data('id');

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
            }
        });

        $.ajax({
            url: '/cart/remove',
            type: 'POST',
            data: {
                product_id: productId
            },
            success: function (response) {
                if (response.status) {
                    $('#cart_count').text(response.cart_count);
                    $('.mini-cart-icon').click();
                }
            }
        });
    });

    //page cart


    //cập nhật số lượng sản phẩm ở page cart
    function updateCart(productId, quantity, $input) {
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
            }
        });

        $.ajax({
            url: '/cart/update',
            type: 'POST',
            data: {
                product_id: productId,
                quantity: quantity
            },
            success: function (response) {
                $input.val(response.quantity);
                $input.closest('tr').find('.cart-product-subtotal').text(response.total);
                $('.cart-total').text(response.total);
                $('.cart-grand-total').text(response.grandTotal);


            },
            error: function (xhr) {
                alert(xhr.responseJSON.error);
            }
        });

    }


    //xóa sản phẩm ở page Cart
    $('.remove-from-cart').on('click', function (e) {
        let productId = $(this).data('id')
        let row = $(this).closest('tr')
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
            }
        });

        $.ajax({
            url: '/cart/remove-cart',
            type: 'POST',
            data: {
                product_id: productId,
            },
            success: function (response) {
                row.remove();
                $('.cart-total').text(response.total);
                $('.cart-grand-total').text(response.grandTotal);
                if($('.cart-product-remove').length === 0)
             {
                location.reload();
             }


            },
            error: function (xhr) {
                alert(xhr.responseJSON.error);
            }
        });

    });

});