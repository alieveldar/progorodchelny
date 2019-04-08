var vtype=0; 
var itype=0;
var qid=0;
var oid=0;
$( document ).ready(on_tests_question());
function on_tests_question()
{
		JsHttpRequest.query('/modules/tests/Getqueries-JSReq.php',
		{'id':1},function(result,errors){ 
		console.log(result);
			$("#tests").html(result["text"]);
			vtype=result["type"];
			qid=result["qid"];
		},true);
}
function on_tests_answer_image(id){
	itype=id;
	$("#tests img").css({
	
        'border':'3px #CCCCCC solid', 
		 '-moz-border-radius': '10px', 
		 '-webkit-border-radius': '10px', 
		 '-khtml-border-radius':'10px',
		 'border-radius': '10px',
	     'padding':'1%','width':'95%'
	});
	$('.aimg'+id).css({'border':'3px solid #00a8e1'});
	}
	function on_tests_answer(id)
	{
	if (vtype==0) 
	{
	  ans = $("#tests_votes").val();
		if(ans)
		{
		TestsSend(ans,qid);
		}else{
			alert("Введите текст ответа!!");
		}
    }
    if (vtype==1)
     {
	   ans = itype;
	
		if(ans)
		{
		TestsSend(ans,qid)
		}
	
	else{
	  alert("Вы не выбрали ответ");
	}

}
if (vtype==2) 
{
  if($('.votes').filter(':checked').size()>0)
 {
   var myArr = [];
  $('.votes').filter(':checked').each(function(){
  ans = $(this).val();
  
 });
   TestsSend(ans,qid);
 }else{
    alert("Вы ничего не выбрали !");
 }
 }
if (vtype==3) 
{

var myArr = [];
 $('.votes').filter(':checked').each(function(){
 var i = $(this).val();
 myArr.push(i)// добавим в конец массива 
 });
 
 if(myArr.length>0)
 {
      rezult= myArr.join(",");
      TestsSend(rezult,qid);
	
 }else{
    alert("Вы ничего не выбрали !");
 }
}
}
function TestsSend(aid,qid)
{
JsHttpRequest.query('/modules/tests/VoteSave-JSReq.php',
	{'aid':aid,'qid':qid},function(result,errors){
		
	},true);

on_tests_question();

}