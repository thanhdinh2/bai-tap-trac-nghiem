program nhan;
uses crt;
var a,b,cv,dt: integer;
begin
clrscr;
writeln('nhap so a,b');
readln(a,b);
dt := a*b;
cv := (a+b)*2;
writeln('dien tich',dt);
writeln('chu vi',cv);
readln;
end.