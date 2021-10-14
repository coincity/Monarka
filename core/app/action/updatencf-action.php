<?php
header('Content-type: application/json');

if(count($_POST)>0){

	$rr = NCFData::getRepeatedUpdate($_POST["ncf_id"],$_POST["tipo_doc"],$_POST["tipo"],DateTime::createFromFormat('d/m/Y', $_POST["fecinivig"])->format('Y-m-d'),DateTime::createFromFormat('d/m/Y', $_POST["fecfinvig"])->format('Y-m-d'));

	if($rr!=null){
		$response_array['status'] = 'fail';
		$response_array['message'] = 'Secuencia de Comprobante ya existente';
	}
	else if($_POST["secuenciaini"] > $_POST["secuenciafin"]) {
	  	$response_array['status'] = 'fail';
		$response_array['message'] = 'La Secuencia de Inicio no puede ser mayor a la Final';
	}
    else if($_POST["secuenciaini"] > $_POST["secuenciaactual"]) {
        $response_array['status'] = 'fail';
		$response_array['message'] = 'La Secuencia de Inicio no puede ser mayor a la Actual';
	}
	else if(DateTime::createFromFormat('d/m/Y', $_POST["fecinivig"])->format('Y-m-d') > DateTime::createFromFormat('d/m/Y', $_POST["fecfinvig"])->format('Y-m-d')) {
	  	$response_array['status'] = 'fail';
		$response_array['message'] = 'La Fecha Inicial no puede ser mayor a la Final';
	}
	else {
		$ncf = NcfData::getById($_POST["ncf_id"]);
		$ncf->tipodoc = $_POST["tipo_doc"];
		$ncf->tipo = $_POST["tipo"];
		$ncf->fecinivig = DateTime::createFromFormat('d/m/Y', $_POST["fecinivig"])->format('Y-m-d');
		$ncf->fecfinvig = DateTime::createFromFormat('d/m/Y', $_POST["fecfinvig"])->format('Y-m-d');
		$ncf->secuenciaini = $_POST["secuenciaini"];
		$ncf->secuenciafin = $_POST["secuenciafin"];
		$ncf->user_id = $_SESSION['user_id'];
		$ncf->update();

	 $response_array['status'] = 'success';
	 $response_array['message'] = 'Secuencia Agregada Exitosamente';
	}
}else {
    $response_array['status'] = 'fail';
    $response_array['message'] = '';
}

echo json_encode($response_array);

?>