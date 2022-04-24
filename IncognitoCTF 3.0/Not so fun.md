# IncognitoCTF 3.0 - Not So Fun
#### about : 
- Type: pwn 
- Level: easy 
- Points : 100

Descritpion of the challenge:
```
Usage: ./fun string

++kZSB4VJGi1/NJBdhYKC7BZNWgqOzLdTvlWHCGwpGP6F3U=
```

I opened the executable in ghidra and I found the encryption function. I "reversed" it in an unusual way with this python script:

```
from pwn import *

for ch in 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890_}?-{.:;#@*!/()=^%$&':
    p = process(['fun', 'ictf{'+ch])
    print(f"Trying {ch}")
    p.interactive()
```

Flag: ```ictf{Was_this_fun_879342332dsauhi$'}```
