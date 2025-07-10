<?php
/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\widgets\Alert;
use yii\bootstrap5\Breadcrumbs;
use yii\bootstrap5\Html;
use yii\bootstrap5\BootstrapAsset;
use yii\web\YiiAsset;
use yii\bootstrap5\Nav;
use yii\bootstrap5\NavBar;

AppAsset::register($this);
BootstrapAsset::register($this);
YiiAsset::register($this);

$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.css', [
    'depends' => [\yii\bootstrap5\BootstrapAsset::class],
]);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.4/moment.min.js', [
    'depends' => [\yii\web\JqueryAsset::class],
]);
$this->registerJsFile('https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/3.0.5/daterangepicker.min.js', [
    'depends' => [\yii\web\JqueryAsset::class],
]);
$this->registerCssFile('@web/css/site.css', ['depends' => [\yii\bootstrap5\BootstrapAsset::class]]);
$this->registerCsrfMetaTags();
$this->registerMetaTag(['charset' => Yii::$app->charset], 'charset');
$this->registerMetaTag(['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1, shrink-to-fit=no']);
$this->registerMetaTag(['name' => 'description', 'content' => $this->params['meta_description'] ?? '']);
$this->registerMetaTag(['name' => 'keywords', 'content' => $this->params['meta_keywords'] ?? '']);
$this->registerLinkTag(['rel' => 'icon', 'type' => 'image/x-icon', 'href' => Yii::getAlias('@web/favicon.ico')]);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="h-100">
<head>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="d-flex flex-column h-100">
<?php $this->beginBody() ?>

<header id="header">
    <?php
    NavBar::begin([
        'brandLabel' => Html::encode(Yii::$app->name),
        'brandUrl' => Yii::$app->homeUrl,
        'options' => ['class' => 'navbar-expand-md navbar-dark bg-dark fixed-top'],
    ]);

    $menuItems = [
        ['label' => Yii::t('app', 'Home'), 'url' => ['/site/index']],
        ['label' => Yii::t('app', 'About'), 'url' => ['/site/about']],
        ['label' => Yii::t('app', 'Contact'), 'url' => ['/site/contact']],
    ];

    if (Yii::$app->user->isGuest) {
        $menuItems[] = ['label' => Yii::t('app', 'Login'), 'url' => ['/auth/login']];
        $menuItems[] = ['label' => Yii::t('app', 'Signup'), 'url' => ['/auth/signup']];
    } else {
        $menuItems[] = [
            'label' => Yii::t('app', 'Logout') . ' (' . Html::encode(Yii::$app->user->identity->username) . ')',
            'url' => ['/auth/logout'],
            'linkOptions' => ['data-method' => 'post'],
        ];
    }

    $currentLanguage = substr(Yii::$app->language, 0, 2);
    $languageLabel = $currentLanguage == 'en' ? 'English' : ($currentLanguage == 'ru' ? 'Русский' : 'Հայերեն');

    $menuItems[] = [
        'label' => $languageLabel,
        'items' => [
            ['label' => 'English', 'url' => ['/site/language', 'lang' => 'en']],
            ['label' => 'Русский', 'url' => ['/site/language', 'lang' => 'ru']],
            ['label' => 'Հայերեն', 'url' => ['/site/language', 'lang' => 'hy']],
        ],
    ];

    echo Nav::widget([
        'options' => ['class' => 'navbar-nav ms-auto'],
        'items' => $menuItems,
    ]);

    NavBar::end();
    ?>
</header>

<main id="main" class="flex-shrink-0" role="main">
    <div class="container">
        <?php if (!empty($this->params['breadcrumbs'])): ?>
            <?= Breadcrumbs::widget(['links' => $this->params['breadcrumbs']]) ?>
        <?php endif ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</main>

<footer id="footer" class="mt-auto py-3 bg-light">
    <div class="container">
        <div class="row text-muted">
            <div class="col-md-6 text-center text-md-start">&copy; <?= Html::encode(Yii::$app->name) ?> <?= date('Y') ?></div>
            <div class="col-md-6 text-center text-md-end"><?= Yii::powered() ?></div>
        </div>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
