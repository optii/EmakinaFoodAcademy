/**
 * Created by dan on 14/02/2017.
 */

$(document).ready(function () {
    $('.message .close').on('click', function () {
        $(this).closest('.message').transition('fade');
    });
    $('.ui.checkbox').checkbox();
    $('.ui.dropdown').dropdown();
    $('.ui.calendar').calendar({
        monthFirst: false,
        formatter: {
            datetime: function(date, settings){
                if(!date) return '';
                return formatLocalDate(date);
            }
        }
    });

    $('.slider').slick({
        autoplay: true,
        nextArrow: false,
        prevArrow: false,
    });

    $('.menus .image').dimmer({
        on: 'hover'
    });

    $('.dishes .image').dimmer({
        on: 'hover'
    });

    $('#reservation-submit').click(function(){
        $('#modalDate').text($('#damdan_appbundle_reservation_date').val());
        $('#modalPeople').text($('#damdan_appbundle_reservation_seats').val());
        $('#modalMail').text($('#damdan_appbundle_reservation_email').val());
        $('#modalName').text($('#damdan_appbundle_reservation_name').val());
        $('#modalPhone').text($('#damdan_appbundle_reservation_phone').val());
        $('.ui.basic.reservation.modal')
            .modal({
                onApprove : function() {
                    $('form').trigger('submit');
                }
            })
            .modal('show')
        ;
    });
});

function formatLocalDate(date) {
    var now = date,
        tzo = -now.getTimezoneOffset(),
        dif = tzo >= 0 ? '+' : '-',
        pad = function(num) {
            var norm = Math.abs(Math.floor(num));
            return (norm < 10 ? '0' : '') + norm;
        };
    return now.getFullYear()
        + '-' + pad(now.getMonth()+1)
        + '-' + pad(now.getDate())
        + 'T' + pad(now.getHours())
        + ':' + pad(now.getMinutes())
        + ':' + pad(now.getSeconds())
        + dif + pad(tzo / 60)
        + ':' + pad(tzo % 60);
}