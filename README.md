# Estap - OKS Version [DEPRECATED]
Fork of [ESTAP](https://bitbucket.org/acg-bonn/estap/overview)

## Contributors
- See [ESTAP](https://bitbucket.org/acg-bonn/estap/overview) for initial Contributors
- Robert Rabe
- [Nils Witt](https://github.com/Nils-Witt)

Changes made to the original Repo:
* Added option for appointments on different days
* Added option to add times foreach teacher separately
* Video conferencing integration
* Minor adjustments for supporting PHP 7.4

IMPORTANT information
---------------------
This software was originally written for PHP 5.3, as of now this software has NO support and is marked as deprecated.
The deprication is caused of
- some used functions are deprecated in PHP 7.0 and removed in 8.0
- There is currently no active maintenance (if you want to takeover the maintanance contact Nils Witt)
- There will be no updates for the UI / UX
- Nearly the full backend needs a rewrite for full PHP 8.0 support and better maintainability
- Use of deprecated HTML tags and attributes


Original Readme.md:
ESTAP
=====

ESTAP is a web application which can be used by schools to organize 
parent/teacher interviews. An admin can setup the database by generating 
teachers, pupils and times. Parents can login to reserve interview times. 
Teachers can login to see their calendar.  


Requirements
------------

* MySQL 5.0 or MariaDB
* PHP  5.3 to 7.4 with enabled PDO and MySQL support (read above about newer PHP version support)
* [PhoolKit](https://github.com/kayahr/phoolkit)


Installation
------------

* Create a new MySQL database and execute the `database/paeda.sql` file in it.
  This will create the database schema and enables an initial admin user 
  called `admin` with the initial password `adminpass`.
* Download [PhoolKit](https://github.com/kayahr/phoolkit) or [Here](https://github.com/paeda-bonn/phoolkit) and copy the contents
  of the `src` folder somewhere into the PHP include path or the `web/library` folder
  of ESTAP.
* Copy the `web/config-example.php` to `web/config.php` and edit the file to 
  setup ESTAP. You must enter at least the database connection parameters. 
* Copy all files from the `web` directory to your webserver.
* Open the web in your browser.
* Switch to the admin login page at the bottom of the screen.
* Login as `admin` with password `adminpass`.
* Immediately change your username and password.

Dev Installation
----------------
* Required: Docker with compose, Git
* Clone the repository `git clone git@github.com:paeda-bonn/ESTAP-OKS-Fork.git`
* Do the steps for Installation
* Start the Containers with `docker-compose up -d`

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
