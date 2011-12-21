var Registros = new function() {

	return {

		visualizar : function() {
			$('#dados').dataTable({
				"oLanguage": {
					"oPaginate": {
						"sFirst": "Primeira",
						"sNext": "Proxima",
						"sPrevious": "Anterior",
						"sLast": "Ultima"
					},
					"sLengthMenu": "Visualizar _MENU_ registros por página",
					"sZeroRecords": "Registro não encontrado",
					"sInfo": "Visualizando _START_ até _END_ de _TOTAL_ registros",
					"sInfoEmpty": "Sem registros para visualizar",
					"sInfoFiltered": "(Filtrado de _MAX_ total de registros)",
					"sSearch": "Buscar"
				},
				"bJQueryUI": true,
				"sPaginationType": "full_numbers"
			}); 
		},

		excluir : function(event) {
			var excluir = confirm("Deseja realmente excluir este registro?");

			if( ! excluir ) event.preventDefault();
		},

		interesse : function(event) {

			var interesse = confirm("Deseja cadastrar o interesse de imovel para o cliente agora?");

			if(! interesse ) event.preventDefault();
		}

	};
};

//------------------------------------------------------------------------------

var Upload = new function() {
	return {

		file : function() {
			$(document).ready(function() {
				$('#file_upload').uploadify({
					'uploader'  : '/uploadify/uploadify.swf',
					'script'    : '/uploadify/uploadify.php',
					'cancelImg' : '/uploadify/cancel.png',
					'folder'    : '/uploadsFotos',
					'auto'      : true
				});
			});

		}
	}
}

//------------------------------------------------------------------------------

var Cep = new function() {

	return {

		mascara : function() {
			$('#cep').mask('99999-999');
		}
	};
};

//------------------------------------------------------------------------------

var Digitos = new function() {

	return {

		dois : function() {
			$('.dois_digitos').mask('99');
		},

		quatro : function() {
			$('.quatro_digitos').mask('9999');
		},

		seis : function() {
			$('.seis_digitos').mask('999999');
		}	

	};
};

//------------------------------------------------------------------------------

var Cpf = new function() {

	return {

		mascara : function() {
			$('#cpf').mask('999.999.999-99');
		}
	};

};

//------------------------------------------------------------------------------

var Accordion = new function() {

	return {

		mascara : function() {
			$( "#accordion" ).accordion({ autoHeight: false });
		}
	};
};

//------------------------------------------------------------------------------

var Cnpj = new function() {

	return {

		mascara : function() {
			$('#cnpj').mask('99.999.999/9999-99');
		}

	};

};

//------------------------------------------------------------------------------

var Telefone = new function() {

	return {

		mascara : function() {
			$('input[name*="telefone"]').mask('(99) 9999-9999');
		}
	};

};

//------------------------------------------------------------------------------

var Data = new function() {

	return {

		mascara : function() {
			$('input[name*="data"]').mask('99/99/9999');
		}
	};

};

//------------------------------------------------------------------------------

var Login = new function() {

	return {

		autenticar : function() {

			$('#login').validate({
				rules: {
					email: {
						required: true,
						email: true
					},
					senha: {
						required: true
					}
				},
				messages:{
					email: {
						required: "Insira seu Login para entrar no sistema.",
						email: "O Login informado não é válido"
					},
					senha: {
						required: "Insira sua senha para logar no sistema."
					}
				}
			});
		}
	};

};

//------------------------------------------------------------------------------

var Slides = new function() {

	return {

		fotos : function() {
			$(function(){
				$('#slides').slides({
					preload: true,
					preloadImage: '/js/slides/loading.gif',
					play: 5000,
					pause: 2500,
					slideSpeed: 600,
					hoverPause: true
				});
			});
		}

	}
}

//------------------------------------------------------------------------------

var Nova = new function() {

	return {
		janela : function(url){
			window.open(url, '_blank', 'width=1024, height=635, scrollbars=yes');
			return false;
		}
	}
}

//------------------------------------------------------------------------------

var Moeda = new function(){
	
	return {
		
		validar : function() {
			$(".valor_dinheiro").maskMoney({
				showSymbol:true,
				symbol:"R$",
				decimal:",",
				thousands:".",
				allowZero:true
			});
		}
	}
}

//------------------------------------------------------------------------------











