namespace PrimerParcial
{
    // , Iparte3
    export class Manejadora implements Iparte2
    {
        public static AgregarProductoJSON()
        {
            let nombre = (<HTMLInputElement> document.getElementById("nombre")).value;
            let origen = (<HTMLInputElement> document.getElementById("cboOrigen")).value;

            let form = new FormData();
            form.append("nombre", nombre);
            form.append("origen", origen);

            let ajax = new Ajax();
            ajax.Post("./Backend/AltaProductoJSON.php",
                (param : any)=>{
                    alert(param);
                    console.log(param);
                }, form)
        }

        public static MostrarProductosJSON()
        {
            let ajax = new Ajax();
            ajax.Get("./Backend/ListadoProductosJSON.php", 
            (param : any)=>{
                if(param == "No se recibio GET")
                {
                    alert(param);
                    console.log(param);
                }
                else{
                    let tabla ="<table width=100% border = '2'><tr><td>Nombre</td><td>origen</td></tr>";
                    let lista = JSON.parse(param);

                    for (let i=0; i<lista.length;i++) {
                        tabla+='<tr><td>';
                        tabla+=lista[i].nombre;
                        tabla+='</td><td>';
                        tabla+=lista[i].origen;
                        tabla+='</td></tr>';
                    }
                    (<HTMLDivElement> document.getElementById("divTabla")).innerHTML = tabla;
                }
            });
        }

        public static VerificarProductoJSON()
        {
            let nombre = (<HTMLInputElement> document.getElementById("nombre")).value;
            let origen = (<HTMLInputElement> document.getElementById("cboOrigen")).value;

            let form = new FormData();
            form.append("nombre", nombre);
            form.append("origen", origen);

            let ajax = new Ajax();
            ajax.Post("./Backend/VerificarProductoJSON.php",
                (param : any)=>{
                    param = JSON.parse(param);
                    alert(param.mensaje);
                    console.log(param.mensaje);
                }, form)
        }

        public static MostrarInfoCookie()
        {
            let nombre = (<HTMLInputElement> document.getElementById("nombre")).value;
            let origen = (<HTMLInputElement> document.getElementById("cboOrigen")).value;

            let ajax = new Ajax();
            ajax.Get("./Backend/MostrarCookie.php",
                (param : any)=>{
                    param = JSON.parse(param);
                    alert(param.mensaje);
                    console.log(param.mensaje);
                }, `nombre=${nombre}&origen=${origen}`);
        }

        public static AgregarProductoSinFoto()
        {
            let nombre:string=(<HTMLInputElement> document.getElementById("nombre")).value;
            let origen:string=(<HTMLInputElement> document.getElementById("cboOrigen")).value;
            let codigoBarra:string=(<HTMLInputElement> document.getElementById("codigoBarra")).value;
            let precio:string=(<HTMLInputElement> document.getElementById("precio")).value;
            
            let productoObj=new Entidades.ProductoEnvasado(nombre,origen,0,parseInt(codigoBarra) ,parseInt(precio) ,"");//lo hago tipo json

            let producto= productoObj.ToJSON();

            let form=new FormData();

            form.append("producto_json",producto);
            let ajax =new Ajax();
            ajax.Post("./backend/AgregarProductoSinFoto.php",
            (param:any)=>{
                //(<HTMLInputElement> document.getElementById("id")).value = "";
                        (<HTMLInputElement> document.getElementById("nombre")).value = "";
                        (<HTMLInputElement> document.getElementById("precio")).value = "";
                (<HTMLInputElement> document.getElementById("codigoBarra")).value = "";
                (<HTMLInputElement> document.getElementById("cboOrigen")).value = "1";
                alert(JSON.parse(param).mensaje);
                console.log(param);
            }
            ,form)
    
        }

        public static MostrarProductosEnvasados()
    {
        let ajax = new Ajax();

        ajax.Get("./BACKEND/ListadoProductosEnvasados.php", 
        (param : any)=>{
            let producto_json = JSON.parse(param);

            let tabla = "<table border=2>";
            tabla += "<thead>";
            tabla += "<tr>";
            tabla += "<td>Id</td>";
            tabla += "<td>Nombre</td>";
            tabla += "<td>origen</td>";
            tabla += "<td>codigoBarra</td>";
            tabla += "<td>Precio</td>";
            tabla += "<td>Foto</td>"
            tabla += "<td>Acciones</td>";
            tabla += "</tr>";
            tabla += "</thead>";
            
            tabla += "<tbody>";

            for (let i=0; i<producto_json.length;i++) {
                let producto = new Entidades.ProductoEnvasado(producto_json[i].nombre, producto_json[i].origen,producto_json[i].id, producto_json[i].codigoBarra, producto_json[i].precio, producto_json[i].pathFoto);
                tabla+='<tr><td>';
                tabla+=producto.id;
                tabla+='</td><td>';
                tabla+=producto.nombre;
                tabla+='</td><td>';
                tabla+=producto.origen;
                tabla+='</td><td>';
                tabla+=producto.codigoBarra;
                tabla+='</td><td>';
                tabla+=producto.precio;
                tabla+='</td><td>';
                tabla+="<img src='"+producto.pathFoto+"' width=50 height=50 />";
                tabla+='</td>';
                tabla+="<td><input type='button' value='Borrar' onclick='PrimerParcial.Manejadora.EliminarProducto("+producto.ToJSON()+")' />";
                tabla+="<input type='button' value='Modificar' onclick='PrimerParcial.Manejadora.ModificarProducto("+producto.ToJSON()+")' /></td>";
                tabla+='</td></tr>';
            }
            
            tabla += "</tbody>"
            tabla += "</table>";

            (<HTMLDivElement> document.getElementById("divTabla")).innerHTML = tabla;
        }, "tabla=json")
    }

         ModificarProducto(producto_json : any) : void
        {
            (<HTMLInputElement> document.getElementById("idProducto")).value = producto_json.id;
            (<HTMLInputElement> document.getElementById("nombre")).value = producto_json.nombre;
            (<HTMLInputElement> document.getElementById("precio")).value = producto_json.precio;
            (<HTMLInputElement> document.getElementById("codigoBarra")).value = producto_json.codigoBarra;
            (<HTMLInputElement> document.getElementById("cboOrigen")).value = producto_json.origen;
        }

         EliminarProducto(producto_json : any) : void
        {
            if(!confirm("Desea eliminar al prodcuto seleccionado? nombre: "+producto_json.nombre+" origen: "+producto_json.origen))
            return;

            let producto = JSON.stringify(producto_json);
            let ajax = new Ajax();
            let form = new FormData();
            form.append("producto_json", producto);
            ajax.Post("./Backend/EliminarProductoENvasado.php",
                (param : any)=>{
                    alert(JSON.parse(param).mensaje);
                    console.log(JSON.parse(param).mensaje);

                    if(JSON.parse(param).exito)
                    {
                        Manejadora.MostrarProductosEnvasados();
                    }
                }, form);
        }

        public static ModificarSinFoto()
        {
            let id = (<HTMLInputElement> document.getElementById("idProducto")).value;
            let nombre = (<HTMLInputElement> document.getElementById("nombre")).value;
            let origen = (<HTMLInputElement> document.getElementById("cboOrigen")).value;
            let codigoBarra = (<HTMLInputElement> document.getElementById("codigoBarra")).value;
            let precio = (<HTMLInputElement> document.getElementById("precio")).value;

            let form = new FormData();
            let producto = new Entidades.ProductoEnvasado(nombre, origen, parseInt(id), parseInt(codigoBarra), parseInt(precio));
            form.append("producto_json", producto.ToJSON());

            let ajax = new Ajax();
            ajax.Post("./Backend/ModificarProductoEnvasado.php", 
            (param : any)=>{
                let aux = JSON.parse(param);
                if(aux.exito == true)
                {
                    alert(aux.mensaje);
                    console.log(aux.mensaje);
                    Manejadora.MostrarProductosEnvasados();
                    (<HTMLInputElement> document.getElementById("idProducto")).value = "";
                    (<HTMLInputElement> document.getElementById("nombre")).value = "";
                    (<HTMLInputElement> document.getElementById("cboOrigen")).value = "Argentina";
                    (<HTMLInputElement> document.getElementById("codigoBarra")).value = "";
                    (<HTMLInputElement> document.getElementById("precio")).value = "";
                }
                else{
                    alert(aux.mensaje);
                    console.log(aux.mensaje);
                }
                
            }, form);
        }
    }
 
}

