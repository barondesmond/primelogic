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