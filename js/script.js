$(document).ready(function(){
    $('input[type="tel"]').inputmask({ "mask": "+7 (999) 999-99-99" }); //specifying options


    $('form').each(function () {
        $(this).validate({
            focusInvalid: false,
            rules: {
                client_phone: {
                    required: true,
                },
                client_email: {
                    required: true,
                },
                client_name: {
                    required: true,
                    maxlength: 5,
                },
                client_lead_price: {
                    number: true
                }
            },
            messages: {
                client_phone: {
                    required: 'Нужно что-то ввести'
                },
                client_email: {
                    required: 'Нужно что-то ввести'
                },
                client_name: {
                    required: 'Нужно что-то ввести',
                    maxlength: 'Нужно ввести максимум 5 букв'
                },
                client_lead_price: {
                    number: 'Нужно ввести число'
                }
            },
            submitHandler(form) {
                console.log('send');
                let th = $(form);

                $.ajax({
                    type: 'POST',
                    url: 'controller.php',
                    data: th.serialize(),
                    // eslint-disable-next-line func-names
                }).done(() => {
                    console.log("Отправлено");
                    th.trigger('reset');
                });

                return false;
            }
        });
    });
});