ipv6Watch
=========
ipv6Watch is a small suite, capable of tracking and monitoring the ipv6 leases within your network using ipv6 neighborhood discovery via ssh from your routers.
The suite consists of a webfrontend, written in symfony2 and a client written in python.
For further details please read the <a href="docs/README.md" target="_blank">documentation</a>.

<b>Note:</b> This is an unstable WIP alpha-release! Most of the code is not working as expected yet, use it at your own risk! The code will be updated on a regular basis, so expect it to be more or less broken until we announce a stable release.

License
=======
The project itsself is licensed under <a href="LICENSE">GPLv3</a>.<br>
The used symfony2 framework and 3rd-party bundles use MIT or BSD licence.

Contributors
============
- <a href="https://github.com/dragonchaser">Christian Richter</a>
- <a href="https://github.com/danielniedermeyer">Daniel Niedermeyer</a>
- Benjamin Hiefner


Quickstart
==========
Clone the project
-----------------
Clone the project to a directory of your choice, make sure `webfrontend/web` can be accessed by your webserver (nginx, apache will work, alternativly you could use the build in webserver of symfony2 (run `webfrontend/app/console server:run`)).
```
git clone https://github.com/ipv6Watch/ipv6watch.git
```
Configure the database
----------------------
Setup the database of your choice (we support only postgres and mysql).
Edit `webfrontend/app/config/parameters.yml` and `client/pydemo/config.cfg` to suit your needs.

Setup database schema
---------------------
Run `webfrontend/app/console doctrine:schema:create`.

Create initial user
-------------------
Run `webfrontend/app/console ipv6watch:createuser`

If you want this User to be able to create other users you have to promote him to `ROLE_SUPER_ADMIN` using `webfrontend/app/console fos_user:promote`

Use the project
---------------

Navigate to to the webpage frontend using your webbrowser (e.g. `http://localhost/ipv6watch`) and setup your cron to run `client/pydemo/client.py`.