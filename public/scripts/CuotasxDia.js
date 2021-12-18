$(document).on("ready", init);// Inciamos el jquery

function init() {


//    $("#tabplan").hide(500); Ocultar un Tab

    $('#tblCuotaMora').dataTable({

        dom: 'Bfrtip',

        buttons: [

            'copyHtml5',

            'excelHtml5',

            'csvHtml5',

            'pdfHtml5'

        ]

    });

    var date = new Date(); // Or your date here
    mes = (date.getMonth() + 1);
    mes = mes<10 ? '0' + mes: mes;
    filtrohoy =  (date.getFullYear() + '-' + mes + '-' + date.getDate());
    console.log('principal ' + filtrohoy);

    $('#fecha').val(filtrohoy);

	ListadoCuotaMora();// Ni bien carga la pagina que cargue el metodo

    $("#btnBuscar").click(ListadoCuotaMora);

}

function BuscarRegistros() {
    ListadoCuotaMora();
}


function ListadoCuotaMora(){

    var filtro = $('#fecha').val();

        var tabla = $('#tblCuotaMora').dataTable(

        {   "aProcessing": true,

            "aServerSide": true,

            dom: 'Bfrtip',

            buttons: [

                'copyHtml5',

                'excelHtml5',

                'csvHtml5',

                'pdfHtml5'

            ],

            "aoColumns":[

                    {   "mDataProp": "0"},

                    {   "mDataProp": "1"},

                    {   "mDataProp": "2"},

                    {   "mDataProp": "3"},

                    {   "mDataProp": "4"},

                    {   "mDataProp": "5"},

                    {   "mDataProp": "6"},

                    {   "mDataProp": "7"},

                    {   "mDataProp": "8"},

                    {   "mDataProp": "9"}

            ],"ajax":

                {

                    url: './ajax/CuotasxDiaAjax.php?fecha="' + filtro +'"',

                    type : "get",

                    dataType : "json",

                    error: function(e){

                        console.log(e.responseText);

                    }

                },

            "bDestroy": true



        }).DataTable();

    };






