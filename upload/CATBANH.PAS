Type rec=record x,y : real; end;
Var c : array[1..100] of rec;
    i,j : integer;
    n : byte;
Procedure Input;
 Var f : text;
     i : integer;
 Begin
  Assign(f,'CATBANH.INP'); Reset(f);
  Readln(f,n);
  For i:=1 to n*2 do
   Readln(f,c[i].x,c[i].y);
  Close(f);
 End;
Function KC2(a,b : integer;x,y : real): byte;
 Var d : real;
 Begin
  d:=a*x+b*y;
  If d>0 then kc2:=1 else kc2:=0;
 End;
Procedure Process;
 Var KT : boolean;
     dem1,dem2 : integer;
     k :byte;
 Begin
  For i:=-500 to 500 do
   For j:=-500 to 500 do
    Begin
     KT:=True; dem1:=0; dem2:=0;
     For k:=1 to n*2 do
      If KC2(i,j,c[k].x,c[k].y)=1 then inc(dem1) else inc(dem2);
     If dem1=dem2 then exit;
    End;
 End;
Procedure Output;
 Var f : text;
 Begin
  Assign(f,'CATBANH.OUT'); Rewrite(f);
  Writeln(f,i,' ',j);
  Close(f);
 End;
BEGIN
 Input;
 Process;
 Output;
END.

