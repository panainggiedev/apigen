<?php

namespace App;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\SamController;
use App\Models\CurrentAPI;
class Sam
{


    /**
     * Add more keys here
     */
    public static $keys = [
        'y5udMWHx5qVmC3Du6M39edg5K8nU25Nc'
    ];

    public static $fields = [
        "legalBusinessName",
        "hasKnownExclusion",
        "debtSubjectToOffset",
        "euiStatus",
        "stateOfIncorporationDesc",
        // "certificationsURL",
    ];

    
    public static function call($ueiSAM)
    {
        // $duns = $this->format($duns);
        // 139LpKiTp5wZgUVLCgzhL17exPiRlTD1Nr5wxRA7
        // iPkdTjNmtK23sXr330FNnN2CKyTfEoweSUUMBiUf
        //jctovcS1qTSbPNjCVH7Se5i7pJsAfMXOrKg0gtSM
        //o0x6wEF111KXTbADyDZue2AmsOKsoHOMwZ6FT6SP
        //wx0YpRaiSD8PS8LRtL11pEKCdViAsALmOuHM2avd
        //oEPWOQ5ASyg4qmfq9PEtKntuFQogSN7mISAXZ546
        //jpA6NJVYXOe4DUzICoxzCD3tgUJ4IA2FVo49kDbr
        $apidata = DB::table('current_a_p_i_s')->value('api_key');
        
        //return json_decode(@file_get_contents("http://api.data.gov/sam/v8/registrations/$duns?api_key=lJewYdb9f5w9CRpFkYk0a5Iv2UuJCUVXpUJcnHD8"));
        //return json_decode(@file_get_contents("https://api.sam.gov/entity-information/v2/entities?api_key=wx0YpRaiSD8PS8LRtL11pEKCdViAsALmOuHM2avd&ueiDUNS=$duns"))
        return json_decode(@file_get_contents("https://api.sam.gov/entity-information/v2/entities?api_key=$apidata&ueiSAM=$ueiSAM"));
        

    }
}