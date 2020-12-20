<?php
namespace App\Http\Controllers;



use Illuminate\Http\Request;
use App\Models\Soldier;
use App\Models\Team;

class SoldierController extends Controller
{   

    /**
     * Función que se encarga de crear un solado y añadirlo a la base de datos utilizando la ruta adecuada
     */
    public function createSoldier(Request $request)
    {

    	$response="";


    	// Leer el contenido de la petición
    	$data = $request->getContent();

    	// Decodificar el json
    	$data = json_decode($data);

    	// Si hay un json, crear el soldado
    	if($data) {
    		$soldier = new Soldier();

    		//Validar los datos antes de guardar

    		$soldier->nombre = $data->nombre;
    		$soldier->apellido = $data->apellido;
    		$soldier->fecha_nacimiento = $data->fecha_nacimiento;
    		$soldier->fecha_incorporacion = $data->fecha_incorporacion;
    		$soldier->placa = $data->placa;
    		$soldier->rango = $data->rango;
    		$soldier->estado = $data->estado;

    		try{
    			$soldier->save();
    			$response="OK";
    		} catch(\Exception $e){
    			$response=$e->getMessage();
    		}
    		
    	}

    	return response($response);

    }

    /**
     * Función que sirve para editar todos los datos de un soldado excepto su estado.
     El soladado se selecciona pasandole su id mediante parametro.
     */
     public function updateSoldier(Request $request, $id) 
     {
        $response="";

        //Buscar el libro por id
        $soldier = Soldier::find($id);
        

        // Si hay un libro, actualizar el libro
        if($soldier) {

            // Leer el contenido de la petición
            $data = $request->getContent();

            // Decodificar el json
            $data = json_decode($data);
            

            if($data){

                $soldier->nombre = (isset($data->nombre) ? $data->nombre : $soldier->nombre);
                $soldier->apellido = (isset($data->apellido) ? $data->apellido : $soldier->apellido);
                $soldier->fecha_nacimiento = (isset($data->fecha_nacimiento) ? $data->fecha_nacimiento : $soldier->fecha_nacimiento);
                $soldier->fecha_incorporacion = (isset($data->fecha_incorporacion) ? $data->fecha_incorporacion : $soldier->fecha_incorporacion);
                $soldier->placa = (isset($data->placa) ? $data->placa : $soldier->placa);
                $soldier->rango = (isset($data->rango) ? $data->rango : $soldier->rango);
                try{
                    $soldier->save();
                    $response="OK";
                } catch(\Exception $e){
                    $response=$e->getMessage();
                }
            } else {
                $response = "No book";
            }

            
            
        }

        return response()->json($response);
    }


    /**
     * Función que sirve para editar el estado de un soldado.
     El soladado se selecciona pasandole su id mediante parametro.
     */
     public function updateEstado(Request $request, $id) 
     {
        $response="";

        //Buscar el libro por id
        $soldier = Soldier::find($id);
        

        // Si hay un libro, actualizar el libro
        if($soldier) {

            // Leer el contenido de la petición
            $data = $request->getContent();

            // Decodificar el json
            $data = json_decode($data);
            

            if($data){

                $soldier->estado = (isset($data->estado) ? $data->estado : $soldier->estado);
                

                try{
                    $soldier->save();
                    $response="OK";
                } catch(\Exception $e){
                    $response=$e->getMessage();
                }
            } else {
                $response = "No book";
            }

            //TAREA: Validar los datos antes de guardar        
            
        }

        return response()->json($response);
    }

  /**
   *Funcion que muestra la lista de soldados y la información de su equipo 
   */
  public function viewAll(){      

    $response = "";
    $soldiers = Soldier::all();
    $response= [];
    

    for ($i=0; $i <count($soldiers) ; $i++) { 

        $response[$i] = [
            "nombre" => $soldiers[$i]->nombre,
            "apellido" => $soldiers[$i]->apellido,
            "rango" => $soldiers[$i]->rango,
            "numero_placa" => $soldiers[$i]->placa
        ];

        if($soldiers[$i]->team_id){

            $response[$i]['team_id'] = $soldiers[$i]->team->id;
            $response[$i]['team_name'] = $soldiers[$i]->team->nombre;
        }else{
            $response[$i]['team_id'] = "Sin equipo";
            $response[$i]['team_name'] = "Sin equipo";
        }
    }
    return response()->json($response);

}

    /**
     * Funcion que muestra la informacion de un soldado en concreto. 
     Devuelve la infrmacion del soldado, su equipo, y el lider de su quipo
     El soldado se selecciona pasando su id por parametro
     */
     public function details($soldier_id){

        $response = "";
        $soldier = Soldier::find($soldier_id);
        //creo un lider, que no es mas que un soldado normal. Lo que hace especial a este soldado es que es el soldado cuyo id es el del lider del equipo
        if($soldier->team&&$soldier->team->soldier_id)
            $leader = Soldier::find($soldier->team->soldier_id);
        if($soldier){

            $response = [
                "id" => $soldier->id,
                "nombre" => $soldier->nombre,
                "apellido" => $soldier->apellido,
                "fecha_nacimiento" => $soldier->fecha_nacimiento,
                "fecha_incorporacion" => $soldier->fecha_nacimiento,
                "numero_placa" => $soldier->placa,
                "rango" => $soldier->rango,
                "estado" => $soldier->estado
            ];

            if($soldier->team_id){
                
                $response['team_id'] = $soldier->team->id;
                $response['team_nombre'] = $soldier->team->nombre;
            }else{
                $response['team'] = "Sin equipo";
            }
            
            if($soldier->team_id && $soldier->team->soldier_id){
                
                $response['leader_id'] = $leader->id;
                $response['leader_nombre'] = $leader->nombre;
                $response['leader_apellido'] = $leader->apellido;
                $response['leader_rango'] = $leader->rango;
            } else{
               $response['leader'] = "Sin lider";
           }    
       }

       return response()->json($response);
   }  

     /**
      * Funcion que muestra el historial de misiones de un soldado. Se selecciona el soldado por parametro
      */
     public function missionHistoryList($soldier_id){

        //busco el soldado por su id
        $soldier = Soldier::find($soldier_id);

        $response=[];

        //para que se vea en la petición el id del soldado y su nombre y apellido
        $response[] = [                 
            "soldier_id" => $soldier->id,
            "soldier_nombre" => $soldier->nombre,
            "soldier_apellido" => $soldier->apellido
        ];

        //para imprimir la información de todas las misiones en las que ha estado, regustrado en la tabla soldier_mission

        for ($i=0; $i <count($soldier->mission) ; $i++) { 

            $response['mission_id'] = $soldier->mission[$i]->id;
            $response['nombre_mision'] = $soldier->mission[$i]->descripcion;
            $response['fecha_registro'] = $soldier->mission[$i]->fecha_registro;
            $response['estado'] =$soldier->mission[$i]->estado;                
            
        }

        return response()->json($response);
    }
}
