$.picker = (function(){
	
	if (typeof Handlebars !== 'object') {
		console.error("Handlebars is required.");
	}
	
	return function(_options){

		return new (function(options){
			
			var _html;

			if (!options.url) {
				switch (options.type) {
					case 'person':
					options = $.extend({
						columns:[{
							title:"Id",
							field:"idperson"
						},{
							title:"Nome",
							field:"desperson"
						},{
							title:"E-mail",
							field:"desemail"
						},{
							title:"CPF",
							field:"descpf"
						}],
						filters:[{
							type:"text",
							label:"Id",
							field:"idperson"
						},{
							type:"text",
							label:"Busca",
							field:"desperson",
							placeholder:"Nome, e-mail, cpf ou etc..."
						}],
						url:'/persons',
						title:'Selecionar Pessoas...',
						itemTPL:Handlebars.compile(
							'<li class="list-group-item" data-valu="{{idpessoa}}">' +
								'<div class="media">' +
									'<div class="media-left">' +
										'<a class="avatar" href="javascript:void(0)">' +
											'<img class="img-responsive" src="{{desfoto}}" alt="{{despessoa}}">' +
										'</a>' +
									'</div>' +
									'<div class="media-body">' +
										'<h4 class="media-heading">{{despessoa}}</h4>' +
									'</div>' +
								'</div>' +
							'</li>'
						)
					}, options);
					break;
				}	
			}
			
			var id = 'id-'+new Date().getTime();

			var t = this, defaults = {
				title:"Selecionar...",
				debug:false,
				cache:true,
				valueField:'',
				displayField:'',
				url:'',
				multiple:false,
				select:function(objects){

				},
				itemTPL:Handlebars.compile(
					'<li class="list-group-item active" data-value="{{valueField}}">{{displayField}}</li>'
				),
				tpl:Handlebars.compile(
					'<div class="panel panel-primary panel-line is-fullscreen" id="panel-picker">'+
			            '<div class="panel-heading">'+
			            	'<h3 class="panel-title">{{title}}</h3>'+
			            	'<div class="panel-actions panel-actions-keep">'+
				                '<a class="panel-action icon md-close" aria-hidden="true" id="btn-picker-close"></a>'+
				            '</div>'+
			            '</div>'+
			            '<div class="panel-body p-y-10 p-x-0" style="height:{{height}}px; overflow:auto; background: #f1f4f5;">'+
			             	'<div class="row-fluid">'+
			             		'<div class="col-md-3">'+
			             			'<div class="panel">'+
							            '<div class="panel-body">'+
							            	'<form id="form-picker-filters">'+
							              		'{{{filters}}}'+
							              		'<button type="submit" class="btn btn-primary btn-block waves-effect">Buscar</button>'+
							              	'</form>'+
							            '</div>'+
							        '</div>'+
			             		'</div>'+
			             		'<div class="col-md-9">'+
			             			'<div class="panel">'+
							            '<div class="panel-body">'+
							              '<table class="table table-hover" data-plugin="selectable" data-row-selectable="true">'+
						                    '<thead>'+
						                      '<tr>'+
						                      '</tr>'+
						                    '</thead>'+
						                    '<tbody>'+					                      
						                    '</tbody>'+
						                  '</table>'+
							            '</div>'+
							        '</div>'+
			             		'</div>'+
			             	'</div>'+
			            '</div>'+
			            '<div class="panel-footer" style="padding:20px;">'+
			             	'<button type="button" id="btn-picker-select" class="btn btn-primary waves-effect pull-xs-right">Selecionar</button>'+
			             	'<button type="button" id="btn-picker-cancel" class="btn btn-default waves-effect pull-xs-left">Cancelar</button>'+
			            '</div>'+
			        '</div>'
				),
				tplColumn:Handlebars.compile(
					'<th>{{title}}</th>'
				),
				tplColumnCheck:Handlebars.compile(
					'<th class="w-50">'+
                      '<span class="checkbox-custom checkbox-primary">'+
                        '<input class="selectable-all" type="checkbox">'+
                        '<label></label>'+
                      '</span>'+
                    '</th>'
				),
				tplResult:Handlebars.compile(
					'<td>{{value}}</td>'
				),
				tplResultCheck:Handlebars.compile(
					'<td>'+
                      '<span class="checkbox-custom checkbox-primary">'+
                        '<input class="selectable-item" type="checkbox" id="row-619" value="619">'+
                        '<label for="row-619"></label>'+
                      '</span>'+
                    '</td>'
				),
				tplFilter:{
					text:Handlebars.compile(
						'<div class="form-group form-material" data-plugin="formMaterial">'+
							'<label class="form-control-label" for="{{field}}">{{label}}</label>'+
							'<input type="text" class="form-control" name="{{field}}" id="{{field}}" placeholder="{{placeholder}}">'+
						'</div>'
					)
				}
			};

			var o =  $.extend(defaults, options);

			t.getFiltersHTML = function(){

				var html = '';

				$.each(o.filters, function (index, filter) {

					html += o.tplFilter[filter.type](filter);

				});

				return html;

			};

			t.callRest = function(){

				var $form = _html.find("#form-picker-filters");
				var params = $form.formValues();
				var valid = false;

				for (var key in params) {
					if (params[key].length > 0) valid = true;
				}

				if (valid) {

					rest({
						cache:o.cache,
						url:o.url,
						method:o.method,
						data:params,
						success:function(r){
							t.setResult(r.data);
						},
						failure:function(e){
							t.showError(e);
						}
					});

				} else {

					t.showError({
						error:"Preencha algum campo para fazer a busca"
					});

				}				

			};

			t.showError = function(e){

				console.error(e);

			};

			t.setResult = function(result){

				var _resultHTML = [];

				if (result.length > 0) {


					$.each(result, function (index2, row) {

						var $tr = $("<tr>"+o.tplResultCheck({})+"</tr>");

						$tr.data("object", row);

						$.each(o.columns, function (index1, column) {

							var $td = $(o.tplResult({
								value:row[column.field]
							}));

							$tr.append($td);

						});

						_resultHTML.push($tr);

					});

				}

				_html.find("tbody").append(_resultHTML);

				_html.find("tbody").find("tr").on("click", function (e) {

					if (e.target.tagName !== 'INPUT') {
						var $input = $(this).find("input:checkbox");
						$input.prop("checked", !$input.prop("checked"));
						$input.trigger("change");
					}

				});

				_html.find("tbody").find("tr").find("input:checkbox").on("change", function () {

					var checked = $(this).prop("checked");
					var $tr = $(this).closest("tr");

					if (checked) {
						$tr.addClass("table-active");
					} else {
						$tr.removeClass("table-active");
					}

				});

			};

			t.select = function(){

				var itens = [];

				$("#panel-picker tbody input:checked").each(function () {

					itens.push($(this).closest("tr").data("object"));

				});

				o.select(itens);
				t.close();

			};

			t.initForm = function(){

				var $form = _html.find("#form-picker-filters");

				$form.find("[type=submit]").on("click", function(e){

				    e.preventDefault();

				    //$('.page-aside-switch:visible').trigger('click');

				    t.callRest();

				});

				$form.find("input").on("keyup", function(e){

				    if (e.keyCode === 13) $form.find("[type=submit]").trigger("click");

				});

			};

			t.initColumns = function(){

				var _columnsHTML = o.tplColumnCheck({});

				$.each(o.columns, function (index, column) {

					_columnsHTML += o.tplColumn(column);

				});

				_html.find("thead").find("tr").html(_columnsHTML);

				_html.find("thead").find("input:checkbox").on("change", function () {

					$("#panel-picker tbody input:checkbox").prop("checked", $(this).prop("checked")).trigger("change");

				});

			};

			t.initButtons = function(){

				_html.find("#btn-picker-select").on("click", function () {

					var $btn = $(this);

					$btn.btnload("load");

					t.select();

				});

				_html.find("#btn-picker-cancel").on("click", function () {

					var $btn = $(this);

					$btn.btnload("load");

					t.close();
					
				});

				_html.find("#btn-picker-close").on("click", function () {

					t.close();
					
				});

			};

			t.init = function(){
				
				o.height = getPageSize().height-140;
				o.filters = t.getFiltersHTML();

				_html = $(o.tpl(o));

				t.initForm();
				t.initColumns();
				t.initButtons();

				$("#panel-picker").remove();
				$("body").append(_html);
				
			};

			t.close = function () {

				$("#panel-picker").remove();

			};
			
			t.init();

		})(_options);

	};

})();