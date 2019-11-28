function addCategory() {
    var name = $("#category_name").val();
    $("#category_name").val("");

    if (name.length < 1) {
        return false;
    }

    $.ajax("/admin/play/add-category", {
        type: "POST",
        data: {"name": name},
        statusCode: {
            200: function (response) {
                $("#addCategoryModal .invalid-feedback").removeClass("d-block").addClass("d-none");
                $("#addCategoryModal input").removeClass("is-invalid");
                $("#play_category").append("<option selected value='" + response.id + "' >" + response.name + "</option>");
                $("#addCategoryModal").modal("hide");
            },
            400: function () {
                $("#addCategoryModal .invalid-feedback").removeClass("d-none").addClass("d-block");
                $("#addCategoryModal input").addClass("is-invalid");
                $("#addCategoryModal .form-error-message").text('Kategorie "' + name + '" už existuje.');
            }
        }
    });

    return false;
}

$("#addCategoryModal form").submit(addCategory);