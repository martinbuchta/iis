function renderHall(seats, orderedSeats, hallEl, hall, price, isStatic=false)
{
    var seatSize = 20;
    var margin = 10;
    var textSpace = 80;

    hallEl.css("width", (seatSize+margin) * hall.seatsInRow + margin + textSpace);
    hallEl.css("height", (seatSize+margin) * hall.rowCount + margin);

    for (i = 0; i < hall.rowCount; i++) {
        var textEl = $('<div class="text">Řada ' + (i + 1) + '</div>');
        textEl.css("left", 15);
        textEl.css("top", (seatSize + margin) * i + 10);
        hallEl.append(textEl);
    }

    seats.forEach(function (seat) {
        var seatEl = $('<div class="seat" data-id="' + seat.id + '" data-price="' + price + '">' + seat.number + '</div>');
        seatEl.css("left", seatSize * (seat.number-1) + margin * seat.number + textSpace);
        seatEl.css("top", seatSize * (seat.row-1) + margin * seat.row);

        if (isSeatOrdered(seat, orderedSeats)) {
            seatEl.addClass("ordered");
        }

        if (isStatic == false) {
            seatEl.click(handleSeatClick);
        }

        hallEl.append(seatEl);
    });
}

function handleSeatClick()
{
    if ($(this).hasClass("ordered")) {
        return;
    }

    var seatId = $(this).data("id");
    var price = parseFloat($(this).data("price"));
    $(this).toggleClass("selected");
    $(".seat" + seatId).click();

    var seatsNumber = $(".seat.selected").length;
    var priceTotal = seatsNumber * price;
    $("#priceTotal").text(priceTotal.toString() + " Kč");

    if (seatsNumber > 0) {
        $(".JS-create-order-btn").removeClass("disabled");
    } else {
        $(".JS-create-order-btn").addClass("disabled");
    }
}

function isSeatOrdered(seat, orderedSeats)
{
    var isOrdered = false;

    orderedSeats.forEach(function (orderedSeat) {
        if (orderedSeat.id == seat.id) {
            isOrdered = true;
        }
    });

    return isOrdered;
}

$(function() {
    $(".JS-create-order-btn").click(function () {
        if ($(this).hasClass("disabled")) {
            alert("Vyberte prosím sedadlo.");
            return false;
        }

        return true;
    });
});
