# Share It

Share It is a PHP project to share private files.
The project uses the Orpheus PHP Framework: http://orpheus-framework.com/

Share It is also an open source example of advanced upload implementation using drag & drop, upload progress & speed.

## FAQ

##### How to disable registration

Open UserLoginController class and set `$allowRegister` to `false`.

##### How to restrict uploads to some users only

Open routes.yml and set all user_file_* routes' role to __moderator__.
Then in your admin panel, set the role of the users you want to __moderator__.

## About Us

Share It is an Open Source project of [Sowapps](http://sowapps.com/) Company, founded by Florent HAZARD.
