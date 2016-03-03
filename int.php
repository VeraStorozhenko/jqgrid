<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<title>Grid</title>

<link rel="stylesheet" type="text/css" media="screen" href="jquery-ui-1.11.4.custom/jquery-ui.min.css" />
<link rel="stylesheet" type="text/css" media="screen" href="css/ui.jqgrid.css" />

<style>
html, body {
    margin: 0;
    padding: 0;
    font-size: 100%;
}
</style>

<script src="js/jquery-1.11.0.min.js"   type="text/javascript"></script>
<script src="js/i18n/grid.locale-en.js"  type="text/javascript"></script>

<script type="text/javascript">
    jQuery.jgrid.no_legacy_api = true; //После файла локали и перед JS-файлом jqGrid.
</script>

<script src="js/jquery.jqGrid.min.js" type="text/javascript"></script>

<script>
$(function () {
    var lastsel;
    $("#list").jqGrid({
        url: "getDataJson.php?q=2",
        datatype: "json",
        mtype: "GET",
        colNames: ["No", "Date", "Amount", "Tax", "Total", "Note"],
        colModel: [
            { name: "id", index:'id', width: 10, editable:true},
            { name: "date", index:'date', align: "center", editable:false},
            { name: "amount", index:'amount',  align: "center", editable:true },
            { name: "tax", index:'tax', align: "center", width: 30, editable:true },
            { name: "total", index:'total',  align: "center", width:50, editable:true },
            { name: "note", index:'note', width: 70, editable:true }
        ],
        pager: "#pager",
        rowNum: 2,
        rowList: [1, 2, 3, 4],
        sortname: "id", //Колонка, по которой будет происходить первоначальная сортировка.
        sortorder: "desc", //Направление сортировки
        viewrecords: true,
        gridview: true,
        autoencode: true,
        caption: "My first grid can add, edit and search!",
        editurl: 'getDataJson.php'
  });

    //$("#list").jqGrid('navGrid', '#pager', {edit:true, add:true, del:true, search:true});
    //$("#list").jqGrid('searchGrid', {sopt:['eq','lt','gt'], caption: "Search"}); //Какой скрипт вызывается? getDataJson.php
    
    jQuery("#list").jqGrid('navGrid',"#pager",{edit:false,add:false,del:false});
    jQuery("#list").jqGrid('inlineNav',"#pager");  
});

</script>
</head>
<body>
<!-- В теле должна быть обязательно таблица-->
<table id="list" border="1"></table> <!--Этот id нужен для обращения к элементам таблицы -->
<div id="pager"></div> <!--Этот id нужен для постраничной навигации -->
</body>
</html>

