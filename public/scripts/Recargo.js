$(document).on("ready", init);// Inciamos el jquery

var objinit = new init();

elementos = new Array();

function init(){

    $('#tblRecargo').dataTable({

        dom: 'Bfrtip',

        buttons: [

            'copyHtml5',

            'excelHtml5',

            'csvHtml5',

            'pdfHtml5'

        ] 

    });


	ListadoRecargo();// Ni bien carga la pagina que cargue el metodo

	$("#VerForm").hide();// Ocultamos el formulario

	$("form#frmRecargo").submit(SaveOrUpdate);// Evento submit de jquery que llamamos al metodo SaveOrUpdate para poder registrar o modificar datos

	$("#btnNuevo").click(VerFormRecargo);// evento click de jquery que llamamos al metodo VerFormRecargo


	$("#btnBuscarContratoRec").click(AbrirModalContratoRec);

	$("#btnAgregarContratoRec").click(function(e) {
		e.preventDefault();

		var opt = $("input[name=optContratoBusqueda]:checked");

  		$("#txtIdContrato").val(opt.val());

		$("#txtContrato").val(opt.attr("data-nombre"));

		$("#txtCementerio").val(opt.attr("data-cementerio"));

		$("#txtSector").val(opt.attr("data-sector"));

		$("#txtLote").val(opt.attr("data-lote"));

		$("#txtFila").val(opt.attr("data-fila"));

		$("#txtColumna").val(opt.attr("data-columna"));

		$("#txtFechaContrato").val(opt.attr("data-fechacontrato"));

		$("#modalListadoContratoRec").modal("hide");

		$("#txtConcepto").attr('disabled',false);

		$("#txtFechaLimite").attr('disabled',false);

		$("#txtMonto").attr('disabled',false);

		$("#btnRegistrarRecargo").attr('disabled',false);

		$("#txtConcepto").focus();

	});





	function SaveOrUpdate(e) {

		e.preventDefault();// para que no se recargue la pagina

        $.post("./ajax/RecargoAjax.php?op=SaveOrUpdate", $(this).serialize(), function(r){// llamamos la url por post. function(r). r-> llamada del callback

            swal("Mensaje del Sistema", r, "success");

            Limpiar();

            ListadoRecargo();

            OcultarForm();

        });

	};


	function Limpiar(){

		// Limpiamos las cajas de texto

		$("#txtIdCuota").val("");

		$("#txtIdCuota2").val("");

		$("#txtIdContrato").val("");

		$("#txtContrato").val("");

		$("#txtFechaContrato").val("");

		$("#txtCementerio").val("");

		$("#txtSector").val("");

		$("#txtLote").val("");

		$("#txtFila").val("");

		$("#txtColumna").val("");

	}

	function VerFormRecargo(){

		Limpiar();

		$("#VerForm").show();// Mostramos el formulario

		$("#btnNuevo").hide();// ocultamos el boton nuevo

		$("#VerListado").hide();

	    $('#reciboAutomatico').click();

	}



	function OcultarForm(){

		$("#VerForm").hide();// Mostramos el formulario

		$("#btnNuevo").show();// ocultamos el boton nuevo

		$("#VerListado").show();

	}


    function consultar() {
        return JSON.stringify(elementos);
    }

    this.consultar = function(){
        return JSON.stringify(elementos);
    };

    this.eliminar = function(pos){
        pos > -1 && elementos.splice(parseInt(pos),1);
        if (elementos.length==0) {
		    $("#btnPagarCuota").hide();
        }
    };

}



function ListadoRecargo(){

        var tabla = $('#tblRecargo').dataTable(

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

                    {   "mDataProp": "8"}
            ],"ajax":

                {

                    url: './ajax/RecargoAjax.php?op=list',

                    type : "get",

                    dataType : "json",

                    error: function(e){

                        console.log(e.responseText);

                    }

                },

            "bDestroy": true



        }).DataTable();

    };



	function eliminarRecargo(id){

		swal({
		  title: "¿Esta Seguro de eliminar el Recargo seleccionado?",
	//	  text: "Your will not be able to recover this imaginary file!",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonClass: "btn-success",
		  confirmButtonText: "Si",
		  cancelButtonText: "No",
		  closeOnConfirm: true
		},
		function(){
			$.post("./ajax/RecargoAjax.php?op=delete", {id : id}, function(e) {

				swal("Mensaje del Sistema", e, "success");

				ListadoRecargo();

            });
		});
/*
		bootbox.confirm("¿Esta Seguro de eliminar la Cobranza seleccionada?", function(result){ 

			if(result){// si el result es true

				$.post("./ajax/CobranzaAjax.php?op=delete", {id : id}, function(e) {

					swal("Mensaje del Sistema", e, "success");

					ListadoRecargo();

	            });

			}

		})
*/
	}


	function cargarDataRecargo(id, idcontrato,nrocontrato,fechacontrato,adquiriente,cementerio,sector,lote,fila,columna,monto,fechalimite,concepto) {

		$("#VerForm").show();// mostramos el formulario

		$("#btnNuevo").hide();// ocultamos el boton nuevo

		$("#VerListado").hide();

		$("#txtIdCuota").val(id);

		$("#txtIdCuota2").val(id);

		$("#txtIdContrato").val(idcontrato);

		$("#txtContrato").val(nrocontrato);

		$("#txtFechaContrato").val(fechacontrato);

		$("#txtCementerio").val(cementerio);

		$("#txtSector").val(sector);

		$("#txtLote").val(lote);

		$("#txtFila").val(fila);

		$("#txtColumna").val(columna);

		$("#txtMonto").val(monto);

		$("#txtFechaLimite").val(fechalimite);

		$("#txtConcepto").val(concepto);

		$("#txtContrato").attr('disabled',true);

		$("#btnBuscarContratoRec").attr('disabled',true);

		$("#txtConcepto").focus();

 	}

	function AbrirModalContratoRec(){

		$("#modalListadoContratoRec").modal("show");

	 var tabla = $('#tblContratos').dataTable(
            {   "aProcessing": true,
                "aServerSide": true,
                "iDisplayLength": 4,
                //"aLengthMenu": [0, 5],
                "aoColumns":[
                        {   "mDataProp": "0"},
                        {   "mDataProp": "1"},
                        {   "mDataProp": "2"},
                        {   "mDataProp": "3"},
                        {   "mDataProp": "4"},
                        {   "mDataProp": "5"}

                ],"ajax":
                    {
                        url: './ajax/CobranzaAjax.php?op=listContrato',
                        type : "get",
                        dataType : "json",

                        error: function(e){
                            console.log(e.responseText);
                        }
                    },
                "bDestroy": true

            }).DataTable();

	}

	function AbrirModalCuotaCob(){

		$("#modalListadoCuota").modal("show");

		idcontrato = $("#txtIdContrato").val();

		$.post("./ajax/CobranzaAjax.php?op=listCuota", {idcontrato : idcontrato}, function(r) {

            $("#CuotaDetalle").html(r);

            $('#tblCuotas').DataTable();

        });

	}


	function GetTodayDate() {
	   var tdate = new Date();
	   var dd = tdate.getDate(); //yields day
	   if (dd<10) {
	   	  dd = '0' + dd;
	   }
	   var MM = tdate.getMonth()+1; //yields month
	   if (MM<10) {
	   	  MM = '0' + MM;
	   }
	   var yyyy = tdate.getFullYear(); //yields year

	   var currentDate= yyyy + "-" + MM + "-" + dd;
	   console.log (currentDate);
//	   var currentDate=  dd + "-" + MM + "-" + yyyy;

	   return currentDate;
	}

	function fileExists(url) {
		var http = new XMLHttpRequest();
       	http.open('HEAD', url, false);
       	http.send();
       	return http.status!=404;
    }

	function verReciboRecargo(url) {
		existerecibo = fileExists(url);

		if (existerecibo) {
			var a = document.createElement("a");
			a.target = "_blank";
			a.href = url;
			a.click();
		}
		return (existerecibo);
	}

	function reciboRecargo(id,nrocontrato){
		valor = $.post("./ajax/RecargoAjax.php?op=crearReciboRecargo", {id : id});
		console.log(valor);

		swal({
		  title: "¿Esta Seguro de ver el Recibo del Recargo?",
	//	  text: "Your will not be able to recover this imaginary file!",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonClass: "btn-success",
		  confirmButtonText: "Si",
		  cancelButtonText: "No",
		  closeOnConfirm: true
		},
		function(){
			verReciboRecargo('Files/Pdf/Recibo de Recargo. ' + id + '.pdf');
		});
	}



