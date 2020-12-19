<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mission;
use App\Models\Soldier;
class MissionController extends Controller
{
    public function createMission(Request $request)
    {

    	$response="";


    	// Leer el contenido de la petición
    	$data = $request->getContent();

    	// Decodificar el json
    	$data = json_decode($data);

    	// Si hay un json, crear el libro
    	if($data) {
    		$mission = new Mission();

    		//TAREA: Validar los datos antes de guardar

    		$mission->descripcion = $data->descripcion;
            $mission->prioridad= $data->prioridad;
			$mission->fecha_registro = $data->fecha_registro;
			if(isset($data->estado)){
				$mission->estado= $data->estado;
		   }
    		try{
    			$mission->save();
    			$response="OK";
    		} catch(\Exception $e){
    			$response=$e->getMessage();
    		}
    		
    	}

    	return response($response);

    }
     public function updateMission(Request $request, $id) 
    {
        $response="";

        //Buscar el libro por id
        $mission = Mission::find($id);
        

        // Si hay un libro, actualizar el libro
        if($mission) {

            // Leer el contenido de la petición
            $data = $request->getContent();

            // Decodificar el json
            $data = json_decode($data);
            

            if($data){

                $mission->descripcion = (isset($data->descripcion) ? $data->descripcion : $soldier->descripcion);
                $mission->prioridad = (isset($data->prioridad) ? $data->prioridad : $soldier->prioridad);
                $mission->estado= (isset($data->estado) ? $data->estado : $soldier->estado);
               
                try{
                    $mission->save();
                    $response="OK";
                } catch(\Exception $e){
                    $response=$e->getMessage();
                }
            } else {
                $response = "No hay mision";
            }

                   
            
        }

        return response()->json($response);
    }
    public function viewAllMissions(){      

	   	    $response = "";
	        $mission = Mission::orderBy('prioridad', 'ASC')->get();
	        $response= [];
        
	       if($mission){
           foreach ($mission as $mission) {

				$response []= [
                "codigo" => $mission->id,
                "descripcion" => $mission->descripcion,
                "fecha" => $mission->fecha_registro,
                "prioridad" => $mission->prioridad,
                "estado" => $mission->estado
            	];
			}

          }  else{
          	$response="No valido";
          }
		return response()->json($response);

	}

	public function viewMissionDetails($id){      

	   	   	$response="";

	    	
        	$mission = Mission::find($id);
        	

	       if($mission){
           	 $soldier = $mission->soldier;
           		$response = [
                "codigo" => $mission->id,
                "descripcion" => $mission->descripcion,
                "fecha" => $mission->fecha_registro,
                "prioridad" => $mission->prioridad,
                "estado" => $mission->estado
            	];

            	if($mission->teamOne){
            	
            		$response["codigo_equipo"] =$mission->teamOne->id;
            		$response["nombre_equipo"] =$mission->teamOne->nombre;
            		
					if($mission->teamOne->soldier_id){

						$id = $mission->teamOne->soldier_id;
						$soldiere = Soldier::find($id);
					    $response["codigo_lider"] =$soldiere->id;
					    $response['apellido_lider'] =$soldiere->nombre;
					    $response['numero_placa'] =$soldiere->placa;
						$response['rango'] =$soldiere->rango;
					
					}
            	}
            	for ($i=0; $i <count($mission->soldier) ; $i++) { 
                $response[$i]["soldier_id"] = $soldier[$i]->id;
                $response[$i]["placa"] = $soldier[$i]->placa;
                $response[$i]["rango"] = $soldier[$i]->rango;
                $response[$i]["apellido"] = $soldier[$i]->apellido;
            	}
            


          }  else{
          	$response="Mision no encontrada";
          }
		return response()->json($response);
		}

}
