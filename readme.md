# systemd config
We use Systemd Path Monitor to launch our unit. 


File: /etc/systemd/system/mySimpleQueue.path

    [Unit]
    Description="Monitor for changes in /some/path"

    [Path]
    DirectoryNotEmpty=/home/tillmann/Projects/simple-queue/queue/inbox
    Unit=myfileworker.service

    [Service]
    Restart=on-failure
    RestartSec=5s
    
    [Install]
    WantedBy=multi-user.target
---

File: /etc/systemd/system/mySimpleQueue.service

    [Unit]
    Description=Foo
    
    [Service]
    Type=simple
    ExecStart=/usr/bin/php /home/tillmann/Projects/simple-queue/src/worker.php
    Restart=on-failure
    RestartSec=5s
    
    [Install]
    WantedBy=multi-user.target

