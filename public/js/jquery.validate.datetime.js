/**
 * Novo método para o JQuery Validate, validando data e hora.
 * 
 * Essa biblioteca necessita da biblioteca datejs
 * @see 	http://code.google.com/p/datejs/
 * 
 * @name	jquery.validate.datetime
 */
jQuery.validator.addMethod("datetime", function(datetime, element) {
	var domingo = 0;
	var sabado  = 6;
	
	var dataDigitada = Date.parse(datetime);
	
	//Não é uma data válida
	if(dataDigitada == null) 
		return false;
	
	//Obtem a data minima permitida
	var dataMinima = Date.today().setTimeToNow();
	 
	//A data digitada não pode ser antes da data e hora atual
	if(dataDigitada.isBefore(dataMinima)) 
		return false;
	
	//A data é válida e está dentro do período permitido
	return true;
}, 
//Mensagem de retorno do validador
function(){
	var dataMinima = Date.today().setTimeToNow();
	
	var mensagem = 'A data não pode ser antes de ' 
		+ dataMinima.toString('dd/MM/yyyy HH:mm');
	
	return mensagem;
});