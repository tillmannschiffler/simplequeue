# Simple Queue #
Pragmatic approach to asyncron jobs for PHP8.x

## Idea ##
This PHP project is for all the developers who want a queue system without installing huge dependencies. This project is heavily inspired by the lecture [Daemons with PHP](https://www.youtube.com/watch?v=VIEqYmeJcMo) by Arne Blankerts from [thePHP.cc](https://thephp.cc/willkommen)   

If you come to a point in your project that needs asynchronous jobs, you often tend to use big solutions like RabbitMQ or other systems. After realizing that such services have to be maintained and updated, you may search for simpler solutions like the queue package from the Laravel framework. A quick PHPloc will give you something like this:
    
    ./tools/phploc vendor/illuminate/

    Directories                                         96
    Files                                              653

    Size
    Lines of Code (LOC)                           108002

Thats right ... 650+ Files with over 100k Lines of code.

Another Example from the "enqueue" package after require enqueue via composer:

    Installing doctrine/cache
    Installing doctrine/collections
    Installing doctrine/event
    Installing symfony/polyfill
    Installing ramsey/collection
    Installing brick/math
    Installing ramsey/uuid
    Installing queue-interop
    Installing psr/cache
    Installing doctrine/persistence
    Installing doctrine/dbal
    Installing enqueue/dbal

These are huge dependency's and have to be maintained. Here comes this package that aims to be as small as possible but deliver the important features:
- Asynchronous jobs.
- No accidentally multiple executions of one job.
- parallel jobs with max number of workers
- event driven

All this features could be archived by using the most basic storage aka files system with some leverage of systemd.

Systemd uses unit files to configure daemons, and path units monitor files and directories for events. When a specified event occurs, a service unit with the same name is executed. I'll demonstrate this with an example. So the concept is to create files in a specific directory and let systemd handle the starting event condition and keep that one unit running to avoid accidentally multiple executions of one job. 

## Requirements ##
- Linux operating system with systemd
- The minimum required PHP version is PHP 8.0
 
## Installation ##
#### Step 1/2 ###

To use this library install with:

```bash
composer require tillmannschiffler/simplequeue
```

To get started with this system, you can begin by making changes to the example files located at /src/Example. These files can be modified to suit your specific needs. For instance, you can customize the Sample Processor according to your desired functionality.

---

#### Step 2/2 ###
Setup systemd config file like this example

File: /etc/systemd/system/SimpleQueue.path

    [Unit]
    Description="Monitor for changes in Queue Inbox Path"

    [Path]
    DirectoryNotEmpty=<THE_DIR_TO_INBOX>
    Unit=SimpleQueue.service

    [Install]
    WantedBy=multi-user.target

File: /etc/systemd/system/SimpleQueue.service

    [Unit]
    Description="Worker Starter for the Queue/Job handling
    
    [Service]
    Type=simple
    ExecStart=/usr/bin/php <PATH_TO_YOUR_WORKER.php>
    
    [Install]
    WantedBy=multi-user.target

Remeber to reload the daemon and enable these 2 unit to get it to start on boot:

    systemctl daemon-reload
    systemctl enable SimpleQueue.path SimpleQueue.service
    
And of course start it:
    
    systemctl start SimpleQueue.path SimpleQueue.service


## Running ##
To create a job, you have two options: you can either create the job manually and move it to /queue/inbox, or you can make use of the helper file located at /src/Example/CreateSomeJobs.php. This file can help you generate jobs quickly and easily.

Once you have created the job, and  you can proceed to test the system by running /src/Example/SingleWorker.php. This will help you ensure that the setup is working as intended. If there are any issues, you can debug the system accordingly. With this simple setup process, you can get started with using this system in no time at all.

Please feel free to mail me with ideas or bugs you found <tillmann.schiffler@gmail.com>

## Monitoring ##
Since the whole idea to use the file system as backend storage for our jobs. The monitoring is as easy as it sounds. 

Get a count of jobs just count the files in the corresponding directorys like:  
 
    ls | wc -l

in the inbox directory and so on.

Since this project is using systemd as leverage we can use journal log to optain statistics per time range:

    journalctl -u SimpleQueue.path --since "1 minute ago" | grep "Started SimpleQueue.path" | wc -l

To get a throughput of jobs there is also a simple and pragmatic method - use the event subscriber on the "emitFinishedJob" event and write that event to journal of systemd. After that you can use the command above to determine the jobs done per time range. 