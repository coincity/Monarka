<?php
// autoload.php
// esta funcion elimina el hecho de estar agregando los modelos manualmente


spl_autoload_register(function ($modelname) {
	if(Model::exists($modelname)){
		include Model::getFullPath($modelname);
	} 
});



?>