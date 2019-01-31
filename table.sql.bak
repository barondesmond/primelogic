CREATE SEQUENCE TimeClockAppCounter 
    AS int  
    START WITH 1  
    INCREMENT BY 1 ;  


/*
CREATE TABLE TimeClockApp ( 
TimeClockID int PRIMARY KEY CLUSTERED DEFAULT (NEXT VALUE FOR TimeClockAppCounter),  
EmpNo INT NOT NULL DEFAULT '0', 
installationID VARCHAR(255) NOT NULL DEFAULT '',
Name varchar(10) NOT NULL DEFAULT '',
DispatchID varchar(10) NOT NULL DEFAULT '',
latitude DECIMAL(19,9) NOT NULL DEFAULT '0',
longitude DECIMAL(19,9) NOT NULL DEFAULT '0',
violation varchar(255) NOT NULL DEFAULT '',
event VARCHAR(10) not NULL DEFAULT '',
EmpActive INT NOT NULL DEFAULT '1',
StartEvent DATETIME NULL,
StopEvent DATETIME NULL
) 

INSERT INTO TimeClockApp (EmpNo, InstallationId, Name, latitude, longitude, event, StartEvent)
$sql = "UPDATE TimeClockApp SET EventStop = '$time' WHERE EmpNo = '" . $_REQUEST['EmpNo'] "' and installationId = '" . $_REQUEST['installationId'] . "' and EmpActive = '0'";

*/


/* create*
CREATE TABLE UserAppAuth (
EmpNo INT PRIMARY KEY NOT NULL,
installationID varchar(255),
authorized INT NOT NULL DEFAULT '0'
)
*/

/*
INSERT INTO UserAppAuth (EmpNo, installationID) VALUES ('0195', 'askdfhahlkjsdhfladf');
*/

*/
*/
LocationLatLong
LocName MSU Partnership School
locations=33.465871,-88.810235
http://www.mapquestapi.com/geocoding/v1/address?key=N2FSccO8I7dE53zFgXAUQpJg7Q4PDwmj&location=Starkville,%20MS

$json = {"info":{"statuscode":0,"copyright":{"text":"\u00A9 2019 MapQuest, Inc.","imageUrl":"http://api.mqcdn.com/res/mqlogo.gif","imageAltText":"\u00A9 2019 MapQuest, Inc."},"messages":[]},"options":{"maxResults":-1,"thumbMaps":true,"ignoreLatLngInput":false},"results":[{"providedLocation":{"location":"Starkville, MS"},"locations":[{"street":"","adminArea6":"","adminArea6Type":"Neighborhood","adminArea5":"Starkville","adminArea5Type":"City","adminArea4":"Oktibbeha County","adminArea4Type":"County","adminArea3":"MS","adminArea3Type":"State","adminArea1":"US","adminArea1Type":"Country","postalCode":"","geocodeQualityCode":"A5XAX","geocodeQuality":"CITY","dragPoint":false,"sideOfStreet":"N","linkId":"282027040","unknownInput":"","type":"s","latLng":{"lat":33.465871,"lng":-88.810235},"displayLatLng":{"lat":33.465871,"lng":-88.810235},"mapUrl":"http://www.mapquestapi.com/staticmap/v5/map?key=N2FSccO8I7dE53zFgXAUQpJg7Q4PDwmj&type=map&size=225,160&locations=33.465871,-88.810235|marker-sm-50318A-1&scalebar=true&zoom=12&rand=938019522"}]}]}

CREATE TABLE LocationApi (
LocName varchar(150) PRIMARY KEY NOT NULL, 
Add1 varchar(30),
City varchar(25),
State varchar(2),
Zip varchar(10),
latitude numeric (28,13),
longitude numeric (28,13)
);