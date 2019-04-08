CKEDITOR.editorConfig=function(config) {
	config.stylesSet = 'ProCms';
	config.height = 250;
	config.font_defaultLabel = 'Tahoma';
	config.fontSize_sizes='12/12px;14/14px;32/32px';
    config.disableNativeSpellChecker = false;
	config.toolbar_Full=[
		{ name: 'document',		items : [ 'Source','-','RemoveFormat','PasteText','Undo'] },
		{ name: 'basicstyles',	items : [ 'Bold','Superscript']},{ name:'styles', items:['Styles']},
		{ name: 'paragraph',	items : [ 'NumberedList','BulletedList','-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock']},
		{ name: 'insert',		items : [ 'Link','Unlink','Image','Table','SpecialChar'] }
	];
};

CKEDITOR.stylesSet.add('ProCms',[
	{ name:'Заголовок', element:'h2', attributes:{'class':'h2', 'style':'font-size:18px; font-weight:bold;'}},
	{ name:'Цитата в тексте', element:'p', attributes:{'class':'TextQuot', 'style':'padding-left:10px; border-left:1px solid #DDD;'}},
	{ name:'Факты, справки', element:'p', attributes:{'class':'ItemFact', 'style':'border-top:1px solid #DDD; border-bottom:1px solid #DDD; padding:10px; background:rgba(0,0,0,0.03);'}},
	{ name:'Мысль, идея', element:'p', attributes:{'class':'ItemIdea', 'style':'text-align:center; font-size:18px; border-top:1px solid #DDD; border-bottom:1px solid #DDD; padding:10px; background:rgba(0,0,0,0.03);'}},
	{ name:'ФАС', element:'div', attributes:{'class':'TextFAS', 'style':'margin:15px 0; font-weight:normal; font-size:24px; text-align:center; text-transform:uppercase; line-height:30px; color:#999; letter-spacing:-1px;'}},
]);