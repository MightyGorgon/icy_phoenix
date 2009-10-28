<script type="text/javascript">
<!--
function mpFoto(img)
{
	foto1= new Image();
	foto1.src=(img);
	mpControl(img);
}

function mpControl(img)
{
	if((foto1.width!=0)&&(foto1.height!=0))
	{
		viewFoto(img);
	}
	else
	{
		mpFunc="mpControl('"+img+"')";
		intervallo=setTimeout(mpFunc,20);
	}
}

function viewFoto(img)
{
	largh=foto1.width+20;
	altez=foto1.height+20;
	string="width="+largh+",height="+altez;
	finestra=window.open(img,"",string);
}

function MM_jumpMenu(targ,selObj,restore)
{
	//v3.0
	eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
	if (restore)
	{
		selObj.selectedIndex=0;
	}
}

function delete_file(theURL)
{
	if (confirm('Are you sure you want to delete this file??'))
	{
		window.location.href=theURL;
	}
	else
	{
		alert ('No Action has been taken.');
	}
}

//-->
</script>