## Поведение для подключения LiveProf

Подключение в конфиге:

    'bootstrap' => [
        function(\yii\web\Application $app) {
            $app->attachBehavior('profilerBehavior', \app\behaviors\ProfilerBehavior::class);
        }
    ],
    'components' => [
        'profiler' => \app\domain\Utils\Profiler::class,

Название компонента не случайно, оно прописано внутри класса **Profiler**. 
Если нужно использовать другое название компонента - нужно переписать.

Компонент **Profiler** это фасад над профайлером, чтобы было короче вызывать его методы

Примеры использования фасада:

        // запуск профайлера
        Profiler::comp()-> start;
        
        ...

        // Использование таймера
        $t = Profiler::comp()->timer();

        foo(); // fun call

        Profiler::comp()->stopTimer($t);

Компонент использует следующие ENV переменные

    USE_PROFILER
    APP_NAME
    PROFILER_DB_HOST
    PROFILER_DB_PORT
    PROFILER_DB_NAME
    PROFILER_DB_USER
    PROFILER_DB_PASS
    PROFILER_DIVIDER_VALUE
    PROFILER_GLOBAL_DIVIDER_VALUE

По умолчанию использую бд в кач-ве хранилища данных. 
Миграция для создания таблицы логов - в папке migrations

    php yii migrate --migrationPath="@app/vendor/brezgalov/yii2-liveprof-behavior"

Репозиторий профайлера: [badoo/liveprof](https://github.com/badoo/liveprof)