/// <reference path="../node_modules/@types/jquery/index.d.ts" />
const APIREST:string = "http://api_slim4/";
namespace SegundoParcial
{
    export class Login
    {
        
        public static Login()
        {
            let correo = <string> $("#email").val();
            let clave  = <string> $("#password").val();
            let dato:any = {};
            dato.correo = correo;
            dato.clave = clave;
            

            $.ajax({
                type: 'POST',
                url: APIREST+"login/",
                dataType: "json",
                data: {"user":JSON.stringify(dato)},
                async: true
            }
            )
            .done(function (resultado:any){
                console.log(resultado);
                
                if(resultado.exito)
                {
                    localStorage.setItem("user", resultado.usuario);
                    window.location.replace(APIREST+"front-end-principal");
                }
            })
            .fail(function (jqXHR:any, textStatus:any, errorThrown:any){
                let resultado = JSON.parse(jqXHR.responseText);
                console.log(resultado);
                $("#errorLogin").removeClass("hide");

                if(resultado.status == 409)
                {
                    $("#errorLogin").html("Error! " + resultado.mensaje);
                }
                else{
                    $("#errorLogin").html("Error! No existe el usuario");
                }
            });
        }

        public static IrRegistro()
        {
            window.location.replace(APIREST+"front-end-registro");
        }
    }
}