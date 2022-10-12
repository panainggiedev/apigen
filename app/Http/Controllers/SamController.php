<?php

namespace App\Http\Controllers;

use App\Sam;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class SamController extends BaseController
{
    public function index(Request $request)
    {
    
        //$apikey = DB::table('current_a_p_i_s')->where('api_key');
        //ini_set ('display errors', 1);
        if($request->has('ueiSAM')) {

            $response = Sam::call($request->query('ueiSAM'));
            
        

            if($response) {

                return response($this->parse($response));

            }

            return response(['message'=>'No record found'], 404);

        } 
    }

    public function samgov(Request $request)
    {
        
        if($request->has('ueiSAM')) {

            $response = Sam::call($request->query('ueiSAM'));
           
        

            if($response) {

                return response($this->parsev2($response));

            }

            return response(['message'=>'No record found'], 404);

        } 
    }

    // this is a sample push
    // use this for duns testing 9628807820000
    public function parse($data) {

        $data = $data->sam_data->registration;
        $naics = isset($data->naics) ? $this->arrayToString($data->naics) : " ";
        $mailingAddress = isset($data->mailingAddress) ? $this->arrayToString($data->mailingAddress) : " ";
        $samAddress = isset($data->samAddress) ? $this->arrayToString($data->samAddress) : " ";

        return [
            "legalBusinessName"     => isset($data->legalBusinessName) ? $data->legalBusinessName : " ",
            "hasKnownExclusion"     => isset($data->hasKnownExclusion) ? "TRUE" : "FALSE",
            "debtSubjectToOffset"   => isset($data->debtSubjectToOffset) ? "TRUE" : "FALSE",
            "status"                => isset($data->status) ? $data->status : " ",
            "stateOfIncorporation"  => isset($data->stateOfIncorporation) ? $data->stateOfIncorporation : " ",
            "certificationsURL"     => isset($data->certificationsURL->pdfUrl) ? $data->certificationsURL->pdfUrl : " ",
            "mailingAddress"        => $mailingAddress,
            "samAddress"            => $samAddress,
            "naics"                 => $naics
        ];
    }

    public function parsev2($data) {

        $data = $data->entityData[0];
        $samAddress = isset($data->coreData->mailingAddress) ? $this->getBusinessType($data->coreData->mailingAddress) : " ";
        $naicsCodes = isset($data->assertions->goodsAndServices->naicsList) ? $this->getNaicsCode($data->assertions->goodsAndServices->naicsList) : " ";
        $naicsNames = isset($data->assertions->goodsAndServices->naicsList) ? $this->getNaicsName($data->assertions->goodsAndServices->naicsList) : " ";
        $businessTypeList = isset($data->coreData->businessTypes->businessTypeList) ?$this->getBusinessType($data->coreData->businessTypes->businessTypeList) : " ";

        return [
            "legalBusinessName"     => isset($data->entityRegistration->legalBusinessName) ? $data->entityRegistration->legalBusinessName : " ",
            "cageCode"     => isset($data->entityRegistration->cageCode) ? $data->entityRegistration->cageCode : " ",
            "exclusionStatusFlag"     => isset($data->entityRegistration->exclusionStatusFlag) ? $data->entityRegistration->exclusionStatusFlag : null,
            "debtSubjectToOffset"   => isset($data->coreData->financialInformation->debtSubjectToOffset) ? $data->coreData->financialInformation->debtSubjectToOffset : null,
            "ueiStatus"                => isset($data->entityRegistration->ueiStatus) ? $data->entityRegistration->ueiStatus : " ",
            "stateOfIncorporationDesc"  => isset($data->coreData->generalInformation->stateOfIncorporationDesc) ? $data->coreData->generalInformation->stateOfIncorporationDesc: " ",
            // "certificationsURL"     => isset($data->certificationsURL->pdfUrl) ? $data->certificationsURL->pdfUrl : " ",
            "zipCode"               => isset($data->coreData->mailingAddress->zipCode) ? $data->coreData->mailingAddress->zipCode : " ", 
            "city"                  => isset($data->coreData->mailingAddress->city) ? $data->coreData->mailingAddress->city : " ",
            "countryCode"           => isset($data->coreData->mailingAddress->countryCode) ? $data->coreData->mailingAddress->countryCode : " ",
            "addressLine2"          => isset($data->coreData->mailingAddress->addressLine2) ? $data->coreData->mailingAddress->addressLine2 : " ",
            "addressLine1"          => isset($data->coreData->mailingAddress->addressLine1) ? $data->coreData->mailingAddress->addressLine1 : " ",
            "zipCodePlus4"          => isset($data->coreData->mailingAddress->zipCodePlus4) ? $data->coreData->mailingAddress->zipCodePlus4 : " ",
            "stateOrProvinceCode"   => isset($data->coreData->mailingAddress->stateOrProvinceCode) ? $data->coreData->mailingAddress->stateOrProvinceCode : " ",
            "samAddress"            => $samAddress,
            "naicsCodes"            => $naicsCodes,
            "naicsNames"            => $naicsNames,
            "primaryNaics"          => isset($data->assertions->goodsAndServices->primaryNaics) ? $data->assertions->goodsAndServices->primaryNaics : " ",
            "businessTypeList"      => $businessTypeList
        ];
    }


    public function arrayToString($data)
    {
        $string = '';
            foreach($data as $key => $value) {

                if(is_string($value)) {

                    $string .= $key. ': ' . json_encode($value) .', ';
                }
                if(is_array($value) || is_object($value)) {
                    $string .= $this->arrayToString($value) . ';';
                }
            }
            return $string;
    }

    
    public function getBusinessType($data)
    {
        $string = '';
            foreach($data as $key => $value) {
                if(is_string($value)) {
                    $string .= json_encode($value) .', ';
                }
                if(is_array($value) || is_object($value)) {
                    $string .= $this->getBusinessType($value) . ';';
                }
            }
            return $string;      
    }

    public function getNaicsName($data)
    {
        $string = '';
            foreach($data as $key => $value) {
                if(is_string($value) && !is_numeric($value)) {
                    $string .= json_encode($value) .', ';
                }
                if(is_array($value) || is_object($value)) {
                    $string .= $this->getNaicsName($value);
                }
            }
            return $string;
    }

    public function getNaicsCode($data)
    {
        $string = '';
            foreach($data as $key => $value) {
                if(is_numeric($value)) {
                    $string .= json_encode($value) .', ';
                }
                if(is_array($value) || is_object($value)) {
                    $string .= $this->getNaicsCode($value);
                }
            }
            return $string;
    }

    public function getPrimeNaics($data)
    {
        $string = '';
            foreach($data as $key => $value) { 
                    if($value == 'true') {
                        foreach($data as $key => $value) { 
                            if(is_numeric($value)) {
                                $string .= json_encode($value) .', ';
                            }
                        }
                    }
                    if(is_array($value) || is_object($value)) {
                        $string .= $this->getPrimeNaics($value);
                    }
            }
            return $string;
    }

    // public function primeNaics($data) {

    //     if(is_string($data) || is_bool($data) || is_numeric($data)) return [];

    //     $find = ['true'];

    //     $collection = [];

    //     foreach($data as $key => $value) {

    //         $search = array_search($key, $find);

    //         if($search === false) {

    //             $loop = $this->primeNaics($value);

    //             if(empty($loop)){

    //             } else {
    //                 // $collection[] = $loop;
    //                 array_push($collection, $loop);
    //             }

    //         } else {
    //             // $collection[$key] = $value;
    //             array_push($collection, $value);

    //         }
    //     }

    //     return $collection;

    // }

    public function deepfind() {

        if(is_string($data) || is_bool($data) || is_numeric($data)) return;

        $collection = [];

        $loop = 0;

        foreach($data as $key => $val) {
            $loop++;



            if(in_array($key, Sam::$fields)) {

                    $collection[$key] = $val;

            } else {

                $this->parse($val);

            }
        }

        return [$collection, $loop];
    }
}
