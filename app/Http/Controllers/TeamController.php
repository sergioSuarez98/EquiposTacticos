<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Team;
use App\Models\Soldier;
use App\Models\Mission;
use App\Models\SoldierMission;


class TeamController extends Controller
{

    /**
     * Funcion que crea un equipo y lo añade a la base de datos.
     */
    public function createTeam(Request $request)
    {

    	$response="";

    	
        
    	// Leer el contenido de la petición
    	$data = $request->getContent();

    	// Decodificar el json
    	$data = json_decode($data);

    	// Si hay un json,crear equipo
    	if($data) {
    		$team = new Team();

    		//Validar los datos antes de guardar
    		$team->nombre = $data->nombre;
    		
    		
    		try{
    			$team->save();
    			$response="OK";
    		} catch(\Exception $e){
    			$response=$e->getMessage();
    		}
    		
    	}

    	return response($response);

    }

    /**
     * Funcion que modifica el nombre de un equipo.
     Selecciona el equipo por parametro
     */
     public function updateTeam(Request $request, $id) 
     {
        $response="";

        //Buscar el libro por id
        $team = Team::find($id);
        

        
        if($team) {

            // Leer el contenido de la petición
            $data = $request->getContent();

            // Decodificar el json
            $data = json_decode($data);
            

            if($data){

                $team->nombre = (isset($data->nombre) ? $data->nombre : $team->nombre);
                

                try{
                    $team->save();
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
     * Funcion que borra un equipo
     Selecciona el equipo por parametro
     */
     public function deleteTeam(Request $request, $id){

      $response = "";
      
		//Buscar el equipo por su id

      $team = Team::find($id);

      if($team){

         try{
            $team->delete();
            $response = "OK";
        }catch(\Exception $e){
            $response = $e->getMessage();
        }
        
    }else{
     $response = "No team";
 }

 
 return response($response);
}

    /**
     * Funcion que relaciona un soldado con un equipo. Este soldado sera lider.
     Selecciona el equipo por parametro
     */
     public function addLeader(Request $request,$team_id) 
     {
        $response="";

        //Buscar el libro por id
        $team = Team::find($team_id);

        
        
        // Leer el contenido de la petición
        $data = $request->getContent();

        // Decodificar el json
        $data = json_decode($data);
        $soldier = Soldier::find($data->soldier_id);

        // Si hay un libro, actualizar el libro
        if($data&&$team&&$soldier) {

          
           $team->soldier_id = (isset($data->soldier_id) ? $data->soldier_id : $team->soldier_id);

           $soldier->team_id = (isset($team_id) ? $team_id : $data->soldier_id);


           try{

            $team->save();
            $soldier->save();
            $response="OK";

        }catch(\Exception $e){

            $response=$e->getMessage();

        }
        
    }else{$response = "No soldado";}

    return response()->json($response);
}
    /**
     * Se añade un soldado al equipo.
     Tanto soldado como el quipo al que se le relacciona
     se especifican mediante $data
     */
     public function addSoldier(Request $request) 
     {
         $response = "";
		//Leer el contenido de la petición
         $data = $request->getContent();

		//Decodificar el json
         $data = json_decode($data);
         $soldier = Soldier::find($data->soldier);
		//Si hay un json válido, crear el libro
         if($data&&$soldier&&Team::find($data->team)){
             

			//TODO: Validar los datos antes de guardar el libro

             $soldier->team_id = $data->team;
             
             try{
                $soldier->save();
                $response = "OK";
            }catch(\Exception $e){
                $response = $e->getMessage();
            }

        }
        return response($response);
        
    }

    /**
     * Se le asigna una mision a un equipo.
     Tanto la mision como el equipo se especifican por data
     */
     public function asignarMision(Request $request) 
     {
        $response = "";
        //Leer el contenido de la petición
        $data = $request->getContent();

        //Decodificar el json
        $data = json_decode($data);
        //busca un equipo con el id del json
        $team = Team::find($data->team);
        //busca una mision con el id de json
        $mission = Mission::find($data->mission);
        //me guardo los soldados que devuelve getSoldiers
        $soldiers= $this->getSoldiers($team->id);

        
        //Comprobaciones
        if($data){
            if($team && !isset($team->mission_id)){

                if($mission){


                   foreach ($soldiers as $soldier) {

                    $soldierMission = new soldierMission();
                    $soldierMission->soldier_id = $soldier['id'];
                    $soldierMission->mission_id = $data->mission; 
                    $soldierMission->save(); 

                }

                $team->mission_id = $data->mission;
                $mission->estado = "curso";

                try{
                    $team->save();
                    $mission->save();
                    
                    $response = "OK";
                }catch(\Exception $e){
                    $response = $e->getMessage();
                }




            }



        }else{

            $response = "Mision ya asignada";
        }

        
        
    }else{
       $response = "No valido";
   }
   return response($response);
   
}
    /**
     * Funcion en la que guardo todos los soldados con un cierto team_id.
     Ese team_id se pasa por parametro.
     */
     public function getSoldiers($team_id) 
     {
        
        $response = [];
        $soldiers = Soldier::all();
        //Si hay un json válido, crear el libro
        
        foreach ($soldiers as $soldier) {

           if($soldier->team_id === $team_id){
             $response[] =[
                "id" => $soldier->id
            ];
        }
        
    } 
    return $response;
    
}

    /**
     * Función que muestra los la información de un equipo
     y los soldados que pertenecen a el.

     Se selecciona el equipo pasandole su id por parametro
     */
     public function miembrosEquipos($id) 
     {
       $response = "";
       $response = [];
       $team = Team::find($id);
       $leader = Soldier::find($team->soldier_id);

       $response[] = [                 
        "team_id" => $team->id,
        "team_nombre" => $team->nombre,
        "leader_id" => $team->soldier_id,
        "leader_nombre"=> $leader->nombre
    ];
    

    

    $soldiers = Soldier::all();


    for ($i=0; $i < count($soldiers) ; $i++) { 
     
       if($soldiers[$i]->team_id == $id ){
        
         $response[$i]["soldiers"] =[

            "soldier_id" => $soldiers[$i]->id,
            "soldier_name" => $soldiers[$i]->nombre,
            "soldier_apellido" => $soldiers[$i]->apellido,
            "soldier_placa" => $soldiers[$i]->placa,
            "soldier_rango" => $soldiers[$i]->rango

        ];
    }
}



return $response;

}  

    /**
     * Elimina un soldado de un equipo.
     Se especifica medianter el data.
     */
     public function eliminarSoldado(Request $request){

        $response = "";

        $data = $request->getContent();

        $data = json_decode($data);

        $soldier = Soldier::find($data->soldier);

        if($soldier){

            $soldier->team_id = null;

            try{
                $soldier->save();
                $response = "OK";
            }catch(\Exception $e){
                $response = $e->getMessage();
            }
        }else{
            $response = "No existe ese soldado";
        }

        return response($response);
    }

    /**
     * Modifica el lider del equipo
     */
    public function newLeader(Request $request){

        $response = "";

        $data = $request->getContent();

        $data = json_decode($data);

        $soldier = Soldier::find($data->soldier);
        $team = Team::find($data->team);

        if($soldier && $team){

            $team->soldier_id = $soldier->id;
            $soldier->team_id = $data->team;
            
            try{
                $team->save();
                $soldier->save();
                $response = "OK";
            }catch(\Exception $e){
                $response = $e->getMessage();
            }
        }else{
            $response = "Equipo o soldado no validos";
        }

        return response($response);
    }

}
