# Estap OKS
Fork of https://bitbucket.org/acg-bonn/estap/overview
Changes made by Robert Rabe and Nils Witt

Changes made to the original Repo:
* Added option for appointments on different days
* Added option to add times for each teacher separately

Original Readme.md:
ESTAP
=====

ESTAP is a web application which can be used by schools to organize 
parent/teacher interviews. An admin can setup the database by generating 
teachers, pupils and times. Parents can login to reserve interview times. 
Teachers can login to see their calendar.  


Requirements
------------

* MySQL 5.0
* PHP 5.3 or newer with enabled PDO and MySQL support
* [PhoolKit](https://github.com/kayahr/phoolkit)


Installation
------------

* Create a new MySQL database and execute the `database/mysql.sql` file in it.
  This will create the database schema and enables an initial admin user 
  called `admin` with the initial password `estap`.
* Download [PhoolKit](https://github.com/kayahr/phoolkit) and copy the contents
  of the `src` folder somewhere into the PHP include path or the `web/lib` folder
  of ESTAP.
* Copy the `web/config-example.php` to `web/config.php` and edit the file to 
  setup ESTAP. You must enter at least the database connection parameters. 
* Copy all files from the `web` directory to your webserver.
* Open the web in your browser.
* Switch to the admin login page at the bottom of the screen.
* Login as `admin` with password `estap`.
* Immediately change your username and password.


Setup
-----

Initially the database doesn't contain any times, teachers and pupils. Before
the parent/teacher interviews an admin must create all this data. After this is 
done the parent logins and time reservations must be enabled. Then parents can 
start to reserve interview times. 


License
-------

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.