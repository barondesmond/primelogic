CREATE TABLE TimeClock (
TimeClockID INT PRIMARY KEY NOT NULL DEFAULT NEWSEQUENTIALID(),
EmpNo INT NOT NULL, 
Start DATE NOT NULL DEFAULT '1970-01-01', 
Stop  DATE NOT NULL DEFAULT '9999-12-31',
Photo varbinary(max) FILESTREAM NULL  
JobID varchar(10) NOT NULL DEFAULT '',
GPS 
) 


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