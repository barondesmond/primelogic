<?php
include("_db_config.php");
include("_location_api.php");
/*
LocName MSU Partnership School
locations=33.465871,-88.810235
Locations=33.44923,-88.8003



http://www.mapquestapi.com/geocoding/v1/address?key=MAPQUEST_KEY=MSU Partnership School, Starkville, MS
LocName,
latitude,
longitude,
1801 E Main Street, Tupelo, MS 38804
$tcm['results']['locations'][$i]['street'] == $loc['add1'] ? match : ambiguous
$loc = $tcm['results']['locations'][$i];
$loc['street']

*/
define('TCM', '
{
  "info": {
    "statuscode": 0,
    "copyright": {
      "text": " 2019 MapQuest, Inc.",
      "imageUrl": "http://api.mqcdn.com/res/mqlogo.gif",
      "imageAltText": " 2019 MapQuest, Inc."
    },
    "messages": []
  },
  "options": {
    "maxResults": -1,
    "thumbMaps": false,
    "ignoreLatLngInput": false
  },
  "results": [
    {
      "providedLocation": {
        "location": "1801 E Main Street, Tupelo, MS 38804"
      },
      "locations": [
        {
          "street": "1801 E Main St",
          "adminArea6": "",
          "adminArea6Type": "Neighborhood",
          "adminArea5": "Tupelo",
          "adminArea5Type": "City",
          "adminArea4": "Lee",
          "adminArea4Type": "County",
          "adminArea3": "MS",
          "adminArea3Type": "State",
          "adminArea1": "US",
          "adminArea1Type": "Country",
          "postalCode": "38804-2934",
          "geocodeQualityCode": "L1AAA",
          "geocodeQuality": "ADDRESS",
          "dragPoint": false,
          "sideOfStreet": "L",
          "linkId": "rnr3734152|i32549237",
          "unknownInput": "",
          "type": "s",
          "latLng": {
            "lat": 34.257765,
            "lng": -88.667338
          },
          "displayLatLng": {
            "lat": 34.257965,
            "lng": -88.667341
          }
        }
      ]
    }
  ]
}');


$db = location_api($_REQUEST);
header('Content-Type: application/json');
//echo TCM;
echo json_encode($db);

