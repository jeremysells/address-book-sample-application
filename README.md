# Address Book Sample Application - By Jeremy Sells

Sample application with a focus on backend development.

## Requirements
* Persist Data On Disk
* Add a Person with the fields [name, contactDetails]
* Add an Organisation with the fields [name, contactDetails]
* List People and Organisations
* View People with [name, contactDetails] under an Organisation
* Add/Remove people from an organisation
* No authentication/authorization required

## Possible Additions/Changes
* Test on more platforms (Tested on Xubuntu)
* User dialogs
  * Delete successful
  * Are you sure you want to xyz?
* Navigation selection on menu
* Hard coded routes, these should use the reverse routes (from routes.php)
* Pagination
* Edge case error handling
* Build Server/CI
* Update to a view system
* Breadcrumbs
* The `name` field in the DB may benefit to having a unique index on it
* Test mobile and more browsers (mostly tested on Firefox)
* No limitation on the amount of contact details on the page. Might be an issue
* The contact details are a large text area/text block. This is not a good idea for production code.
* More unit tests

## Run
For portability this application uses Docker as a development environment.
Any errors go to standard out (`docker-compose logs -f app`)
1) Add hosts file entry for `address-book.local`
    e.g. On Ubuntu `nano /etc/hosts` and add `127.0.0.1    address-book.local`
2) `docker-compose up -d --build`
3) `docker-compose exec app bin/dev-install.sh`
4) http://address-book.local/
5) http://address-book.local/populate-test-data

## Test
All QA tests are in an Ant build.xml file.
To run them, run:
`docker-compose exec app ant`