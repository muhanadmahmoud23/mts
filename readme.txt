# Intro:
This project reads data from an Excel file and imports it into a normalized SQLite3 database using native PHP (PHP 8.0+), without third-party frameworks. 
It demonstrates clean architecture, SOLID principles, and PSR standards.

------------------------------------------------------------------------------------------------------------------------

# Database:
We changed database structure using normilization ( Normal form stages)
1. remove repeating groups
2. remove multi-value in same row
3. make sure all non key depend on primary key
4. make sure no value depend on non key value
5. split table to joins if this will lead to no loss in data

------------------------------------------------------------------------------------------------------------------------
# Initlization:

composer init --name="muhanad/excel-reader"
complete guide for creating your composer.json config.
composer require phpoffice/phpspreadsheet

------------------------------------------------------------------------------------------------------------------------
# Making Excel Ready to call:

Create ExcelReader Class to handle all Excel Reads
Will Load Excel in Construct
Add getData method to handle all data and retrive it as array to use it in database

------------------------------------------------------------------------------------------------------------------------
#Create empty database:

Create database.sqlite using : type nul > database.sqlite
------------------------------------------------------------------------------------------------------------------------
#Create DataBase Class to handle:

open connection 
close connection

------------------------------------------------------------------------------------------------------------------------
#Model Create to delete, insert data:

Now we need to make model for each table to apply Design pattern and single responsibility also apply open/close
after separting each class has its own job
Repository in models 
Adapter by changing excel to array and array to database and database to html and also database to json

------------------------------------------------------------------------------------------------------------------------
#Create Excel handler to:
delete all data before insertion to prevent duplicate data ( cant be used in live applications)
create all data as new erd
insert data from excel array

------------------------------------------------------------------------------------------------------------------------

#Create method in InvoiceModel to:
Retrive data from Excel as html table & json response ( you will find 2 table , 1 before normilization , second after normilization)
-------------------------------------------------------------------------------------------------------------------------

## To run the app please follow the follwing steps:

git clone https://github.com/your-username/native-excel-importer.git
cd native-excel-importer
composer install
Place any excel file with same rows
run the app throught apache or any virtual server


