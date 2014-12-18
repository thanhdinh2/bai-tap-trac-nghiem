program dinhieu;
uses crt;
var a,b:integer;
C,S:real;
begin
clrscr;
writeln('Tinh chuvi va dientich hcn');
writeln('nhap vao chieu dai hinh chu nhat');
readln(a);
writeln('nhap vao chieu rong hinh chu nhat');
readln(b);
S:=a*b;
C:=(a+b)*2;
writeln('dien tich hinh chu nhat la:',S:4:2);
writeln('chu vi hinh chunhat la:',C:4:2);
readln;
end.

