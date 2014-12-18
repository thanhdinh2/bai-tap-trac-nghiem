program CT;
uses crt;
const max=100;
var A,B,C,l:string;
    t:array[0..max,0..max] of integer;
    i,j,n,m,thu:integer;
procedure docdulieu;
var f:text;
begin
        assign(f, 'D:\input.inp');
        reset(f);
        readln(f, A);
        readln(f,B);
        readln(f,c);
        close(f);
        writeln('3 xau nhap vao la: ');
        writeln(a);
        writeln(b);
        writeln(c);
end;

procedure xuli(a,b:string);
begin
for i:=0 to length(a) do t[i,0]:=0;
for j:=1 to length(b) do t[0,j]:=0;
for i:=1 to length(a) do
for j:=1 to length(b) do
if a[i]=b[j] then t[i,j]:=t[i-1,j-1]+1 else
         if (t[i-1,j]>t[i,j-1]) then t[i,j]:=t[i-1,j]
         else t[i,j]:=t[i,j-1];
end;

function truylung(i,j:integer;a,b:string):integer;
begin
thu:=t[i,j];
while thu>0 do
begin
if a[i]=b[j] then
                        begin
                                l:=l+a[i];
                                truylung(i-1,j-1,a,b);

                        end
                        else if (t[i-1,j]>=t[i,j-1]) then truylung(i-1,j,a,b)
                                else truylung(i,j-1,a,b);
end;
end;
begin
l:='';
clrscr;
docdulieu;
xuli(a,b);
truylung(length(a),length(b),a,b);
b:=l;
l:='';
xuli(a,c);
truylung(length(a),length(c),a,c);
a:=l;
l:='';
xuli(a,b);
truylung(length(a),length(b),a,b);
writeln(t[length(a),length(b)]);
write(l);
readln;
end.
