@servers(['web' => 'u334693063@154.56.33.183 -p65002'])

@setup
echo "Connect to server";
$repository = 'git@github.com:TouradGithub/himaya.git';
$branch = isset($branch) ? $branch : "master";
$app_dir = "u334693063";

$release = date('YmdHis');

$branch_path = "$app_dir/$branch";
$env_file_name = ".env.$branch";
$env_path = "$branch_path/$env_file_name";
echo '{{$env_path}}';
$keep = 1;
$new_release_dir = "/home/u334693063/domains/touradmedlemin.me/public_html/himaya";
$composer = "/home/u334693063/domains/touradmedlemin.me/public_html/himaya/composer.json";
@endsetup

<?php
$composer = '/home/u334693063/domains/touradmedlemin.me/public_html/himaya/composer.json';
echo file_exists($composer) ? 'Exists' : 'Does not exist';
?>

@story('deploy')

check_composer


@endstory

@task('check_composer')
    if [ -f "/home/u334693063/domains/touradmedlemin.me/public_html/himaya/composer.json" ]; then
        echo "composer.json exists."
        echo 'Pulling latest changes.'
        cd {{ $new_release_dir }}
        pwd
        git config --global user.email "touradmedlemin17734@gmail.com"
        git config --global user.name "Tourad"

        # Configure Git to handle divergent branches
        git config pull.rebase false  # Change this as needed (merge, rebase, ff only)

        if [[ `git status --porcelain` ]]; then
            echo "Changes detected, committing and pulling latest changes."
            git add .
            git commit -m "update"
            git pull origin {{ $branch }}
            composer update

        else
            echo "No changes detected, skipping commit and pull."
            git pull origin {{ $branch }}
            composer update

        fi
        echo 'Pulling latest changes Terminate.'


        echo "Composer install finished"
    else
        echo 'Cloning repository'
        echo 'Cloning branch {{ $branch }} from repository {{ $repository }} into {{ $new_release_dir }}'

        mkdir -p {{ $new_release_dir }}
        git clone --depth 1 --branch {{ $branch }} {{ $repository }} {{ $new_release_dir }}
        echo 'Cloning completed'

        echo "Running Composer install."
        cd {{ $new_release_dir }}
        composer install --no-interaction --prefer-dist --optimize-autoloader
        echo "Composer install finished"

        echo "Setting up the app"
        cd {{ $new_release_dir }}
        pwd
        free -g -h -t && sync && free -g -h
        echo "Run migrate"
        cp .env.example .env
        php artisan key:generate --force
        echo "Key generated"
        php artisan optimize:clear
        echo "Optimized cleared"
        echo "Migration complete"

        echo "Optimization complete"

        echo "OK"
        echo "View cleared and storage linked"
        free -h
    fi
@endtask











@task('succeed')
    free -g -h -t && sync && free -g -h -t
    echo 'Deployment completed successfully. The new {{$branch}} release {{$release}} is live now!'
@endtask

@php
// Function to check if the dire
function is_git_repository($dir) {
    $git_dir = $dir . '/.gitignore';
    return is_dir($git_dir);
}
@endphp
