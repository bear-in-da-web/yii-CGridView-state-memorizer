<?php
$this->breadcrumbs = array(
    'Manage Posts',
);

$formId = 'post-all-grid';
$formIdForStorage = Yii::app()->user->id . '-' . $formId;
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/dataFilters.js');
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/blockui.js'); //optional
?>
<script>

    var dataObj = DataFilters.init('<?= $formId; ?>', '<?= $formIdForStorage; ?>', true); //set it to false if you do not use blockui.js
    dataObj.checkExistance();
    $(document).ready(function() {
        dataObj.applyFilters();
    });

</script>

<h1>Manage Posts</h1>

<div id="filtersIndicator" style="display: none;" class="float-left">
    <div class="text-left"><i>Some search filters applied. To see all results reset your search filters</i></div>
</div>
<?= CHtml::link("Reset Search Filters", 'javascript:void(0)', array("onclick" => "dataObj.removeFielters();")); ?>

<?php
$this->widget('zii.widgets.grid.CGridView', array(
    'dataProvider' => $model->search(),
    'filter' => $model,
    'id' => $formId,
    'beforeAjaxUpdate' => 'js:function(id, options) {
                           if (($(".blockUI").length == 0) && (dataObj.useBackground)) {
                               $.setBackgroundStyle();
                               setTimeout(function() {
                                   $.unblockUI(); // fix for none-responsive UI if there\'s an error with JS
                               } , 1000);
                           }
                           dataObj.rememberSettings(options.url); //remeber current filters to HTML5 storage
                      }',
    'afterAjaxUpdate' => 'js:function(id, data){
                          if ((dataObj.useBackground)){
                              $.unblockUI();
                          }
            }',
    'columns' => array(
        array(
            'name' => 'title',
            'type' => 'raw',
            'value' => 'CHtml::link(CHtml::encode($data->title), $data->url)'
        ),
        array(
            'name' => 'status',
            'value' => 'Lookup::item("PostStatus",$data->status)',
            'filter' => Lookup::items('PostStatus'),
        ),
        array(
            'name' => 'create_time',
            'type' => 'datetime',
            'filter' => false,
        ),
        array(
            'class' => 'CButtonColumn',
        ),
    ),
));
?>
