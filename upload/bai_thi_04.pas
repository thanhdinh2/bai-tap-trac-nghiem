PROGRAM DEM_SO_MAT_HANG_GIONG_NHAU;
USES crt;
VAR nguoi:ARRAY[1..100000,1..1000] OF INTEGER;
    n,m:longint;

PROCEDURE nhap;
VAR f:TEXT;  i,j:longint;
BEGIN
        assign(f,'nguoi.inp');
        reset(f);
        readln(f,n,m);
        fillchar(nguoi,sizeof(nguoi),0);
        FOR i:=1 TO m DO
                BEGIN
                        j:=0;
                        WHILE NOT eoln(f) DO
                                BEGIN
                                        inc(j);
                                        read(f,nguoi[i,j]);
                                END;
                        readln(f);
                END;
        FOR i:=1 TO m DO
                BEGIN
                        FOR j:=1 TO n DO
                                IF nguoi[i,j]<>0 THEN
                                        write(nguoi[i,j],' ');
                        writeln;
                END;
        close(f);
END;

procedure xu_li;
var  k,i,j,s:longint; kt:boolean;
     hang:array[1..1000] of integer;
begin
        k:=0;
        for i:=1 to n do
                if nguoi[1,i]<>0 then
                        begin
                                j:=2;

                                while j<=m do
                                   begin
                                        kt:=false;
                                        for s:=1 to n do
                                                if (nguoi[j,s]<>0)and(nguoi[j,s]=nguoi[1,i]) then
                                                        begin
                                                                kt:=true;
                                                                break;
                                                        end;
                                        if kt then
                                                inc(j)
                                        else
                                                break;
                                   end;
                                if kt then
                                        begin
                                                inc(k);
                                                hang[k]:=nguoi[1,i];
                                        end;
                        end;
        write('so mat hang chung:',k,': ');
        for i:=1 to k do
                write(hang[i],' ');
end;

BEGIN
        clrscr;
        nhap;
        xu_li;
        readln;
END.