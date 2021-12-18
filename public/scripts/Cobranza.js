$(document).on("ready", init);// Inciamos el jquery

var objinit = new init();

elementos = new Array();

function init(){

    $('#tblCobranza').dataTable({

        dom: 'Bfrtip',

        buttons: [

            'copyHtml5',

            'excelHtml5',

            'csvHtml5',

            'pdfHtml5'

        ]

    });


	ListadoCobranza();// Ni bien carga la pagina que cargue el metodo

	ObtenerTasa();

	$("#VerForm").hide();// Ocultamos el formulario

//	$("form#frmCobranza").submit(SaveOrUpdate);// Evento submit de jquery que llamamos al metodo SaveOrUpdate para poder registrar o modificar datos

	$("#btnNuevo").click(VerForm);// evento click de jquery que llamamos al metodo VerForm


	$("#btnBuscarContratoCob").click(AbrirModalContratoCob);

	$("#btnBuscarCuota").click(AbrirModalCuotaCob);

	$("#btnPagarCuota").click(AbrirModalPagarCob);

    $("#btnGenerarCobro").click(GenerarCobro);

    $('#txtIncremento').change(function() {
        ActualizarMontos();
    });
    $('#txtDescuento').change(function() {
		var monton = parseFloat($("#txtTotalPed").val());
		var incrementon = parseFloat($("#txtIncremento").val());
		var descuenton = parseFloat($("#txtDescuento").val());

		if (monton<0) {
			monton = monton * -1;
		}
		if (incrementon<0) {
			incrementon = incrementon * -1;
		}
		if (descuenton<0) {
			descuenton = descuenton * -1;
		}

		var totalapagarn = parseFloat(monton);
		totalapagarn = totalapagarn + incrementon;

		if (totalapagarn<descuenton) {
			swal({
			  title: "Mensaje al Operador",
			  text: "¡El monto del Descuento no Puede ser Mayor que el Monto de la Factura!",
			  type: "error",
			  showCancelButton: false,
			  confirmButtonClass: "btn-success",
			  confirmButtonText: "Continuar",
			  closeOnConfirm: false
			});
			descuenton = 0;
			$("#txtDescuento").val(descuenton.toFixed(2));
			$("#txtDescuento").focus();
		} else {
	        ActualizarMontos();
		}
    });


    $('#reciboAutomatico').click(function() {
    	$('#txtNroRecibo').val("");
    	$('#txtNroRecibo').attr('disabled',true);
    });
    $('#reciboManual').click(function() {
    	$('#txtNroRecibo').attr('disabled',false);
    	$('#txtNroRecibo').select();
    });

    $('#monedaBolivianos').click(function() {
		$('#totalPagar').attr('style','height:45px; color:black; background:white;')
		$('#totalPagarBs').attr('style','height:45px; color:black; background:yellow;')
	});
    $('#monedaDolares').click(function() {
		$('#totalPagar').attr('style','height:45px; color:black; background:yellow;')
		$('#totalPagarBs').attr('style','height:45px; color:black; background:white;')
	});

	$('#monedaDolares').click();

	$("#btnAbonarPago").click(function(e) {
		e.preventDefault();

		var indiceregistro = $('#indiceregistro').val();
        var existe = 0;
        var data = JSON.parse(objinit.consultar());
      	console.log (indiceregistro);

        for (var pos in data) {
        	console.log (data[pos][0]);
            if (data[pos][0]==indiceregistro) {
            	existe = 1;
            	npos = pos;
            }
        }
        	console.log (existe);
        if (existe==1) {
        	ntotalCuota = data[npos][4];
        	ntotalAbono = Number($('#totalAbono').val());
        	nSaldo = ntotalCuota - ntotalAbono
        	data[npos][5] = ntotalAbono;
        	data[npos][6] = nSaldo;
        	elementos[npos][5] = ntotalAbono;
        	elementos[npos][6] = nSaldo;
        	console.log(data[npos][5]);
        };


        $("table#tblDetalleCuota tbody").html("");

        for (var pos in data) {
            $("table#tblDetalleCuota").append(
            	"<tr><td>" + data[pos][0] + 
            	" <input class='form-control' type='hidden' name='txtIdCuota' id='txtIdCuota[]' value='" + data[pos][0] + 
            	"' /></td><td> " + data[pos][1] + "</td><td> " + (data[pos][2] == 'C' ? 'CONTRATO' : (data[pos][2] == 'M' ? 'MANTENIMIENTO ANUAL' : (data[pos][2] == 'R' ? 'RECARGO' : 'NO DETERMINADO'))) + "</td><td> " + data[pos][3] + "</td><td>" + data[pos][4].toFixed(2) + "</td><td>" + data[pos][5].toFixed(2) + "</td><td>" + data[pos][6].toFixed(2) + "</td><td>" + (data[pos][7]=='P' ? 'PENDIENTE' : (data[pos][7]=='V' ? 'VENCIDA' : 'NO DETERMINADO') ) + 
				" </td><td><button type='button' onclick='eliminarDetallePed(" + pos + ")' class='btn btn-danger'><i class='fa fa-remove' ></i> </button>  <button type='button' onclick='abonarPago(" + pos + ")' class='btn btn-success'><i class='fa fa-dollar' ></i> </button></td>");
        }
//        calcularIgvPed();
//        calcularSubTotalPed();
        calcularTotalPed();

	});


	$("#btnAgregarContratoCob").click(function(e) {
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

		$("#modalListadoContrato").modal("hide");

	    $("#btnBuscarCuota").show();
	});



	function SaveOrUpdate(e){

		e.preventDefault();// para que no se recargue la pagina

        $.post("./ajax/CobranzaAjax.php?op=SaveOrUpdate", $(this).serialize(), function(r){// llamamos la url por post. function(r). r-> llamada del callback

            swal("Mensaje del Sistema", r, "success");

            Limpiar();

            ListadoCobranza();

            OcultarForm();

        });

	};


	function Limpiar(){

		// Limpiamos las cajas de texto

		$("#txtIdCobranza").val("");

		$("#txtIdCobranza2").val("");

		$("#txtIdContrato").val("");

		$("#txtContrato").val("");

		$("#txtFechaContrato").val("");

		$("#txtCementerio").val("");

		$("#txtSector").val("");

		$("#txtLote").val("");

		$("#txtFila").val("");

		$("#txtColumna").val("");

	}

	function VerForm(){

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



function ListadoCobranza(){

        var tabla = $('#tblCobranza').dataTable(

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

            ],"ajax":

                {

                    url: './ajax/CobranzaAjax.php?op=list',

                    type : "get",

                    dataType : "json",

                    error: function(e){

                        console.log(e.responseText);

                    }

                },

            "bDestroy": true



        }).DataTable();

    };



	function eliminarCobranza(id){

		swal({
		  title: "¿Esta Seguro de eliminar la Cobranza seleccionada?",
	//	  text: "Your will not be able to recover this imaginary file!",
		  type: "warning",
		  showCancelButton: true,
		  confirmButtonClass: "btn-success",
		  confirmButtonText: "Si",
		  cancelButtonText: "No",
		  closeOnConfirm: true
		},
		function(){
			$.post("./ajax/CobranzaAjax.php?op=delete", {id : id}, function(e) {

				swal("Mensaje del Sistema", e, "success");

				ListadoCobranza();

            });
		});
/*
		bootbox.confirm("¿Esta Seguro de eliminar la Cobranza seleccionada?", function(result){ 

			if(result){// si el result es true

				$.post("./ajax/CobranzaAjax.php?op=delete", {id : id}, function(e) {

					swal("Mensaje del Sistema", e, "success");

					ListadoCobranza();

	            });

			}

		})
*/
	}


	function ifReceiptNumberExist(receiptNumber){

		//alert(receiptNumber);
		// swal({
		//   title: "El número de recibo ya existe",
		//   //text: "Your will not be able to recover this imaginary file!",
		//   type: "warning",
		//   showCancelButton: false,
		//   confirmButtonClass: "btn-success",
		//   confirmButtonText: "Aceptar",
		//   //cancelButtonText: "No",
		//   closeOnConfirm: true
		// },
		// function(){
		if(receiptNumber != ''){
			$.post("./ajax/CobranzaAjax.php?op=ifReceiptNumberExist", {receiptNumber : receiptNumber}, function(e) {
				//alert('El número de recibo ya existe');
				if(e == 1){
					swal("El número de recibo ya existe", receiptNumber, "warning");
					//document.getElementById('txtNroRecibo').focus();
					document.getElementById('txtNroRecibo').value = '';
					//document.getElementById('txtNroRecibo').attr("value", "");
				}else{

					
				}
				//ListadoCobranza();

            });
        }
		//});
/*
		bootbox.confirm("¿Esta Seguro de eliminar la Cobranza seleccionada?", function(result){ 

			if(result){// si el result es true

				$.post("./ajax/CobranzaAjax.php?op=delete", {id : id}, function(e) {

					swal("Mensaje del Sistema", e, "success");

					ListadoCobranza();

	            });

			}

		})
*/
	}

	function cargarDataCobranza(id, idcontrato,fechacobranza,nrocontrato,fechacontrato,adquiriente,cementerio,sector,lote,fila,columna,monto,tiporecibo,tasacambio) {

		$("#VerForm").show();// mostramos el formulario

		$("#btnNuevo").hide();// ocultamos el boton nuevo

		$("#VerListado").hide();


		$("#txtIdCobranza").val(id);

		$("#txtIdCobranza2").val(id);

		$("#txtIdContrato").val(idcontrato);

		$("#txtContrato").val(nrocontrato);

		$("#txtFechaContrato").val(fechacontrato);

		$("#txtCementerio").val(cementerio);

		$("#txtSector").val(sector);

		$("#txtLote").val(lote);

		$("#txtFila").val(fila);

		$("#txtColumna").val(columna);

		if (tiporecibo=='A') {
			$("#reciboAutomatico").val(true);
			$("#reciboManual").val(false);
		} else {
			$("#reciboManual").val(true);
			$("#reciboAutomatico").val(false);
		}

        suma = parseFloat(monto);

        $("#txtTotalPed").val(suma.toFixed(2));

//        tasa = parseFloat(tasacambio);

//        $("#txtTasa").val(tasa.toFixed(4));

		$("#txtContrato").attr('disabled',true);

		$("#btnBuscarContratoCob").attr('disabled',true);

		VerCuotasPagadas();
		$("#idbtncab").html('<label class="btn btn-success" onclick="actCabecera(\''+ id +'\');">Modificar cabecera</label>');
 	}

	function AbrirModalContratoCob(){

//	    $("#btnBuscarCuota").attr('disabled',true);

	$("#modalListadoContrato").modal("show");
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


	function ObtenerTasa() {

		$.post("./ajax/CobranzaAjax.php?op=verTasa", '{ }', function(r) {

            $("#txtTasa").val(r);

        });

	}


	function VerCuotasPagadas(){

//		$("#modalListadoCuota").modal("show");

		idcontrato = $("#txtIdContrato").val();
		idcobranza = $("#txtIdCobranza").val();

        $("table#tblDetalleCuota tbody").html("");

		$.post("./ajax/CobranzaAjax.php?op=verCuota", {idcobranza : idcobranza, idcontrato : idcontrato}, function(r) {

            $("#VerCuotas").html(r);

            $('#tblDetalleCuota').DataTable();

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

	function ActualizaMontoaPagar(incremento,descuento,totalapagar) {

		var incrementon = parseFloat(incremento);
		var descuenton = parseFloat(descuento);
		var totalapagarn = parseFloat(totalapagar);
		if (incrementon<0) {
			incrementon = incrementon * -1;
		}
		if (descuenton<0) {
			descuenton = descuenton * -1;
		}

		$("#txtIncremento").val(incrementon.toFixed(2));
		$("#txtDescuento").val(descuenton.toFixed(2));
//		if (incrementon>0) {
			totalapagarn = totalapagarn + incrementon - descuenton;
			$("#totalPagar").val( totalapagarn.toFixed(2) );
//		};

	 	
	}


	function ActualizarMontos() {

		var tasacambion = parseFloat($("#txtTasa").val());
		var monton = parseFloat($("#txtTotalPed").val());
		var incrementon = parseFloat($("#txtIncremento").val());
		var descuenton = parseFloat($("#txtDescuento").val());

		if (tasacambion<0) {
			tasacambion = tasacambion * -1;
		}
		if (monton<0) {
			monton = monton * -1;
		}
		if (incrementon<0) {
			incrementon = incrementon * -1;
		}
		if (descuenton<0) {
			descuenton = descuenton * -1;
		}

		var totalapagarn = parseFloat(monton);

		$("#txtIncremento").val(incrementon.toFixed(2));
		$("#txtDescuento").val(descuenton.toFixed(2));

		totalapagarn = totalapagarn + incrementon - descuenton;
		monton = totalapagarn;

		$("#totalPagar").val( totalapagarn.toFixed(2) );
        $("#tasaCambio").val(tasacambion.toFixed(4));

        montobs = monton * tasacambion;

        $("#totalPagarBs").val( montobs.toFixed(2) );

//		$("#totalPagar").val(monton.toFixed(2));

	}

	function AbrirModalPagarCob(){
		if ($("#txtFechaCobranza").val().length==0) {
		    $("#txtFechaCobranza").val(GetTodayDate());
		}

		ActualizarMontos();

		$("#modalListadoPagar").modal("show");
	}




    function AgregarPedCarritoCobranza(idcuota, nrocuota, tipocuota, fechalimite, monto, acuenta, saldo, estado){
        var existe = 0;
        var data = JSON.parse(objinit.consultar());
        for (var pos in data) {
            if (data[pos][0]==idcuota) {
            	existe = 1;
            }
        }
        if (existe==0) {
        	if (acuenta > 0) {
        		console.log ('acuenta > 0');
		        var detalles = new Array(idcuota, nrocuota, tipocuota, fechalimite, saldo, 0, saldo, estado);
        	} else {
        		console.log ('acuenta = 0');
		        var detalles = new Array(idcuota, nrocuota, tipocuota, fechalimite, monto, acuenta, saldo, estado);
        	}
	        elementos.push(detalles);
	        ConsultarDetallesPed();
    	}
	    $("#btnPagarCuota").show();
    }

    function VerDetalle(idcuota, nrocuota, tipocuota, fechalimite, monto, acuenta, saldo, estado){
        var existe = 0;
        var data = JSON.parse(objinit.consultar());
        for (var pos in data) {
            if (data[pos][0]==idcuota) {
            	existe = 1;
            }
        }
        if (existe==0) {
	        var detalles = new Array(idcuota, nrocuota, tipocuota, fechalimite, monto, acuenta, saldo, estado);
	        elementos.push(detalles);
	        ConsultarDetallesPed();
    	}
	    $("#btnPagarCuota").show();
    }

	function ConsultarDetallesPed() {
        $("table#tblDetalleCuota tbody").html("");
        var data = JSON.parse(objinit.consultar());

        for (var pos in data) {
            $("table#tblDetalleCuota").append(
            	"<tr><td>" + data[pos][0] + 
            	" <input class='form-control' type='hidden' name='txtIdCuota' id='txtIdCuota[]' value='" + data[pos][0] + 
            	"' /></td><td> " + data[pos][1] + "</td><td> " + (data[pos][2] == 'C' ? 'CONTRATO' : (data[pos][2] == 'M' ? 'MANTENIMIENTO ANUAL' : (data[pos][2] == 'R' ? 'RECARGO' : 'NO DETERMINADO'))) + "</td><td> " + data[pos][3] + "</td><td>" + data[pos][4].toFixed(2) + "</td><td>" + data[pos][5].toFixed(2) + "</td><td>" + data[pos][6].toFixed(2) + "</td><td>" + (data[pos][7]=='P' ? 'PENDIENTE' : (data[pos][7]=='V' ? 'VENCIDA' : 'NO DETERMINADO') ) + 
				" </td><td><button type='button' onclick='eliminarDetallePed(" + pos + ")' class='btn btn-danger'><i class='fa fa-remove' ></i> </button>  <button type='button' onclick='abonarPago(" + pos + ")' class='btn btn-success'><i class='fa fa-dollar' ></i> </button></td>");
        }
//        calcularIgvPed();
//        calcularSubTotalPed();
        calcularTotalPed();
    }


	function eliminarDetallePed(ele){
        console.log(ele);
        objinit.eliminar(ele);
        ConsultarDetallesPed();
    }

	function abonarPago(ele) {
        var data = JSON.parse(objinit.consultar());

        nindice = data[ele][0];
		console.log('indice del registro '  + nindice);
		$('#indiceregistro').val(nindice);
		$("#modalAbonarPago").modal("show");
	}

    function calcularTotalPed(posi){
        if(posi != null){
          ModificarPed(posi);
        }
        var suma = 0;
        var data = JSON.parse(objinit.consultar());
        for (var pos in data) {
            suma += parseFloat(data[pos][5]);
        }
        $("#txtTotalPed").val(suma.toFixed(2));
//        $("#txtTotalPed").val(Math.round(suma.toFixed(2)*100)/100);

    }


function verReciboPago(url) {
	var a = document.createElement("a");
	a.target = "_blank";
	a.href = url;
	a.click();
}

function reciboPago(id,nrocontrato){
	//$.post("./ajax/CobranzaAjax.php?op=crearReciboPago", {id : id});

	swal({
	  title: "¿Esta Seguro de ver el Recibo de Pago?",
//	  text: "Your will not be able to recover this imaginary file!",
	  type: "warning",
	  showCancelButton: true,
	  confirmButtonClass: "btn-success",
	  confirmButtonText: "Si",
	  cancelButtonText: "No",
	  closeOnConfirm: true
	},
	function(){
		//verReciboPago('Files/Pdf/Recibo de Pago. Cobranza ' + id + '.pdf');
		window.open("./Reportes/ReciboPagoPdf.php?id="+id);
	});

/*
	bootbox.confirm("¿Esta Seguro de ver el Recibo de Pago?" , function(result){ 
		if (result) {
			verReciboPago('Files/Pdf/Recibo de Pago. Cobranza ' + id + '.pdf');
		}
	})
*/
}



    function GenerarCobro(e){

		e.preventDefault();

	    if ( $('input[name=radTipoRecibo]:checked').val() == "M" ) {
		    if (elementos.length > 0 & $("#txtNroRecibo").val() == "" ) {
		        $("#txtNroRecibo").select();
		        return;
		    }
		}
		incremento = Number($("#txtIncremento").val());

	    if (elementos.length > 0 & ( incremento > 0 & $("#txtObjetoIncremento").val() == "") ) {
	        $("#txtObjetoIncremento").select();
	        return;
	    }
	    if (elementos.length > 0 & ( incremento == 0 & $("#txtObjetoIncremento").val() != "") ) {
	        $("#txtIncremento").select();
	        return;
	    }
	    if (elementos.length > 0 & $("#txtConcepto").val() == "" ) {
	        $("#txtConcepto").select();
	        return;
	    }
	    if (elementos.length > 0 ) {
	    	var detalle =  JSON.parse(objinit.consultar());

	        var data = {
	        	idcontrato : $("#txtIdContrato").val(),
	        	nrorecibo : $("#txtNroRecibo").val(),
	        	tipopago : $("#cboTipoPago").val(),
	        	incremento : $("#txtIncremento").val(),
	        	objetoincremento : $("#txtObjetoIncremento").val(),
	        	concepto : $("#txtConcepto").val(),
	        	observaciones : $("#txtObservaciones").val(),
	        	nombre : $("#txtNombre").val(),
	        	fechacobranza : $("#txtFechaCobranza").val(),
               	detalle : detalle,
               	descuento : $("#txtDescuento").val(),
               	monto : $("#totalPagar").val(),
               	tiporecibo : $('input[name=radTipoRecibo]:checked').val(),
               	emiterecibo : $('input[name=radEmiteRecibo]:checked').val(),
	        	montobs : $("#totalPagarBs").val(),
	        	tasacambio : $("#tasaCambio").val(),
	        	usuario : $("#usuario").val(),
			};
//			console.log (data);

			$.post("./ajax/CobranzaAjax.php?op=SaveFactura", data, function(r) {
//				location.href ="../solventas/Pedido.php";
//				var es = String(r);
//				window.open('./Reportes/exVenta.php?id='+es, 'target', ' toolbar=0 , location=1 , status=0 , menubar=1 , scrollbars=0 , resizable=1 ,left=600pt,top=90pt, width=380px,height=880px');
		        bootbox.alert(r);

				$("#modalListadoPagar").modal("hide");

				$("#VerForm").hide();// Mostramos el formulario

				$("#btnNuevo").show();// ocultamos el boton nuevo

				$("#VerListado").show();

		        $("table#tblDetalleCuota tbody").html("");

				ListadoCobranza();

	         });
	      } else {
	        bootbox.alert(" Debe indicar las Cuotas a Pagar ...");
	     }


}// fimn funcion generar venta

function actualizarAbono(id,idCuota,Saldo,AbonoAnt){
	var data = {
		id: id,
		Abono: $("#"+id).val(),
		idCuota: idCuota,
		Saldo:Saldo,
		AbonoAnt:AbonoAnt
	};
	$.post("./ajax/CobranzaAjax.php?op=actAbono", data, function(r) {
		bootbox.alert(r);
	});
}
function EliminarAbono(id){
	var data = {
		id: id
	};
	$.post("./ajax/CobranzaAjax.php?op=actAbono", data, function(r) {
		bootbox.alert(r);
	});
}
function actCabecera(id){
	$("#btnGenerarCobro").hide();
	var json = {id:id};
	$.post("./ajax/CobranzaAjax.php?op=getCabecera", json, function(r) {
		var objeto = JSON.parse(r);
		$("#tasaCambio").attr("readonly", false);
		$("#totalPagarBs").attr("readonly", false);
		$("#totalPagar").attr("readonly", false);
		$("#txtIdCobranza2").val(id);
		$.each(objeto, function(i, item) {
			$("#txtNroRecibo").val(item.nrorecibo);
			$("#cboTipoPago").val(item.tipopago);
			$("#txtFechaCobranza").val(item.fechacobranza);
			$("#txtIncremento").val(item.incremento);
			$("#txtObjetoIncremento").val(item.objetoincremento);
			$("#txtConcepto").val(item.concepto);
			$("#txtNombre").val(item.nombre);
			$("#txtObservaciones").val(item.observaciones);
			$("#txtDescuento").val(item.descuento);
			$("#monedaDolares").prop('checked', true);
			if(item.emiterecibo === "B"){
				$("#monedaBolivianos").prop('checked', true);
			}
			$("#reciboAutomatico").prop('checked', true);
			if(item.tiporecibo === "M"){
				$("#reciboManual").prop('checked', true);
			}
			$("#tasaCambio").val(item.tasacambio);
			$("#totalPagarBs").val(item.montobs);
			$("#totalPagar").val(item.monto);
			//$("#btnActCobro").val(item.nrorecibo);
		});
		$("#modalListadoPagar").modal("show");
	});
}
function modificaCabPago(){
	var Moneda = "D";
	if($("#monedaBolivianos").is(':checked')){
		Moneda = "B";
	}
	var json = {
		id:$("#txtIdCobranza2").val(),
		NroRecibo:$("#txtNroRecibo").val(),
		TPago:$("#cboTipoPago").val(),
		FecCob:$("#txtFechaCobranza").val(),
		Incremento:$("#txtIncremento").val(),
		ObjInc:$("#txtObjetoIncremento").val(),
		Concepto:$("#txtConcepto").val(),
		Nombre:$("#txtNombre").val(),
		Obs:$("#txtObservaciones").val(),
		Desc:$("#txtDescuento").val(),
		Moneda:Moneda,
		tasaCamb:$("#tasaCambio").val(),
		TPagaBs:$("#totalPagarBs").val(),
		TPagar:$("#totalPagar").val()
	};
	if($("#txtIdCobranza2").val() !== "0" && $("#txtIdCobranza2").val() !== ""){
		$.post("./ajax/CobranzaAjax.php?op=ActCabecera", json, function(r) {
			if(r.length === 3){
				r = "Registro actualizado correctamente";
				$("#txtIdCobranza2").val("0");
				$("#txtNroRecibo").val("");
				$("#txtIncremento").val("");
				$("#txtObjetoIncremento").val("");
				$("#txtConcepto").val("");
				$("#txtNombre").val("");
				$("#txtObservaciones").val("");
				$("#txtDescuento").val("");
				$("#tasaCambio").val("");
				$("#totalPagarBs").val("");
				$("#totalPagar").val("");
				$("#btnGenerarCobro").show();
				$("#btnActCobro").hide();
				$("#modalListadoPagar").modal("hide");
			}
			bootbox.alert(r);
		});
	}else{
		alert("No se pudo determinar el ID para este registro");
	}
}
