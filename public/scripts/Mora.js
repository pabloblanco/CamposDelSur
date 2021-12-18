$(document).on("ready", init);// Inciamos el jquery

function init() {


//    $("#tabplan").hide(500); Ocultar un Tab

    $('#tblContratoMora').dataTable({

        dom: 'Bfrtip',

        buttons: [

            'copyHtml5',

            'excelHtml5',

            'csvHtml5',

            'pdfHtml5'

        ]

    });

    actualizarVencidas();

	ListadoContratoMora();// Ni bien carga la pagina que cargue el metodo

    $("#tabplan").click(CargarPlandePagos);



	function VerMensaje() {
		 swal("Mensaje del Sistema", "Holla", "success");
	}


}



function ListadoContratoMora(){

        var tabla = $('#tblContratoMora').dataTable(

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

                    {   "mDataProp": "9"},

                    {   "mDataProp": "10"}                    

            ],"ajax":

                {

                    url: './ajax/MoraAjax.php?op=list',

                    type : "get",

                    dataType : "json",

                    error: function(e){

                        console.log(e.responseText);

                    }

                },

            "bDestroy": true



        }).DataTable();

    };

function ListadoPlandePagos($idcontrato){

        var tabla = $('#tblPlandePago').dataTable(

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

                    {   "mDataProp": "3"}

            ],"ajax":

                {

                    url: './ajax/ContratoAjax.php?op=listPlandePagos?idcontrato=' + $idcontrato,

                    type : "get",

                    dataType : "json",

                    error: function(e){

                        console.log(e.responseText);

                    }

                },

            "bDestroy": true

        }).DataTable();

    };



function verKardex(idcontrato, nrocontrato) {

    $.post("./ajax/KardexAjax.php?op=creaReporteKardex", {idcontrato : idcontrato} );

	swal({
	  title: "¿Esta Seguro de ver el Kardex del Contrato?",
//	  text: "Your will not be able to recover this imaginary file!",
	  type: "warning",
	  showCancelButton: true,
	  confirmButtonClass: "btn-success",
	  confirmButtonText: "Si",
	  cancelButtonText: "No",
	  closeOnConfirm: true
	},
	function(){

		verReporteKardex('Files/Pdf/Kardex. Contrato Nro. ' + nrocontrato + '.pdf');
	});
}

function actualizarVencidas() {

    console.log('Entre Actualizar vencidas...');

    $.post("./ajax/MoraAjax.php?op=actualizarVencidas");

    console.log('Sali Actualizar vencidas...');

}


function verReporteKardex(url) {
	var a = document.createElement("a");
	a.target = "_blank";
	a.href = url;
	a.click();
}




