function renderHallClickable(seats, orderedSeats, hallEl, hall)
{
    console.debug(seats);
    console.debug(hallEl);
    console.debug(orderedSeats);
    console.debug(hall);

    var seatSize = 20;
    var margin = 10;
    var textSpace = 80;

    hallEl.css("width", (seatSize+margin) * hall.rowCount + margin + textSpace);
    hallEl.css("height", (seatSize+margin) * hall.seatsInRow + margin);

    for (i = 0; i < hall.rowCount; i++) {
        var textEl = $('<div class="text">Řada ' + (i + 1) + '</div>');
        textEl.css("left", 15);
        textEl.css("top", (seatSize + margin) * i + 10);
        hallEl.append(textEl);
    }

    seats.forEach(function (seat) {
        var seatEl = $('<div class="seat"></div>');
        seatEl.css("left", seatSize * (seat.number-1) + margin * seat.number + textSpace);
        seatEl.css("top", seatSize * (seat.row-1) + margin * seat.row);

        hallEl.append(seatEl);
    });
}