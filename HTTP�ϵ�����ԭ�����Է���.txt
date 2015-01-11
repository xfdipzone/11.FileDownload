HTTP断点续传原理
Http头 Range、Content-Range()
HTTP头中一般断点下载时才用到Range和Content-Range实体头，
Range用户请求头中，指定第一个字节的位置和最后一个字节的位置，如（Range：200-300）
Content-Range用于响应头
请求下载整个文件: 
GET /test.rar HTTP/1.1 
Connection: close 
Host: 116.1.219.219 
Range: bytes=0-801 //一般请求下载整个文件是bytes=0- 或不用这个头
一般正常回应 
HTTP/1.1 200 OK 
Content-Length: 801      
Content-Type: application/octet-stream 
Content-Range: bytes 0-800/801 //801:文件总大小



断点续传测试方法:
使用linux wget命令去测试下载, wget -c -O file http://xxx

1.先关闭断点续传
$flag = $obj->download($file, $name);

fdipzone@ubuntu:~/Downloads$ wget -O test.rar http://demo.fdipzone.com/demo.php
--2013-06-30 16:52:44--  http://demo.fdipzone.com/demo.php
正在解析主机 demo.fdipzone.com... 127.0.0.1
正在连接 demo.fdipzone.com|127.0.0.1|:80... 已连接。
已发出 HTTP 请求，正在等待回应... 200 OK
长度： 10445120 (10.0M) [application/octet-stream]
正在保存至: “test.rar”

30% [============================>                                                                     ] 3,146,580    513K/s  估时 14s
^C
fdipzone@ubuntu:~/Downloads$ wget -c -O test.rar http://demo.fdipzone.com/demo.php
--2013-06-30 16:52:57--  http://demo.fdipzone.com/demo.php
正在解析主机 demo.fdipzone.com... 127.0.0.1
正在连接 demo.fdipzone.com|127.0.0.1|:80... 已连接。
已发出 HTTP 请求，正在等待回应... 200 OK
长度： 10445120 (10.0M) [application/octet-stream]
正在保存至: “test.rar”

30% [============================>                                                                     ] 3,146,580    515K/s  估时 14s
^C

可以看到,wget -c不能断点续传

2.开启断点续传
$flag = $obj->download($file, $name, true);

fdipzone@ubuntu:~/Downloads$ wget -O test.rar http://demo.fdipzone.com/demo.php
--2013-06-30 16:53:19--  http://demo.fdipzone.com/demo.php
正在解析主机 demo.fdipzone.com... 127.0.0.1
正在连接 demo.fdipzone.com|127.0.0.1|:80... 已连接。
已发出 HTTP 请求，正在等待回应... 200 OK
长度： 10445120 (10.0M) [application/octet-stream]
正在保存至: “test.rar”

20% [==================>                                                                               ] 2,097,720    516K/s  估时 16s
^C
fdipzone@ubuntu:~/Downloads$ wget -c -O test.rar http://demo.fdipzone.com/demo.php
--2013-06-30 16:53:31--  http://demo.fdipzone.com/demo.php
正在解析主机 demo.fdipzone.com... 127.0.0.1
正在连接 demo.fdipzone.com|127.0.0.1|:80... 已连接。
已发出 HTTP 请求，正在等待回应... 206 Partial Content
长度： 10445121 (10.0M)，7822971 (7.5M) 字节剩余 [application/octet-stream]
正在保存至: “test.rar”

100%[++++++++++++++++++++++++=========================================================================>] 10,445,121   543K/s   花时 14s   

2013-06-30 16:53:45 (543 KB/s) - 已保存 “test.rar” [10445121/10445121])

可以看到会从断点的位置(%20)开始下载。