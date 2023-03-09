# systemd config
We use Systemd Path Monitor to launch our unit. 


File: /etc/systemd/system/mySimpleQueue.path

    [Unit]
    Description="Monitor for changes in Queue Inbox Path"

    [Path]
    DirectoryNotEmpty=<THE_DIR_TO_INBOX>
    Unit=myfileworker.service

    [Install]
    WantedBy=multi-user.target
---

File: /etc/systemd/system/mySimpleQueue.service

    [Unit]
    Description="Worker Starter for the Queue/Job handling
    
    [Service]
    Type=simple
    ExecStart=/usr/bin/php <PATH_TO_SingleWorker.php>
    
    [Install]
    WantedBy=multi-user.target

