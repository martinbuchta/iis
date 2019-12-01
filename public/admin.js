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


function addGenre() {
    var name = $("#genre_name").val();
    $("#genre_name").val("");

    if (name.length < 1) {
        return false;
    }

    $.ajax("/admin/play/add-genre", {
        type: "POST",
        data: {"name": name},
        statusCode: {
            200: function (response) {
                $("#addGenreModal .invalid-feedback").removeClass("d-block").addClass("d-none");
                $("#addGenreModal input").removeClass("is-invalid");
                $("#play_genres").append('' +
                    '<div class="form-check">' +
                    '<input type="checkbox" checked id="play_genres_' + response.id + '" name="play[genres][]" class="form-check-input" value="' + response.id + '">' +
                    '<label class="form-check-label" for="play_genres_' + response.id + '">' + response.name + '</label>' +
                    '</div>');
                $("#addGenreModal").modal("hide");
            },
            400: function () {
                $("#addGenreModal .invalid-feedback").removeClass("d-none").addClass("d-block");
                $("#addGenreModal input").addClass("is-invalid");
                $("#addGenreModal .form-error-message").text('Žánr "' + name + '" už existuje.');
            }
        }
    });

    return false;
}
$("#addGenreModal form").submit(addGenre);

$(".custom-file").removeClass("custom-file");
$(".custom-file-input").removeClass("custom-file-input");
$(".custom-file-label").removeClass("custom-file-label");

if ($(".invalid-feedback .d-block:contains('Soubor je příliš velký')").length > 1) {
    $(".invalid-feedback .d-block:contains('Soubor je příliš velký')").slice(1).remove();
}

$(".JS-reservation-edit #reservation_edit_user").change(function() {
    var user = parseInt($("#reservation_edit_user").val());

    if (user > 0) {
        $(".JS-no-user input").prop("required", false);
        $(".JS-no-user").hide();
    } else {
        $(".JS-no-user input").prop("required", true);
        $(".JS-no-user").show();
    }
}).change();

$(".JS-registration-page #registration_form_role, .JS-user-edit #user_admin_role").change(function() {
    var role = $(this).val();

    if (role == "ROLE_POKLADNI") {
        $("#halls").show();
    } else {
        $("#halls").hide();
    }
}).change();
