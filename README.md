# Viral Outbreak

Viral Outbreak is a problem-based learning game where students take the role of a public health scientist investigating an outbreak. They learn about virological techniques from expert videos, then actively apply that knowledge by collecting appropriate samples, performing the tests required to identify the virus, and reporting their findings.

The software is distributed under a GNU General Public License, see the LICENSE file. The content is licensed under the Creative Commons Attribution-NonCommercial-
ShareAlike 4.0 International License. To view a copy of this license, visit
http://creativecommons.org/licenses/by-nc-sa/4.0/.

## PHP Installation

Download [Composer](http://getcomposer.org/doc/00-intro.md) or update `composer self-update`.

From the root directory of the app, if Composer is installed globally, run
```bash
composer install
```

Or, if Composer is only installed locally run `php composer.phar install`.

## Configuration

Edit `config/app.php` to setup the Datasources, Email configuration and any other specific
configuration requirements.

### Database setup

Run the SQL in db_install.sql to create the database tables and insert records for the default sections, techniques, questions, schools, sample sites and stages, children, standards, etc. 

## LTI Setup

You will need to create a lti_keys record in order to create the first LTI 
link to the app. Necessary fields to complete at consumer_key, name, secret, 
set enabled to 1 and protected to 0.

### Installing as a LTI Tool Provider in a Tool Consumer

The launch URL will be: [App URL]/ltilogin

The key and secret will be as you have defined in the lti_keys table. The Tool Consumer should send names and email addresses, if you want to be able to track students progress and mark their reports. We recommend that Viral Outbreak is launched in a new window.

### LTI Permissions

A user's Permissions in Viral Outbreak will be derived from their permissions in the Tool Consumer. Users with Learner permissions will just be able to attempt the exercise. Users with Instructor or Administrator permissions will also be able to mark students attempts. 