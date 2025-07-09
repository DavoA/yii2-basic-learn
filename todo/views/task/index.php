<?php
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = Yii::t('app', 'Tasks');
$this->params['breadcrumbs'][] = $this->title;

$ajaxSearchUrl = Url::to(['task/search']);
?>

<div class="task-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p><?= Html::a(Yii::t('app', 'Create Task'), ['create'], ['class' => 'btn btn-success']) ?></p>

    <div id="grid-view-container">
        <?= $this->render('_grid', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]) ?>
    </div>

</div>

<?php
$this->registerJs(<<<JS
var ajaxSearchUrl = '$ajaxSearchUrl';

function bindToggleStatus() {
    $('.toggle-status').off('click').on('click', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        if (!id) {
            alert('Task ID missing');
            return;
        }
        $.ajax({
            url: '/task/toggle-status/' + id,
            type: 'POST',
            success: function(response) {
                if (response.success) {
                    var label = $('span.status-label[data-id="' + id + '"]');
                    label.text(response.status === 'completed' ? 'Completed' : 'Pending');
                } else {
                    alert(response.message || 'Unknown error');
                }
            },
            error: function() {
                alert('Server error');
            }
        });
    });
}

function reloadGridWithFilters() {
    var data = {
        'TaskSearch[status]': $('select[name="TaskSearch[status]"]').val(),
        'TaskSearch[title]': $('input[name="TaskSearch[title]"]').val(),
        'TaskSearch[description]': $('input[name="TaskSearch[description]"]').val(),
        'TaskSearch[date_range]': $('#tasksearch-date_range').val(),
    };

    $.ajax({
        url: ajaxSearchUrl,
        type: 'GET',
        data: data,
        success: function(response) {
            $('#grid-view-container').html(response);
            bindToggleStatus();
            initDateRangePicker();
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', status, error);
            alert('Filter error: ' + error);
        }
    });
}

function initDateRangePicker() {
    $('#tasksearch-date_range').daterangepicker({
        autoUpdateInput: false,
        locale: {
            format: 'YYYY-MM-DD',
            separator: ' to ',
            applyLabel: 'Apply',
            cancelLabel: 'Cancel',
        },
        opens: 'right',
        showDropdowns: true
    });

    $('#tasksearch-date_range').off('apply.daterangepicker').on('apply.daterangepicker', function(ev, picker) {
        var fromDate = picker.startDate.format('YYYY-MM-DD');
        var toDate = picker.endDate.format('YYYY-MM-DD');
        $(this).val(fromDate + ' to ' + toDate);
        reloadGridWithFilters();
    });

    $('#tasksearch-date_range').off('cancel.daterangepicker').on('cancel.daterangepicker', function(ev) {
        $(this).val('');
        reloadGridWithFilters();
    });
}

$(document).ready(function() {
    bindToggleStatus();
    initDateRangePicker();

    $('#grid-view-container').on('change', 'select, input:not(#tasksearch-date_range)', function() {
        reloadGridWithFilters();
    });
});
JS
);
?>