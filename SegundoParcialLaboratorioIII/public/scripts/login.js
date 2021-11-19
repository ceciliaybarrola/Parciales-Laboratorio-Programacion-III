"use strict";
/// <reference path="../node_modules/@types/jquery/index.d.ts" />
var APIREST = "http://api_slim4/";
var SegundoParcial;
(function (SegundoParcial) {
    var Login = /** @class */ (function () {
        function Login() {
        }
        Login.Login = function () {
            var correo = $("#email").val();
            var clave = $("#password").val();
            var dato = {};
            dato.correo = correo;
            dato.clave = clave;
            $.ajax({
                type: 'POST',
                url: APIREST + "login/",
                dataType: "json",
                data: { "user": JSON.stringify(dato) },
                async: true
            })
                .done(function (resultado) {
                console.log(resultado);
                if (resultado.exito) {
                    localStorage.setItem("user", resultado.usuario);
                    window.location.replace(APIREST + "front-end-principal");
                }
            })
                .fail(function (jqXHR, textStatus, errorThrown) {
                var resultado = JSON.parse(jqXHR.responseText);
                console.log(resultado);
                $("#errorLogin").removeClass("hide");
                if (resultado.status == 409) {
                    $("#errorLogin").html("Error! " + resultado.mensaje);
                }
                else {
                    $("#errorLogin").html("Error! No existe el usuario");
                }
            });
        };
        Login.IrRegistro = function () {
            window.location.replace(APIREST + "front-end-registro");
        };
        return Login;
    }());
    SegundoParcial.Login = Login;
})(SegundoParcial || (SegundoParcial = {}));
//# sourceMappingURL=login.js.map