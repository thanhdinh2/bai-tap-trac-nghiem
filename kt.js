	window.onbeforeunload = function() {
		//alert("good bye");
		if (typeof t == "number") {
			if (t>0) {
				$.post("caigio.php",{gio:t});
				alert("Bạn sẽ bị trừ 6 giây");
			}
		}
	}
	var hoi = new Array();
	var cactraloi=new Array();
	var c = 0;
	var cau;
	var pha= "";
	var myTimer = null;
	//var t = 300;//giay
	$(document).ready(function(){
		if (typeof sc !== 'undefined') {
			$(".cau:eq(0)").show();
			for (var i=1;i<=sc;i++) {
				$("#cauhoitraloi").append("<span class='cautraloi' id='cautraloi"+i+"'>"+i+":"+"*</span>");
				hoi[i-1]=0;
				cactraloi[i-1]='0';
			}
		}
		$(".phuongan").css("cursor","pointer");
		$(".phuongan").mouseenter(function(){
			$(this).css("background-color","#afff00");
		});
		$(".phuongan").mouseleave(function(){
			$(this).css("background-color","white");
		});
		$(".phuongan").click(function(){
			$(this).parent().find(".phuongan").css('color', 'black');
			$(this).css('color', 'red');
			var chon=$(this).find("span:eq(0)").text();
			$(this).parent().parent().find("div.chon:eq(0)").html("<i>Bạn chọn phương án "+chon+".</i>");
			if (thithu>=3)
			{
				$(this).parent().parent().find("div.chon:eq(0)").append(" <b>("+($(this).hasClass("padung")?"Đúng":"Sai")+")</b>");
				t+=300;
				if (!$(this).hasClass("padung")) 
					$(this).addClass("gachbo");
			}
			$("#cautraloi"+(c+1)).text((c+1)+":"+chon);
			hoi[c]=1;
			cactraloi[c]=$(this).attr("id").substr(2);
			$("#cautraloi"+(c+1)).addClass("dachon");
			if ($(".dachon").length>=sc) {
				$("#nopbai").show();
				$("#tuchuyen").prop( "checked", false );
			}
			//alert(c+chon);
			//if (typeof cau == 'undefined') cau = Array(sc).join("-");
			cau = (c>0?cau.substr(0,c):"")+chon+cau.substr(c+1);
			pha+=$(this).attr("id");
			if ($("#tuchuyen").is(':checked') && (thithu<3))
				$("#causau").click();
		});
		$(".cautraloi").css("cursor","pointer");
		$(".cautraloi").click(function(){
			//alert($(this).text());
			var cid = $(this).attr("id");
			cid=cid.replace("cautraloi","");
			//alert(cid);
			$(".cau:eq("+c+")").hide();
			c=parseInt(cid)-1;
			if ($(".cau:eq("+c+")").length<=0) c=0;
			$(".cau:eq("+c+")").show();
			codangxem();
		});
		$("#causau").click(function(){
			$(".cau:eq("+c+")").hide();
			c++;
			if ($(".cau:eq("+c+")").length<=0) c=0;
			$(".cau:eq("+c+")").show();
			codangxem();
		});
		$("#cautruoc").click(function(){
			$(".cau:eq("+c+")").hide();
			c--;
			if ($(".cau:eq("+c+")").length<=0) c=$(".cau").length-1;
			$(".cau:eq("+c+")").show();
			codangxem();
		});
		$("#batdau").click(function(){
			//alert("OK");
			if (typeof cau == 'undefined') cau = Array(sc).join("-");
			$("#baikiemtra").show();
			$("#khuvucthi").show();
			if (thithu>=3) $("#thongtin").hide();
			$(this).parent().hide();
			$.post("batdau.php");
			if (!myTimer) tinhgio();
			codangxem();
			
		});
		$("#nopbai").hide();
		$("#nopbai").click(function(){
			//nopbai();
			var date_begin = new Date();
			if (confirm("Bạn chắc chắn muốn nộp bài?\nBài làm sẽ được chấm điểm ngay và không thể sửa trả lời được nữa.")) {
				t=0;
				nopbai();
			}
			var date_end = new Date();
			//alert((date_end-date_begin)/1000);
			t= t-Math.round((date_end-date_begin)/1000);
			//t=0;
		});
		setTimeout(ancanhbao,8000);
		if (typeof lamtudau != "undefined") {
			if (!lamtudau) tinhgio();
		}
		//if ($("#hovaten").length>0) 
		//$("#hovaten").focus();
	});
	function codangxem() {
		$(".cautraloi").removeClass("dangxem");
		$("#cautraloi"+(c+1)).addClass("dangxem");
	}
	function ancanhbao() {
		$("#canhbao").hide();
	}
	function tinhgio() {
		t--;
		if (t>0) {
			$("#thoigian").text(~~(t/60) + ":" + (t%60<10?"0":"")+t%60);
			myTimer = window.setTimeout(tinhgio,1000);
			//if (t%2==0) $.post("caigio.php",{gio:t});
			if (t==59) $("#thoigian").addClass("maudo");
		}
		else {
			$("#thoigian").text("00:00");
			nopbai();
		}
	}
	function nopbai() {
		//alert(cau);
		$("#baikiemtra").hide();
		$.post("caigio.php");
		//delete t;
		setTimeout(chuyentrang,1000);
		
	}
	function chuyentrang() {
		var ph="";
		var scd = 0;
		var ketqua="";
		for (var i=0; i<sc; i++) {
			ph = String.fromCharCode(i+65)+""+(cau.charCodeAt(i)-63);
			if (dap.indexOf(ph)>=0) {
				scd++;
				ketqua+=(i+1)+" ";
			}
		}
		//alert(scd);
		//alert(pha);
		pha=cactraloi.join(",");
		t=0;
		if (thithu>=2) 
		{
			alert("Các câu làm đúng: "+ketqua);
			$("#baikiemtra").show();
			$("#nopbai").hide();
			$("body").append("<hr/><a href='thoat.php'>Thoát</a><br/>");
		}
		else
			window.location.href+="&kq="+scd+"&fb="+pha;
	}