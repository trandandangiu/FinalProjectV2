$(document).ready(function () {

    /*****************************************************************
     * MANAGEMENT USERS
     *****************************************************************/
    $(document).on('click', '.upgradeStaff', function (e) {
        e.preventDefault();

        let button = $(this);
        let userId = button.data('user-id');
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
            }
        });


        $.ajax({
            type: 'POST',
            url: "user/upgrade",
            data: {
                user_id: userId,
            },
            success: function (response) {

                if (response.status) {
                    toastr.success(response.message);
                    button.closest('.profile_view').find('.brief i').text('STAFF');
                    button.closest('.profile_view').find('.changeStatus').hide();
                    button.hide();
                } else {
                    toastr.error(response.message)
                }
            },
            error: function (xhr, status, error) {
                alert('An error occurred:' + error);
            }
        });
    });

    $(document).on('click', '.changeStatus', function (e) {
        let button = $(this);
        let userId = button.data('user-id');
        let status = button.data('status');
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
            }
        });

        $.ajax({
            url: "user/updateStatus",
            type: 'POST',
            data: {
                user_id: userId,
                status: status,
            },
            success: function (response) {
                if (response.status) {
                    toastr.success(response.message);

                    if (status === "banned") {
                        button.text("Đã chặn");
                    } else if (status === "active") {
                        button.text("Hoạt động");
                    } else if (status === "deleted") {
                        button.text("Đã xóa");
                    }

                    button.addClass("disabled").prop("disabled", true);


                    $('#status-' + userId).text(status);
                } else {
                    toastr.error(response.message);
                }
            }
        });
    });

    $('.btn_reset').on('click', function () {
        let form = $(this).closet('form');
        form.strigger('reset');
        form.find('.input[type="file"]').val('');
        form.find('#image-preview').html('');
        form.find('#image-preview').atr('src', '');
        form.find('#image-preview-container').html('');
    });


    /*****************************************************************
     * MANAGEMENT CATEGORY
     *****************************************************************/
    $(".category-image").change(function () {
        let file = this.files[0];
        let categoryId = $(this).data("id");
        if (file) {
            let reader = new FileReader();
            reader.onload = function (e) {
                $("#image-preview-" + categoryId).attr("src", e.target.result);
            };
            reader.readAsDataURL(file);
        } else { $("#image-preview-" + categoryId).attr("src", ""); }
    });

    // UPDATE CATEGORY
    $(document).on("click", ".btn-update-submit-category", function (e) {
        e.preventDefault();

        let button = $(this);
        let categoryId = button.data("id");
        let form = button.closest(".modal").find("form");

        let formData = new FormData(form[0]);


        formData.append("id", categoryId);


        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            url: "categories/update",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {
                button.prop("disabled", true);
                button.text("Đang cập nhật...");
            },
            success: function (response) {
                if (response.status) {
                    let categoryId = response.data.id;

                    let newRow = `
<tr id="category-row-${categoryId}"> 
  <td>
    <img src="${response.data.image}" alt="${response.data.name}" 
         class="img-thumbnail" style="width: 50px;">
  </td>
  <td>${response.data.name}</td>
  <td>${response.data.slug}</td>
  <td>${response.data.description}</td>
  <td>
    <a class="btn btn-app btn-update-category" 
       data-bs-toggle="modal"
       data-bs-target="#exampleModal-${categoryId}">
       <i class="fa fa-edit"></i>
       Chỉnh sửa
    </a>
  </td>
  <td>
    <a class="btn btn-app btn-delete-category" data-id="${categoryId}">
      <i class="fa fa-close"></i> Xóa
    </a>
  </td>
</tr>`;


                    $('#category-row' + categoryId).replaceWith(newRow);
                    $(".modalUpdate" + categoryId).modal("hide");
                } else {
                    toastr.error(response.message);
                }
            },
            error: function () {
                toastr.error("Có lỗi xảy ra, vui lòng thử lại!");
            },
            complete: function () {
                button.prop("disabled", false);
                button.text("Cập nhật");
            }
        });
    });
    // delete 
    $(document).on("click", ".btn-delete-category", function (e) {
        e.preventDefault();
        let button = $(this);
        let categoryId = button.data("id");
        let row = button.closest("tr");

        if (confirm("Bạn có chắc muốn xóa danh mục này?")) {
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
            });

            $.ajax({
                url: "categories/delete",
                type: "POST",
                data: {
                    category_id: categoryId,
                },
                success: function (response) {
                    if (response.status) {
                        toastr.success(response.message);
                        row.fadeOut(300, function () {
                            $(this).remove();
                        });
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function (xhr, status, error) {
                    alert("Có lỗi xảy ra khi xóa! " + error);
                }
            });
        }
    });



    /*****************************************************************
     * MANAGEMENT PRODUCTS
     *****************************************************************/
    $("#product-images").change(function (e) {
        let files = e.target.files;
        let previewContainer = $("#images-preview-container");
        previewContainer.empty();

        if (files.length > 0) {
            for (let i = 0; i < files.length; i++) {
                let file = files[i];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function (event) {
                        let img = $("<img>")
                            .attr("src", event.target.result)
                            .addClass("image-preview")
                        previewContainer.append(img);
                    };
                    reader.readAsDataURL(file);
                }
            }
        } else {
            previewContainer.html("<p>Không có ảnh nào được chọn</p>");
        }
    });


    /*****************************************************************
     * IMAGE-PRODUCTS
     *****************************************************************/
    $(".product-images").change(function (e) {
        let files = e.target.files;
        let productId = $(this).data("id");
        let previewContainer = $("#image-preview-container-" + productId);
        previewContainer.empty();

        if (files.length > 0) {
            for (let i = 0; i < files.length; i++) {
                let file = files[i];
                if (file) {
                    let reader = new FileReader();
                    reader.onload = function (e) {
                        let img = $("<img>")
                            .attr("src", e.target.result)
                            .addClass("image-preview-");
                        img.css({
                            "max-width": "150px",
                            "max-height": "150px",
                            "margin": "5px",
                            "border-radius": "5px",
                        });
                        previewContainer.append(img);
                    };
                    reader.readAsDataURL(file);
                }
            }
        } else {
            previewContainer.html("");
        }
    });

    /*****************************************************************
     * UPDATE PRODUCTS
     *****************************************************************/
    $(document).on("click", ".btn-update-submit-product", function (e) {
        e.preventDefault();
        let button = $(this);
        let productId = button.data("id");
        let form = button.closest(".modal").find("form");
        let formData = new FormData(form[0]);

        formData.append("id", productId);
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            url: "products/update",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            beforeSend: function () {
                button.prop("disabled", true);
                button.text("Đang cập nhật...");
            },
            success: function (response) {
                if (response.status) {
                    toastr.success(response.message);
                    let product = response.data;
                    let productId = product.id;
                    let imageSrc = product.images.length > 0 ? product.images[0] : "storage/products/default.png";

                    let newRow = `
<tr id="product-row-${productId}">
    <td>
        <img src="${imageSrc}" alt="${product.name}" class="image-product" width="80">
    </td>
    <td>${product.name}</td>
    <td>${product.category_name}</td>
    <td>${product.slug}</td>
    <td>${product.description}</td>
    <td>${product.stock}</td>
    <td>${Number(product.price).toLocaleString('vi-VN')} VND</td>
    <td>${product.unit}</td>
    <td>
        <a class="btn btn-app btn-update-product" data-toggle="modal"
           data-target="#modalUpdate-${productId}">
            <i class="fa fa-edit"></i>Chỉnh sửa
        </a>
    </td>
    <td>
        <a class="btn btn-app btn-delete-product" data-id="${productId}">
            <i class="fa fa-trash"></i>Xóa
        </a>
    </td>
</tr>
`;
                    $("#product-row-" + productId).replaceWith(newRow);
                    $("#modalUpdate-" + productId).modal("hide");
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
                alert("Có lỗi xảy ra khi cập nhật! " + error);
            },
            complete: function () {
                button.prop("disabled", false);
                button.text("Chỉnh sửa");
            },
        });
    });
    $(document).on("click", ".btn-delete-product", function (e) {
        e.preventDefault();
        let button = $(this);
        let productId = button.data("id");

        if (!confirm("Bạn có chắc chắn muốn xóa sản phẩm này?")) {
            return;
        }

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            url: "products/delete",
            type: "POST",
            data: { id: productId },
            success: function (response) {
                if (response.status) {
                    toastr.success(response.message);
                    $("#product-row-" + productId).remove(); // Xóa dòng khỏi bảng
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
                alert("Có lỗi xảy ra khi xóa! " + error);
            }
        });
    });



    /*****************************************************************
     * MANAGEMENT ORDERS
     *****************************************************************/
    $(document).on('click', '.confirm-order', function (e) {
        e.preventDefault();
        let button = $(this);
        let orderId = button.data("id");

        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
            }
        });
        $.ajax({
            type: 'POST',
            url: "orders/confirm",
            data: {
                id: orderId,
            },
            success: function (response) {

                if (response.status) {
                    toastr.success(response.message);
                    button.closest('tr')
                        .find('.order-status')
                        .text('Đang giao');
                    button.hide();
                } else {
                    toastr.error(response.message)
                }
            },
            error: function (xhr, status, error) {
                alert('An error occurred:' + error);
            }
        });
    });


    /*****************************************************************
     * SEND-INVOICE-MAIL
     *****************************************************************/

    $(document).on('click', '.send-invoice-mail', function (e) {
        e.preventDefault();
        let button = $(this);
        let orderId = button.data("id");
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
            }
        });
        $.ajax({
            type: 'POST',
            url: "/admin/orders/send-invoice-mail",
            data: {
                id: orderId,
            },
            success: function (response) {
                if (response.status) {
                    toastr.success(response.message);
                    button.remove();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function (xhr, status, error) {
                alert('An error occurred:' + error);
            }
        });
    });


    /*****************************************************************
     * MANAGEMENT PROFILE
     *****************************************************************/
    $(document).on('click', '.cancel-order', function (e) {
        e.preventDefault();
        let button = $(this);
        let orderId = button.data("id");
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
            }
        });
        $.ajax({
            type: 'POST',
            url: "/admin/orders/cancel",
            data: {
                id: orderId,
            },
            success: function (response) {
                if (response.status) {
                    toastr.success(response.message);
                    button.closest('tr')
                        .find('.order-status')
                        .text('Đã hủy');
                    button.hide();
                } else {
                    toastr.error(response.message)
                }
            },
            error: function (xhr, status, error) {
                alert('An error occurred:' + error);
            }
        });

    });




    /*****************************************************************
     * MANAGEMENT CONTACT
     *****************************************************************/
$(document).on('click', '.contact_item', function (e) {
    // Dùng .attr() để đảm bảo lấy giá trị mới nhất của từng phần tử
    let contactName    = $(this).attr('data-name');
    let contactEmail   = $(this).attr('data-email');
    let contactMessage = $(this).attr('data-message');
    let contactId      = $(this).attr('data-id');
    let isReplied      = $(this).attr('data-is_replied');

    // Đổ dữ liệu vào khung hiển thị (Content Mail)
    $(".mail_view .sender-info strong").text(contactName);
    $(".mail_view .sender-info span").text('(' + contactEmail + ')');
    $(".mail_view .view_mail p").text(contactMessage);
console.log("Bạn vừa click vào email:", $(this).attr('data-email'));
    $('.mail_view').show();

    // Cập nhật thông tin cho nút "Gửi phản hồi"
    if (isReplied != 0) {
        $("#compose").hide(); 
    } else {
        $("#compose").show();
        // QUAN TRỌNG: Gán lại email mới cho nút Send mỗi khi click item khác nhau
        $('.send-reply-contact').attr('data-email', contactEmail);
        $('.send-reply-contact').attr('data-id', contactId);
    }
});

$(document).on('click', '.send-reply-contact', function (e) {
    e.preventDefault();
    let button = $(this);
    
    // Lấy data đã được set ở bước trên
    let email = button.data('email');
    let contactId = button.data('id');
    

    let message = $("#editor-contact").val().trim(); 

    if (message === "") {
        toastr.warning("Bạn chưa nhập nội dung phản hồi!");
        return;
    }

    // Hiệu ứng chờ (optional)
    button.prop('disabled', true).text('Sending...');

    $.ajax({
        type: 'POST',
        url: "/admin/contact/reply",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr('content'),
        },
        data: {
            email: email,
            message: message,
            contact_id: contactId,
        },
        success: function (response) {
            if (response.status) {
                toastr.success(response.message);
                $(".mail_view").hide();
                $(".compose").hide(); // Ẩn khung soạn thảo (class của bạn là .compose)
                $("#editor-contact").val(""); // Xóa nội dung cũ
                
                // Cập nhật trạng thái tại chỗ trên giao diện (đổi màu icon)
                $(`.contact_item[data-id="${contactId}"]`).find('i.fa-circle').css('color', 'red');
                $(`.contact_item[data-id="${contactId}"]`).data('is_replied', 1);
            } else {
                toastr.error(response.message);
            }
        },
        error: function (xhr, status, error) {
            alert('An error occurred: ' + error);
        },
        complete: function() {
            button.prop('disabled', false).text('Send');
        }
    });
});

    /*****************************************************************
     * MANAGEMENT PROFILE
     *****************************************************************/
$(document).ready(function() {
    // Toggle form đổi mật khẩu
    $(".form-change-pass").on("click", function () {
        $("#change-password").toggle();
        $(this).text($("#change-password").is(":visible") ? "Hủy đổi mật khẩu" : "Đổi mật khẩu");
    });

    // Upload ảnh
    $(".update-image").click(function () { $("#avatar").trigger("click"); });

    $("#avatar").on("change", function () {
        let file = this.files[0];
        if (file) {
            let formData = new FormData();
            formData.append("type", "avatar");
            formData.append("avatar", file);
            updateProfile(formData, "avatar");
        }
    });

    // Submit thông tin cá nhân
    $("#update-profile").on("submit", function (e) {
        e.preventDefault();
        let name = $("#name").val().trim();
        let phone = $("#phone").val().trim();
        let address = $("#address").val().trim();

        if (name.length < 3) return toastr.error("Tên quá ngắn");
        if (!/^(0\d{9})$/.test(phone)) return toastr.error("SĐT không hợp lệ");

        let formData = new FormData();
        formData.append('type', "profile");
        formData.append("name", name);
        formData.append("phone", phone);
        formData.append("address", address);
        updateProfile(formData, "profile");
    });

    // Submit đổi mật khẩu
    $("#change-password").on("submit", function (e) {
        e.preventDefault();
        let curPass = $("#current_password").val();
        let newPass = $("#new_password").val();
        let confPass = $("#confirm_password").val();

        if (newPass.length < 6) return toastr.error("Mật khẩu mới ít nhất 6 ký tự");
        if (newPass !== confPass) return toastr.error("Xác nhận mật khẩu không khớp");

        let formData = new FormData();
        formData.append('type', "password");
        formData.append("current_password", curPass);
        formData.append("new_password", newPass);
        formData.append("confirm_password", confPass);
        updateProfile(formData, "password");
    });

    function updateProfile(formData, type) {
        $.ajax({
            url: "profile/update",
            type: "POST",
            headers: { "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content") },
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
              if (response.status) {
    toastr.success(response.message);
    
    // Lấy type từ FormData object
    let updateType = formData.get("type"); 

    if (updateType === "profile") {
        // Cập nhật text hiển thị ở danh sách thông tin bên trái
        // Đảm bảo ID này khớp với các thẻ <span> hoặc <h3> ở cột bên trái
        $("#user-name").text(response.data.name);
        $("#user-phone").text(response.data.phone_number);
        $("#user-address").text(response.data.address);
        
    } else if (updateType === "password") {
        $("#change-password")[0].reset();
        $("#change-password").hide();
        $(".form-change-pass").text("Đổi mật khẩu");
        
    } else if (updateType === "avatar") {
        $("#avatar-img").attr("src", response.avatar_url);
        // Cập nhật ảnh nhỏ trên góc phải màn hình (header) nếu có
        $(".profile_info img, .nav-user-menu img").attr("src", response.avatar_url);
    }
} else {
    toastr.error(response.message);
}
            },
            error: function () { toastr.error("Lỗi kết nối máy chủ!"); }
        });
    }
});

});