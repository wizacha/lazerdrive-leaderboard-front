# lazerdrive-leaderboard-front
## What's this?
At Wizaplace, we often play at [lazerdrive.io](http://lazerdrive.io), we love this game :heart_eyes:

We decided to make a persisted leaderboard because the the score is lost when you close your game tab!
Before this project, one person of our team kept his tab always open to keep his 1st position!
This leaderboard is for you [Guillaume-Rossignol](https://github.com/Guillaume-Rossignol) :joy:

The project is deployed here: [lazerdrive.tk](http://lazerdrive.tk)

## Technical notes
### Installation
You just need to run `composer install`

The easiest way to test it:
```
composer install
./console db:fixtures
php -S 127.0.0.1:8000 -t web
```

Note: you will need [puli](http://docs.puli.io/en/latest/installation.html) to setup the project.

Note2: The project is based on PHP7.
### Database
The data in the database are provided by [lazerdrive-leaderboard-sniffer](https://github.com/vdechenaux/lazerdrive-leaderboard-sniffer)
