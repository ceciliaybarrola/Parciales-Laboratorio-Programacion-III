/// <reference path="../node_modules/@types/jquery/index.d.ts" />
const APIREST:string = "http://api_slim4/";
namespace SegundoParcial{

    export class Registro
    {
        public static AltaUsuario()
        {
            let correo = <string> $("#regcorreo").val();
            let clave = <string> $("#regpassword").val();
            let nombre = <string> $("#regnombre").val();
            let apellido = <string> $("#regapellido").val();
            let perfil = <string> $("#regperfil").val();
            let foto = (<HTMLInputElement>document.getElementById("regfoto")).files;
            let fotoReal;
            if(foto!=undefined && foto.length > 0)
            {
                fotoReal = foto[0];
            }
            if(fotoReal == undefined){
               fotoReal = "";
            }
            
            let dato:any = {}; 
            dato.correo = correo;
            dato.clave = clave;
            dato.nombre = nombre;
            dato.apellido = apellido;
            dato.perfil = perfil;

            let form = new FormData();
            form.append("usuario", JSON.stringify(dato));
            form.append("foto", fotoReal);

            $.ajax({
                type: 'POST',
                url: APIREST+"usuarios",
                dataType: "json",
                contentType: false,
                processData: false,
                data: form,
                async: true
            }
            )
            .done(function (resultado:any){
                console.log(resultado);
                
                if(resultado.exito)
                {
                    window.location.replace(APIREST+"front-end-login");
                }

            })
            .fail(function (jqXHR:any, textStatus:any, errorThrown:any){
                let resultado = JSON.parse(jqXHR.responseText);
                console.log(resultado);
                
                if(!resultado.exito)
                {
                    $("#errorReg").removeClass("hide");
                    $("#errorReg").html(resultado.mensaje);
                }
                
            });
        }

        
    }
}