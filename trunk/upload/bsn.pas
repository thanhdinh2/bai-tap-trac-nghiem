var fi,fo:text;
    n,i,x,y,c1,c2:longint;
    a,b:array[1..1000] of integer;
begin
    assign(fi,'bsn.inp'); reset(fi);
    assign(fo,'bsn.out'); rewrite(fo);
    readln(fi,n);
    for i:=1 to 2*n do readln(fi,a[i],b[i]);
    x:=0; y:=1; c1:=-1; c2:=0;
    while c1<>c2 do
    begin
        c1:=0; c2:=0;
        for i:=1 to n do
        if a[i]*x+b[i]*y>0 then inc(c1) else
        if a[i]*x+b[i]*y<0 then inc(c2) else
        begin c1:=-1; c2:=0; break; end;
        if c1>c2 then inc(x);
        if c1<c2 then inc(y);
    end;
    writeln(fo,x,#32,y);
    close(fi); close(fo);
end.