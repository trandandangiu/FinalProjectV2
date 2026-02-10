$(document).ready(function () {
    // Management Users
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

                    // Nếu có cột hiển thị trạng thái riêng
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

    ////////////Manager Category
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

        // thêm category_id vào formData
        formData.append("id", categoryId);

        // setup CSRF token (Laravel)
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

                    //Regeneraten new HTML
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

                    //Replice old row with new row
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



    ////////////Manager Products
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

    //Update Products 
    $(".product-images").change(function (e) {
        let files = e.target.files;
        let productId = $(this).data("id"); // lấy id sản phẩm từ data-id
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
    //update products 
    $(document).on("click", ".btn-update-submit-product", function (e) {
    e.preventDefault();
    let button = $(this);
    let productId = button.data("id");
    let form = button.closest(".modal").find("form");
    let formData = new FormData(form[0]);

    formData.append("id", productId);
    // Nếu category_id không nằm trong form, append thêm:
    // formData.append("category_id", selectedCategoryId);

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


});

