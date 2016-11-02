<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
if(Yii::app()->user->checkAccess(ROLE_ADMIN)){
    echo "hello, I'm administrator";
}
?>
<div class="wrapselect">    
    <select class="address" id="Cities" data-child="Street" data-placeholder="Выберете город"></select>     
    <button type="button" id="RemoveCities" title="Удалить" class="btn btn-danger btn-xs select-item-remove" disabled><span class="glyphicon glyphicon-remove"></span></button>
    <button type="button" id="EditCities" title="Именить" class="btn btn-primary btn-xs select-item-edit" disabled><span class="glyphicon glyphicon-pencil"></span></button>
</div>
<div class="wrapselect">
    <select class="address" id="Street" data-child="House" data-placeholder="Выберете улицу" disabled></select>
    <button type="button" id="RemoveStreet" title="Удалить" class="btn btn-danger btn-xs select-item-remove" disabled><span class="glyphicon glyphicon-remove"></span></button>
    <button type="button" id="EditStreet" title="Именить" class="btn btn-primary btn-xs select-item-edit" disabled><span class="glyphicon glyphicon-pencil"></span></button>
</div>
<div class="wrapselect">
    <select class="address" id="House" data-placeholder="Выберете дом" disabled></select>
    <button type="button" id="RemoveHouse" title="Удалить" class="btn btn-danger btn-xs select-item-remove" disabled><span class="glyphicon glyphicon-remove"></span></button>
    <button type="button" id="EditHouse" title="Именить" class="btn btn-primary btn-xs select-item-edit" disabled><span class="glyphicon glyphicon-pencil"></span></button>
</div>
<div>
    <input id="TextOnMap" type="text" placeholder="Текст для отображения на карте" style="width: 230px;" disabled/>
    <button type="button" id="AddTextOnMap" title="Да" class="btn btn-success btn-xs select-item-textonmap" disabled><span class="glyphicon glyphicon-ok"></span></button>
</div>
<div id="dialog-confirm" title="Удалить?" style="display: none;">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Выбранный пункт будет удалён. Продолжить?</p>
</div>
<div id="dialog-update" title="Заменить?" style="display: none;">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>Заменить "<span id="changeword"></span>" на ?</p>
  <p><input id="replacetext" type="text" /></p>
</div>
<script>
    
    function CurrentSelect() {              
        // сохраненный экземпляр
        var instance = this;
       
       CurrentSelect = function () {           
            return instance;
        };
            instance.CurrentTerm = ''; 
            instance.isResult = false;
            instance.CurrentId = '';
            instance.preload_data = [];
        return instance;
    }
        
    function addterm(){    
    var id_parent;
    cs = new CurrentSelect();   
    if ((cs.CurrentTerm !=='')&&(cs.CurrentId !=='')) {   
        
        if(cs.CurrentId == 'Street') {
                        id_parent = $('#Cities').select2('val');
                    } else {
                        if(cs.CurrentId == 'House') {
                            id_parent = $('#Street').select2('val');
                        } else {
                            id_parent = 0;
                        }    
                    }; 
            
        $.get('/add',
                                {'term': cs.CurrentTerm, 'modelname': cs.CurrentId, 'id_parent': id_parent},
                                function(data) {                                   
                                    var $element = $('#'+cs.CurrentId);                                   
                                    var option = new Option(data['text'], data['id'], true, true);                                     
                                    $element.append(option);                                       
                                }, "json"
                            );  
        }
        
    }

  
     


    $(function(){ 
    $.fn.select2.amd.define('CustomDataAdapter',[
    'select2/data/array',
    'select2/utils'
], function (ArrayData, Utils) {
    function CustomDataAdapter ($element, options) {
        CustomDataAdapter.__super__.constructor.call(this, $element, options);
        console.log("HERPDERP",$element, options);
    }

    Utils.Extend(CustomDataAdapter, ArrayData);

    CustomDataAdapter.prototype.current = function (callback) {
        console.log("HERPDERP2");
               
    
        callback(data);
    };

   return CustomDataAdapter;
});
    $('.addres').select2({
        width: '230px',
            dropdownAutoWidth: false,
            
  });
  
    $(".select-item-edit").click(function (e) {
      var id = e.currentTarget.id; 
      var modelname = id.substr(4);
      var id_row = $('#'+modelname).select2('val');
      var txt = $('#'+modelname +' option:selected').text();      
      if (id_row >0) {
      $( "#changeword" ).text(txt);    
      $( "#dialog-update" ).dialog({
      resizable: false,
      height: "auto",
      width: 300,
      modal: true,
      buttons: {
        "Да": function() {
        $.get('/admin/locations/update',
                                {'modelname': modelname, 'id': id_row, 'text': $( "#replacetext" ).val()},
                                function(data) {                                      
                                    if (data.cod === 1) {                                       
                                        cs = new CurrentSelect();
                                        cs.CurrentTerm = data.text; 
                                        $('#'+data.name).html('<option>'+data.text+'</option>').change(); 
                                        $('#'+data.name +' option:selected').text(data.text);
                                        $('#'+data.name +' option:selected').val(data.id);
                                        }
                                    }, "json"
                                    );
                            $(this).dialog("close");
                        },
                        "Нет": function () {
                            $(this).dialog("close");
                        }
                    }
                });
            }
        });
        
       
        
        $(".select-item-remove").click(function (e) {
            var id = e.currentTarget.id;
            var modelname = id.substr(6);
            var id_row = $('#' + modelname).select2('val');
            if (id_row > 0)
                $("#dialog-confirm").dialog({
                    resizable: false,
                    height: "auto",
                    width: 400,
                    modal: true,
                    buttons: {
                        "Да": function () {

                            $.get('/admin/locations/remove',
                                    {'modelname': modelname, 'id': id_row},
                                    function (data) {
                                        if (data.cod === 1) {
                                            $("#" + data.name).val("").trigger("change");
                                        }
                                    }, "json"
                                    );
                            $(this).dialog("close");
                        },
                        "Нет": function () {
                            $(this).dialog("close");
                        }
                    }
                });
        });

        $(".address").on("select2:opening", function (e) {
            var cs = new CurrentSelect();
            cs.CurrentId = this.id;            
            var id_parent;
            if (cs.CurrentId == 'Street') {
                        id_parent = $('#Cities').select2('val');
                    } else {
                        if (cs.CurrentId == 'House') {
                            id_parent = $('#Street').select2('val');
                        } else {
                            id_parent = 0;
                        }
                    };
                   
            
        });
        $(".address").on("select2:closing", function (e) {
            cs = new CurrentSelect();
            cs.CurrentId = '';
        });
        
        $("#AddTextOnMap").click(function (e) {
            var id = $('#House').val();
            var text = $('#TextOnMap').val();            
            $.get('/admin/locations/settextonmap',
                                    {'id': id, 'text': text},
                                    function (data) {
                                        if (data.cod == 1) {                                            
                                            alert(data.onmap);
                                        }
                                    }, "json"
               );
        });
        
        $(".address").on("select2:select", function (e) {
            var id = e.currentTarget.id;           
            $("#Remove" + id).removeAttr("disabled");
            $("#Edit" + id).removeAttr("disabled");

            var child = $(this).data('child');
            if (child !== null) {
                $("#" + child).val("").trigger("change");
                $("#" + child).prop("disabled", false);                 
                child = $("#" + child).data('child');
                $("#" + child).val("").trigger("change");
                $("#" + child).prop("disabled", true);                
            }
            if (id == 'House') {
               $("#TextOnMap").removeAttr("disabled");
               $("#AddTextOnMap").removeAttr("disabled");              
               
               $.get('/admin/locations/gettextonmap',
                                    {'id': $(this).val()},
                                    function (data) {
                                        if (data.cod == 1) 
                                             $('#TextOnMap').val(data.onmap)                                            
                                        else $('#TextOnMap').val('');
                                    }, "json"
               );               
            } else {
                $('#TextOnMap').val('');
                $("#TextOnMap").prop("disabled", "disabled");
            }
        });

        $(".address").on("select2:unselect", function (e) {

            var id = e.currentTarget.id;
            $("#Remove" + id).prop("disabled", "disabled");
            $("#Edit" + id).prop("disabled", "disabled");

            var child = $(this).data('child');
            if (child !== null) {
                $("#" + child).val("").trigger("change");
                $("#" + child).prop("disabled", true);
                $("#Remove" + child).prop("disabled", "disabled");
                $("#Edit" + child).prop("disabled", "disabled");
                child = $("#" + child).data('child');
                $("#" + child).val("").trigger("change");
                $("#" + child).prop("disabled", true);
                $("#Remove" + child).prop("disabled", "disabled");
                $("#Edit" + child).prop("disabled", "disabled");
            }            
               $("#TextOnMap").val(""); 
               $("#TextOnMap").prop("disabled", "disabled");
               $("#AddTextOnMap").prop("disabled", "disabled");           
        });

       $(".address").select2({
            allowClear: true,
            width: '230px',
            dropdownAutoWidth: false,            
            language: "ru",                    
            ajax: {
                url: '/admin/locations/select',
                dataType: 'json',
                processResults: function (data, params) {
                    cs = new CurrentSelect();
                    if (data == '') {
                        cs.isResult = true;
                    }
                    return {results: data};
                },
                data: function (options) { // page is the one-based page number tracked by Select2
                    var id_parent, term; //, page;
                    cs = new CurrentSelect();
                    
                    if (cs.CurrentId == 'Street') {
                        id_parent = $('#Cities').select2('val');
                    } else {
                        if (cs.CurrentId == 'House') {
                            id_parent = $('#Street').select2('val');
                        } else {
                            id_parent = 0;
                        }
                    }
                    if (typeof options.term !== "undefined")
                    {
                         options.term;
                         term = options.term;
                         cs.CurrentTerm = options.term;
                         //page = options.page;
                    } else {
                         cs.CurrentTerm = '';
                         term = '';
                         //page = 0;
                    }                    
                    
                    return {
                        q: term, //search term
                        //page: page, // page number
                        idp: id_parent,
                        modelname: cs.CurrentId,
                    };
                },
            },
            escapeMarkup: function (markup) {
                cs = new CurrentSelect();

                if (cs.isResult == true) {

                    cs.isResult = false;
                    return '<button type="button" class="btn btn-success" onclick="addterm()">добавить</button>'
                } else {
                    return markup;
                }
            },
            dropdownCssClass: "bigdrop", // apply css that makes the dropdown taller

        });        
               
    });
</script>
