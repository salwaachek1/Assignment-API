<?php

namespace App\Http\Controllers;
use GuzzleHttp\Exception\GuzzleException;
class CalculController extends Controller
{
    //working on 10 records 
    public function sort_rec($array_records,$i,$name){      
        $result=0;
        $count_rock=0;
        $count_paper=0;
        $count_scissors=0;
        if($array_records["data"][$i]["playerA"]["name"]==$name ){
        if ($array_records["data"][$i]["playerA"]["played"] == "ROCK") {
        $count_rock=$count_rock+1;    
        switch ($array_records["data"][$i]["playerB"]["played"]) {
            case "PAPER":            
            break;
            case "SCISSORS":
            $result = $result+1;
            break;
        }
      } else if ($array_records["data"][$i]["playerA"]["played"]== "PAPER") {
          $count_paper=$count_paper+1;   
        switch ($array_records["data"][$i]["playerB"]["played"]) {
          case "ROCK":
            $result = $result+1;
            break;
            case "SCISSORS":          
            break;
        }
      } else if ($array_records["data"][$i]["playerA"]["played"] == "SCISSORS") {
          $count_scissors=$count_scissors+1;   
        switch ($array_records["data"][$i]["playerB"]["played"]) {
            case "ROCK":
            break;
            case "PAPER":
            $result = $result+1;
            break;
        }
      }
        }
       
    if($array_records["data"][$i]["playerB"]["name"]==$name ){
        if ($array_records["data"][$i]["playerB"]["played"] == "ROCK") {
            $count_rock=$count_rock+1;    
        switch ($array_records["data"][$i]["playerA"]["played"]) {
            case "PAPER":    
            $result = $result+1;        
            break;
            case "SCISSORS":            
            break;
        }
      } else if ($array_records["data"][$i]["playerB"]["played"]== "PAPER") {
          $count_paper=$count_paper+1;   
        switch ($array_records["data"][$i]["playerA"]["played"]) {
          case "ROCK":            
            break;
            case "SCISSORS":  
                $result = $result+1;        
            break;
        }
      } else if ($array_records["data"][$i]["playerB"]["played"] == "SCISSORS") {
          $count_scissors=$count_scissors+1;   
        switch ($array_records["data"][$i]["playerA"]["played"]) {
            case "ROCK":
                $result = $result+1;
            break;
            case "PAPER":            
            break;
        }
      }
    }
     

      return array($result,$count_rock,$count_paper,$count_scissors);
      


    }
    public function getFewRecords($name)
    {
    $limit=0;    
    $count_games = 0;
    $games_played= [];
    $result=0;
    $count_rock=0;
    $count_paper=0;
    $count_scissors=0;
    $API_END_POINT = "https://bad-api-assignment.reaktor.com";
    $FIRST_PAGE = "/rps/history";
    $games = json_decode(file_get_contents($API_END_POINT.$FIRST_PAGE), true);
    $next = $games["cursor"];   
    for($i=0;$i<count($games["data"]);$i++)    
    {
        if(($games["data"][$i]["playerA"]["name"]==$name)||($games["data"][$i]["playerB"]["name"]==$name)) {
            $count_games = $count_games + 1;
            $games_played[]=$games["data"][$i]["gameId"];
        $x=$this->sort_rec($games,$i,$name);
        $result=$x[0]+$result;
        $count_rock=$x[1]+$count_rock;
        $count_paper=$x[2]+$count_paper;    
        $count_scissors=$x[3]+$count_scissors; 
        }      
        }
    while ($limit < 10) {
     $games = json_decode(file_get_contents($API_END_POINT.$next), true);
     $next =$games["cursor"];
    for($i=0;$i<count($games["data"]);$i++)    
        {
       if(($games["data"][$i]["playerA"]["name"]==$name)|| ($games["data"][$i]["playerB"]["name"]==$name)) {
            $count_games = $count_games + 1; 
            $games_played[]=$games["data"][$i]["gameId"];     
      $x=$this->sort_rec($games,$i,$name);
        $result=$x[0]+$result;
        $count_rock=$x[1]+$count_rock;
        $count_paper=$x[2]+$count_paper;    
        $count_scissors=$x[3]+$count_scissors;   
        
        }
    }  
    $limit=$limit+1;
      
    }
    if(($count_rock>=$count_paper)&&($count_rock>=$count_scissors)){
        $most_played="Rock";
    }
    else if(($count_paper>=$count_rock)&&($count_paper>=$count_scissors)){
         $most_played="Paper";
    }
    else if(($count_scissors>=$count_rock)&&($count_scissors>=$count_paper)){
         $most_played="Scissors"; 
    }
    $ratio=$result/$count_games;
    $count=strval($count_games);
        return response()->json([
                    'count' => $count,
                    'ratio' => $ratio,
                    'most played' => $most_played,
                    'games played'=> $games_played,
                ]);
    // return response()->json($count,$result);
    }
    
    public function getAllRecords($name)
    {
        
    $count_games = 0;
    $API_END_POINT = "https://bad-api-assignment.reaktor.com";
    $FIRST_PAGE = "/rps/history";
    $games = json_decode(file_get_contents($API_END_POINT.$FIRST_PAGE), true);
    $next = $games["cursor"];   
    for($i=0;$i<count($games["data"]);$i++)    
    {
        if(($games["data"][$i]["playerA"]["name"]==$name)|| ($games["data"][$i]["playerB"]["name"]==$name)) {
            $count_games = $count_games + 1;
            $games_played[]=$games["data"][$i]["gameId"];
            $result=$this->sort_rec($games,$i,$name)+$result;
        }
    }  
    while ($next !== null) {
     $games = json_decode(file_get_contents($API_END_POINT.$next), true);
      $next =$games["cursor"];
    for($i=0;$i<count($games["data"]);$i++)    
        {
        if(($games["data"][$i]["playerA"]["name"]==$name)|| ($games["data"][$i]["playerB"]["name"]==$name)) {
            $count_games = $count_games + 1;
            $games_played[]=$games["data"][$i]["gameId"];
            $result=$this->sort_rec($games,$i,$name)+$result;
        }
    }  
      
    }

    $count=strval($count_games);
    return response()->json($count);
    }

}
