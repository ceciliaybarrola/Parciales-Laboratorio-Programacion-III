namespace Entidades
{
    export class Producto
    {
        nombre : string;
        origen : string;

        public constructor (nombre : string, origen : string)
        {
            this.nombre = nombre;
            this.origen = origen;
        }

        public ToString()
        {
            return `"nombre" : "${this.nombre}", "origen" : "${this.origen}"`;
        }

        public ToJSON()
        {
            return `{${this.ToString()}}`;
        }
    }
}