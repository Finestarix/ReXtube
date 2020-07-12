# ReXtube

Simple streaming platform website made with PHP.

### Installation

- Open Command Prompt (on Windows) or Terminal (on MacOS/Linux) in specified folder. (e.g. D:/)
- Type "git clone https://github.com/finestarix/ReXtube" and wait until the installation is completed.
- Open Command Prompt (on Windows) or Terminal (on MacOS/Linux) in project directory (e.g. D:/ReXtube).
- Type "composer update" and wait until the installation is completed.
- Create .env file in project directory (e.g. D:/ReXtube/.env) as following:
```
#Database Config
HOST="localhost"
PORT="3306"
USERNAME="root"
PASSWORD=""
DATABASE_NAME="phph3project"

#Google Sign In Config
CLIENT_ID=""
CLIENT_SECRET=""
```
- Install XAMPP and open it.
- In Apache row, select config tab and choose "Apache (httpd.conf)".
- Change DocumentRoot to project directory (e.g. D:/ReXtube).
- Start Apache and MySQL in XAMPP.
- Open browser and type URL http://localhost:[Port]/initialize.

## Authors

* **Renaldy (RX19-2)** - [Finestarix](https://github.com/Finestarix)

See also the list of [contributors](https://github.com/Finestarix/ReXtube/contributors) who participated in this project.
