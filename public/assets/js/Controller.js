class Controller {

    constructor() {
        AOS.init();
        this.menu();
        this.modal();
        this.clickButtons();
    }

    menu() {

        $("#mobile-button").on("click", function() {
            if ($(".menu-mobile").hasClass("slidein")) {
                $(".menu-mobile").removeClass("slidein");
                $(".menu-mobile").addClass("slideout");
                setTimeout(() => {
                    $(".menu-mobile").css("display", "none");
                }, 400);
            } else {
                $(".menu-mobile").css("display", "block");
                $(".menu-mobile").addClass("slidein");
                $(".menu-mobile").removeClass("slideout");
            }
        });

    }

    modal() {
        $(".modal-item").hide();

        $("[data-modal-button]").on("click", function() {

            $("#" + $(this).data().modalButton).fadeIn();
            $("#" + $(this).data().modalButton).css("visibility", "visible");
            $("body").css("overflow", "hidden");

        });

        $(".close-this").each(function() {
            $(this).on("click", function() {
                $(".modal-item").fadeOut();
                $("body").css("overflow", "auto");
            });
        });
    }

    clickButtons() {
        $("[data-btn]").on("click", function() {
            document.querySelector("#" + $(this).attr("data-btn")).scrollIntoView(true);
        });
    }

    contact() {
        $("#send").on("click", function() {
            if ($("#name").val() == "" || $("#email").val() == "" || $("#phone").val() == "" || $("#product").val() == "") {
                swal('Preencha todos os campos requisitados!');
                return;
            }
            $.ajax({
                method: "POST",
                url: "/sendmail",
                data: {
                    name: $("#name").val(),
                    email: $("#email").val(),
                    phone: $("#phone").val(),
                    product: $("#product").val(),
                    msg: $("#msg").val()
                },
                success: function(result) {
                    swal("Orçamento solicitado com sucesso!", "", "success");
                },
                error: function() {
                    swal('Oops! Algo deu errado...', 'error');
                }
            });
        });
    }

}