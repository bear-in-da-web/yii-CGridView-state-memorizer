yii-cgirdview-memorizer
=======================

Yii CGridView state memorizer - Allows to remember state of the grid (with pagination and order) when you navigate through the pages.

It uses HTML5 local storage and it is very easy to implement it. You do not need to change controllers/models codes, only adjust codes of your views where cgridview is being used. 

Author: Sergio G https://github.com/bearwebua/

Installation:
-------------

Copy js file(s) fom js folder to your project codes and then add next Php+js code to your view file (at the top of the page, before cgridview is used):

```php
    <?php
    $formId = 'post-all-grid'; // your form ID (for each gridview this value is unique)
    $formIdForStorage = Yii::app()->user->id . '-' . $formId;
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/dataFilters.js');
    Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/blockui.js'); //optional
    ?>
````
```js
    <script>
    var dataObj = DataFilters.init('<?= $formId; ?>', '<?= $formIdForStorage; ?>', true); //set last param to false if you are not using blockui.js
    dataObj.checkExistance();
    $(document).ready(function() {
        dataObj.applyFilters();
    });
    </script>
````

Add next HTML+Php code somewhere below the above codes:

```html
    <div id="filtersIndicator" style="display: none;" class="float-left">
        <div class="text-left"><i>Some search filters applied. To see all results reset your search filters</i></div>
    </div>
````
```php
    <?= CHtml::link("Reset Search Filters", 'javascript:void(0)', array("onclick" => "dataObj.removeFielters();")); ?>
````

Adjust your gridview config by adding these params:

```php
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
````

Notes:
-------------

- you may find that its not efficient to load the page (in normal way) and then reload cgridview via ajax to apply filters. That's how this extension works and simplicity of integration and usage comes with a price.
- blockui.js file included into this extension source. You may use it to notify users about ajax actions. You may not use it and disable it or you can create your own notifications. See comments in dataFilters.js file for more info.
- you may use default "Yii Blog Demo" application under "demo" folder to test this extension on your PC. View example: https://github.com/bearwebua/yii-cgirdview-memorizer/blob/master/demo/protected/views/post/admin.php
- this extension does work (with some adjustments) with advanced search form functionality CGridView comes by default, but doesn't populate fields values of the form after update. Maybe will be fixed in next release 
