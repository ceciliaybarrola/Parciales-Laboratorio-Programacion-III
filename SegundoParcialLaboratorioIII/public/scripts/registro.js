"use strict";
/// <reference path="../node_modules/@types/jquery/index.d.ts" />
var APIREST = "http://api_slim4/";
var SegundoParcial;
(function (SegundoParcial) {
    var Registro = /** @class */ (function () {
        function Registro() {
        }
        Registro.AltaUsuario = function () {
            var correo = $("#regcorreo").val();
            var clave = $("#regpassword").val();
            var nombre = $("#regnombre").val();
            var apellido = $("#regapellido").val();
            var perfil = $("#regperfil").val();
            var foto = document.getElementById("regfoto").files;
            var fotoReal;
            if (foto != undefined && foto.length > 0) {
                fotoReal = foto[0];
            }
            if (fotoReal == undefined) {
                fotoReal = "";
            }
            var dato = {};
            dato.correo = correo;
            dato.clave = clave;
            dato.nombre = nombre;
            dato.apellido = apellido;
            dato.perfil = perfil;
            var form = new FormData();
            form.append("usuario", JSON.stringify(dato));
            form.append("foto", fotoReal);
            $.ajax({
                type: 'POST',
                url: APIREST + "usuarios",
                dataType: "json",
                contentType: false,
                processData: false,
                data: form,
                async: true
            })
                .done(function (resultado) {
                console.log(resultado);
                if (resultado.exito) {
                    window.location.replace(APIREST + "front-end-login");
                }
            })
                .fail(function (jqXHR, textStatus, errorThrown) {
                var resultado = JSON.parse(jqXHR.responseText);
                console.log(resultado);
                if (!resultado.exito) {
                    $("#errorReg").removeClass("hide");
                    $("#errorReg").html(resultado.mensaje);
                }
            });
        };
        return Registro;
    }());
    SegundoParcial.Registro = Registro;
})(SegundoParcial || (SegundoParcial = {}));
//# sourceMappingURL=registro.js.map